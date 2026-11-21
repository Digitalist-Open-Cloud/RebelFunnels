<?php

namespace Piwik\Plugins\RebelFunnels\RecordBuilders;

use Piwik\ArchiveProcessor;
use Piwik\ArchiveProcessor\Parameters;
use Piwik\ArchiveProcessor\Record;
use Piwik\ArchiveProcessor\RecordBuilder;
use Piwik\DataAccess\LogAggregator;
use Piwik\DataTable;
use Piwik\DataTable\Row;
use Piwik\Plugins\RebelFunnels\Archiver;
use Piwik\Plugins\RebelFunnels\Archiving\FunnelCounter;
use Piwik\Segment;
use Piwik\Segment\SegmentExpression;

/**
 * Archives one funnel as a blob record with a row per step that visits reached, labelled with
 * the number of the step. The subtable of a step has what the visits that left there did next.
 */
class FunnelRecord extends RecordBuilder
{
    private const MAX_NEXT_ACTIONS = 50;

    private array $funnel;
    private FunnelCounter $counter;

    public function __construct(array $funnel, FunnelCounter $counter)
    {
        // The steps are never cut off, the list of what visits did after a step is.
        parent::__construct(null, self::MAX_NEXT_ACTIONS, 'nb_visits');

        $this->funnel = $funnel;
        $this->counter = $counter;
    }

    public function isEnabled(ArchiveProcessor $archiveProcessor): bool
    {
        $idSite = $this->funnel['idsite'];

        return $idSite === 0 || in_array($idSite, $archiveProcessor->getParams()->getIdSites());
    }

    public function getRecordMetadata(ArchiveProcessor $archiveProcessor): array
    {
        return [Record::make(Record::TYPE_BLOB, Archiver::getRecordName($this->funnel))];
    }

    public function buildForNonDayPeriod(ArchiveProcessor $archiveProcessor): void
    {
        // Core reads the days of a period through one object, which only looks up the archives
        // of single reports for the first plugin it is asked about. A processor of our own
        // looks up ours, so days that were archived for the funnel alone are not left out.
        parent::buildForNonDayPeriod(new ArchiveProcessor(
            $archiveProcessor->getParams(),
            $archiveProcessor->getArchiveWriter(),
            $archiveProcessor->getLogAggregator()
        ));
    }

    public function getQueryOriginHint(): string
    {
        return 'RebelFunnels' . $this->funnel['idfunnel'];
    }

    protected function aggregate(ArchiveProcessor $archiveProcessor): array
    {
        $table = new DataTable();
        foreach ($this->counter->count($this->getLogAggregator($archiveProcessor), $this->funnel['steps']) as $index => $counts) {
            if ($counts['nb_visits'] === 0) {
                continue;
            }

            $row = new Row([Row::COLUMNS => [
                'label' => (string) ($index + 1),
                'nb_visits' => $counts['nb_visits'],
                'sum_time' => $counts['sum_time'],
                'revenue' => $counts['revenue'],
            ]]);
            $table->addRow($row);

            if ($counts['next']) {
                $next = new DataTable();
                foreach ($counts['next'] as $label => $visits) {
                    $next->addRowFromSimpleArray(['label' => $label, 'nb_visits' => $visits]);
                }
                $row->setSubtable($next);
            }
        }

        return [Archiver::getRecordName($this->funnel) => $table];
    }

    private function getLogAggregator(ArchiveProcessor $archiveProcessor): LogAggregator
    {
        $logAggregator = $archiveProcessor->getLogAggregator();
        $filter = $this->funnel['segment_filter'];

        if ($filter !== '') {
            $params = $archiveProcessor->getParams();
            $segment = new Segment(
                Segment::combine($params->getSegment()->getString(), SegmentExpression::AND_DELIMITER, $filter),
                $params->getIdSites(),
                $params->getDateTimeStart(),
                $params->getDateTimeEnd()
            );

            $logAggregator = new LogAggregator(new Parameters($params->getSite(), $params->getPeriod(), $segment));
        }

        $logAggregator->setQueryOriginHint($this->getQueryOriginHint());

        return $logAggregator;
    }
}
