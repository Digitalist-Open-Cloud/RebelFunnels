<?php

namespace Piwik\Plugins\RebelFunnels;

use Piwik\Archive;
use Piwik\ArchiveProcessor\Parameters;
use Piwik\Common;
use Piwik\DataAccess\LogAggregator;
use Piwik\DataTable;
use Piwik\DataTable\DataTableInterface;
use Piwik\DataTable\Row;
use Piwik\Date;
use Piwik\Period\Factory as PeriodFactory;
use Piwik\Piwik;
use Piwik\Plugins\RebelFunnels\Archiving\ArchiveStatus;
use Piwik\Plugins\RebelFunnels\Archiving\FunnelCounter;
use Piwik\Plugins\RebelFunnels\Matching\Rules;
use Piwik\Segment;
use Piwik\Site;
use Piwik\Tracker\Action;

/**
 * Funnels: how many visits get through an ordered list of steps, and where they leave.
 *
 * A step has a "name", "rules" and optionally "direct": 1 if only the very next action of the
 * visit after the step before counts, instead of any later action. A rule has a "type" ("url", "title", "event_category",
 * "event_action", "event_name", "download", "outlink" or "search"), an "operator" ("equals",
 * "contains", "starts_with", "ends_with" or "regex") and a "value". A step is reached by an
 * action that any of its rules match. Matching ignores case, and URLs are compared without
 * protocol and "www.".
 *
 * A rule of the type "form_start", "form_submit" or "form_success" is matched by a form of the
 * plugin RebelMyForms whose name matches, when that plugin is active.
 * A rule of the type "goal" is matched by a conversion instead: its "value" is the ID of a goal
 * of the website, "order" for an ecommerce order or "cart" for an abandoned cart. It needs no
 * operator. A funnel for all websites can only use "order" and "cart".
 *
 * @method static \Piwik\Plugins\RebelFunnels\API getInstance()
 */
class API extends \Piwik\Plugin\API
{
    // How many days the preview of a funnel that is being edited counts.
    private const PREVIEW_DAYS = 7;

    private Model $model;
    private FunnelManager $funnelManager;
    private ArchiveStatus $archiveStatus;
    private FunnelCounter $counter;

    public function __construct(Model $model, FunnelManager $funnelManager, ArchiveStatus $archiveStatus, FunnelCounter $counter)
    {
        $this->model = $model;
        $this->funnelManager = $funnelManager;
        $this->archiveStatus = $archiveStatus;
        $this->counter = $counter;
    }

    /**
     * Returns the funnels of a site, including the funnels defined for all websites.
     *
     * @return array[]
     */
    public function getFunnels(int $idSite): array
    {
        Piwik::checkUserHasViewAccess($idSite);

        return $this->model->getFunnels($idSite);
    }

    /**
     * Returns the definition of one funnel.
     *
     * @throws \Exception if the site has no such funnel
     */
    public function getFunnel(int $idSite, int $idFunnel): array
    {
        Piwik::checkUserHasViewAccess($idSite);

        return $this->funnelManager->getFunnel($idSite, $idFunnel);
    }

    /**
     * Returns for each funnel of the site which days it has archived data for.
     *
     * @return array[] one entry per funnel: idfunnel, first_date and last_date (the first and
     *                 the last day with archived data, null when there is none), is_queued
     *                 (whether more is waiting to be archived) and queued_days (how many days
     *                 are waiting)
     */
    public function getArchiveStatus(int $idSite): array
    {
        Piwik::checkUserHasViewAccess($idSite);

        return array_values($this->archiveStatus->get($this->model->getFunnels($idSite), $idSite));
    }

    /**
     * Creates a funnel. Its data is archived the next time archiving runs.
     *
     * @param array[] $steps two or more steps in their order, see the description of this API
     * @param string $segmentFilter a segment the funnel is always restricted to
     * @param bool $allWebsites create the funnel for all websites, requires super user access
     * @return int the ID of the funnel
     */
    public function addFunnel(int $idSite, string $name, array $steps, string $description = '', string $segmentFilter = '', bool $allWebsites = false): int
    {
        Piwik::checkUserHasWriteAccess($idSite);
        if ($allWebsites) {
            Piwik::checkUserHasSuperUserAccess();
        }

        return $this->funnelManager->addFunnel($idSite, $this->toDefinition($name, $steps, $description, $segmentFilter), $allWebsites);
    }

    /**
     * Changes a funnel. If its rules or its segment change, its data is built again.
     *
     * @param array[] $steps
     */
    public function updateFunnel(int $idSite, int $idFunnel, string $name, array $steps, string $description = '', string $segmentFilter = ''): void
    {
        $funnel = $this->getFunnelToManage($idSite, $idFunnel);

        $this->funnelManager->updateFunnel($idSite, $funnel, $this->toDefinition($name, $steps, $description, $segmentFilter));
    }

    /**
     * Deletes a funnel and its archived data.
     */
    public function deleteFunnel(int $idSite, int $idFunnel): void
    {
        $this->funnelManager->deleteFunnel($this->getFunnelToManage($idSite, $idFunnel));
    }

    /**
     * Pauses a funnel: it keeps its archived data, but gets no new data until it is resumed.
     */
    public function pauseFunnel(int $idSite, int $idFunnel): void
    {
        $this->funnelManager->pauseFunnel($this->getFunnelToManage($idSite, $idFunnel));
    }

    /**
     * Resumes a paused funnel. The time it was paused is archived as well.
     */
    public function resumeFunnel(int $idSite, int $idFunnel): void
    {
        $this->funnelManager->resumeFunnel($this->getFunnelToManage($idSite, $idFunnel));
    }

    /**
     * Copies a funnel, within its website or to another one. A funnel with a goal as a step
     * can only be copied to a website that has a goal with the same ID.
     *
     * @param int $idDestinationSite the website to copy the funnel to. By default the funnel
     *                               is copied within its website, with "(copy)" in its name.
     * @return int the ID of the new funnel
     */
    public function duplicateFunnel(int $idSite, int $idFunnel, int $idDestinationSite = 0): int
    {
        Piwik::checkUserHasViewAccess($idSite);
        $idDestinationSite = $idDestinationSite ?: $idSite;
        Piwik::checkUserHasWriteAccess($idDestinationSite);

        return $this->funnelManager->duplicateFunnel($this->funnelManager->getFunnel($idSite, $idFunnel), $idDestinationSite);
    }

    /**
     * Sets the order of the funnels of a site in the reporting menu.
     *
     * @param int[] $idFunnels the IDs of all funnels of the site in their new order. Moving a
     *                         funnel that is defined for all websites requires super user access.
     */
    public function setFunnelOrder(int $idSite, array $idFunnels): void
    {
        Piwik::checkUserHasWriteAccess($idSite);

        $this->funnelManager->reorderFunnels($idSite, array_map('intval', $idFunnels), Piwik::hasUserSuperUserAccess());
    }

    /**
     * Returns the steps of a funnel with the visits that reached each of them.
     *
     * @param string|false $segment
     * @param int|string|false $idSubtable the idsubdatatable of a step, to get what the visits
     *                                     that left at the step did next
     * @return DataTable|DataTable\Map one row per step: nb_visits (visits that reached it),
     *         funnel_step_rate (their share of the visits that entered the funnel),
     *         funnel_avg_step_time (seconds from the step before), funnel_revenue (the revenue
     *         of the goal conversions and orders that reached the step) and, except for the last step,
     *         funnel_proceeded (visits that went on to the next step), funnel_exits (visits that
     *         left) and funnel_dropoff_rate. With $idSubtable: one row per next action, with
     *         nb_visits.
     */
    public function getSteps(int $idSite, string $period, string $date, int $idFunnel, $segment = false, $idSubtable = false): DataTableInterface
    {
        Piwik::checkUserHasViewAccess($idSite);

        $funnel = $this->funnelManager->getFunnel($idSite, $idFunnel);
        $table = Archive::createDataTableFromArchive(Archiver::getRecordName($funnel), $idSite, $period, $date, $segment, false, false, $idSubtable);

        if (!empty($idSubtable)) {
            $table->filter('ColumnCallbackReplace', ['label', [$this, 'getNextActionLabel']]);
            $table->filter('Sort', ['nb_visits', 'desc']);

            return $table;
        }

        $table->filter(function (DataTable $dataTable) use ($funnel) {
            $rows = [];
            foreach ($dataTable->getRows() as $row) {
                $rows[(int) $row->getColumn('label') - 1] = $row;
            }

            $ordered = [];
            foreach ($this->toStepRows($funnel['steps'], array_map(fn (Row $row) => $row->getColumns(), $rows)) as $index => $columns) {
                // The archived row is kept, as it leads to what visits did after the step.
                $row = $rows[$index] ?? new Row();
                $row->setColumns($columns);
                $ordered[] = $row;
            }
            $dataTable->setRows($ordered);
        });

        return $table;
    }

    /**
     * @param string $label a label of FunnelCounter for what a visit did next
     * @internal
     */
    public function getNextActionLabel(string $label): string
    {
        if ($label === FunnelCounter::NEXT_EXIT) {
            return Piwik::translate('RebelFunnels_NextExit');
        }
        if ($label === FunnelCounter::NEXT_OTHER) {
            return Piwik::translate('RebelFunnels_NextOther');
        }

        $parts = explode(':', $label, 2);
        if (count($parts) < 2 || !ctype_digit($parts[0])) {
            // The row core sums the rarest actions into.
            return $label;
        }

        $prefixes = [
            Action::TYPE_OUTLINK => 'RebelFunnels_NextOutlink',
            Action::TYPE_DOWNLOAD => 'RebelFunnels_NextDownload',
            Action::TYPE_SITE_SEARCH => 'RebelFunnels_NextSearch',
            Action::TYPE_EVENT_ACTION => 'RebelFunnels_NextEvent',
        ];

        return isset($prefixes[(int) $parts[0]]) ? Piwik::translate($prefixes[(int) $parts[0]], $parts[1]) : $parts[1];
    }

    /**
     * Returns how many visits entered a funnel and how many reached its last step.
     *
     * @param string|false $segment
     * @return DataTable|DataTable\Map funnel_entries, funnel_conversions, funnel_conversion_rate and
     *         funnel_revenue (the revenue of the conversions that reached the last step)
     */
    public function getSummary(int $idSite, string $period, string $date, int $idFunnel, $segment = false): DataTableInterface
    {
        Piwik::checkUserHasViewAccess($idSite);

        $funnel = $this->funnelManager->getFunnel($idSite, $idFunnel);
        $table = Archive::createDataTableFromArchive(Archiver::getRecordName($funnel), $idSite, $period, $date, $segment);

        return $this->toSummary($table, count($funnel['steps']));
    }

    /**
     * Counts steps that are not saved from the raw data of the last days, for the preview in
     * the funnel editor.
     *
     * @param array[] $steps
     * @return array[] per step: name, nb_visits, funnel_proceeded, funnel_exits, funnel_avg_step_time
     *                 and funnel_revenue
     */
    public function previewFunnel(int $idSite, array $steps, string $segmentFilter = ''): array
    {
        Piwik::checkUserHasWriteAccess($idSite);

        $steps = $this->funnelManager->validateSteps($this->toDefinition('', $steps, '', '')['steps'], $idSite);

        $timezone = Site::getTimezoneFor($idSite);
        $today = Date::factoryInTimezone('today', $timezone);
        $period = PeriodFactory::build('range', $today->subDay(self::PREVIEW_DAYS - 1)->toString() . ',' . $today->toString(), $timezone);
        $segment = new Segment(Common::unsanitizeInputValue($segmentFilter), [$idSite], $period->getDateTimeStart(), $period->getDateTimeEnd());
        $logAggregator = new LogAggregator(new Parameters(new Site($idSite), $period, $segment));
        $logAggregator->setQueryOriginHint('RebelFunnelsPreview');

        $rows = [];
        foreach ($this->toStepRows($steps, $this->counter->count($logAggregator, $steps)) as $row) {
            $rows[] = [
                'name' => $row['label'],
                'nb_visits' => $row['nb_visits'],
                'funnel_proceeded' => $row['funnel_proceeded'] ?? null,
                'funnel_exits' => $row['funnel_exits'] ?? null,
                'funnel_avg_step_time' => (int) Piwik::getQuotientSafe($row['sum_time'], $row['nb_visits'], 0),
                'funnel_revenue' => $row['revenue'],
            ];
        }

        return $rows;
    }

    /**
     * Tells which steps a page URL matches.
     *
     * @param array[] $steps
     * @return bool[] one entry per step
     */
    public function testUrl(int $idSite, array $steps, string $url): array
    {
        Piwik::checkUserHasWriteAccess($idSite);

        $steps = $this->funnelManager->validateSteps($this->toDefinition('', $steps, '', '')['steps'], $idSite);

        return Rules::testUrl($steps, Common::unsanitizeInputValue($url));
    }

    /**
     * @param array[] $steps
     * @param array<int, array> $counts by step index, nb_visits and sum_time; steps no visit reached may be missing
     * @return array[] the columns of one row per step
     */
    private function toStepRows(array $steps, array $counts): array
    {
        $entries = (int) ($counts[0]['nb_visits'] ?? 0);

        $rows = [];
        foreach ($steps as $index => $step) {
            $visits = (int) ($counts[$index]['nb_visits'] ?? 0);
            $row = [
                // Names are stored as the API receives them, HTML-encoded.
                'label' => Common::unsanitizeInputValue($step['name']),
                'nb_visits' => $visits,
                'funnel_entries' => $entries,
                'sum_time' => (int) ($counts[$index]['sum_time'] ?? 0),
                'revenue' => (float) ($counts[$index]['revenue'] ?? 0),
            ];

            if (isset($steps[$index + 1])) {
                $row['funnel_proceeded'] = (int) ($counts[$index + 1]['nb_visits'] ?? 0);
                $row['funnel_exits'] = $visits - $row['funnel_proceeded'];
            }

            $rows[] = $row;
        }

        return $rows;
    }

    /**
     * Core only compares a table without labels with another period or segment if it is a
     * Simple table, like the "get" reports of core return.
     *
     * @param DataTable|DataTable\Map $table
     * @return DataTable|DataTable\Map
     */
    private function toSummary(DataTableInterface $table, int $stepCount): DataTableInterface
    {
        if ($table instanceof DataTable\Map) {
            foreach ($table->getDataTables() as $label => $tableOfPeriod) {
                $table->addTable($this->toSummary($tableOfPeriod, $stepCount), $label);
            }

            return $table;
        }

        $first = $table->getRowFromLabel('1');
        $last = $table->getRowFromLabel((string) $stepCount);

        $simple = new DataTable\Simple();
        $simple->setAllTableMetadata($table->getAllTableMetadata());
        $simple->addRowsFromArray([
            'funnel_entries' => $first ? (int) $first->getColumn('nb_visits') : 0,
            'funnel_conversions' => $last ? (int) $last->getColumn('nb_visits') : 0,
            'revenue' => $last ? (float) $last->getColumn('revenue') : 0.0,
        ]);

        return $simple;
    }

    private function getFunnelToManage(int $idSite, int $idFunnel): array
    {
        Piwik::checkUserHasWriteAccess($idSite);

        $funnel = $this->funnelManager->getFunnel($idSite, $idFunnel);
        if ($funnel['idsite'] === 0) {
            Piwik::checkUserHasSuperUserAccess();
        }

        return $funnel;
    }

    /**
     * Rule values and segments are needed as they were typed, not HTML-encoded.
     */
    private function toDefinition(string $name, array $steps, string $description, string $segmentFilter): array
    {
        foreach ($steps as &$step) {
            foreach ($step['rules'] ?? [] as &$rule) {
                if (isset($rule['value'])) {
                    $rule['value'] = Common::unsanitizeInputValue($rule['value']);
                }
            }
        }

        return [
            'name' => $name,
            'description' => $description,
            'steps' => $steps,
            'segment_filter' => Common::unsanitizeInputValue($segmentFilter),
        ];
    }
}
