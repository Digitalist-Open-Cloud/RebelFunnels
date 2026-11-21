<?php

namespace Piwik\Plugins\RebelFunnels\Metrics;

use Piwik\Archive\DataTableFactory;
use Piwik\Columns\Dimension;
use Piwik\DataTable;
use Piwik\DataTable\Row;
use Piwik\Metrics\Formatter;
use Piwik\Piwik;
use Piwik\Plugin\ProcessedMetric;
use Piwik\Request;

/**
 * The revenue of the goal conversions and orders that reached a step, in the currency of the
 * website.
 */
class Revenue extends ProcessedMetric
{
    private int $idSite = 0;

    public function getName()
    {
        return 'funnel_revenue';
    }

    public function getTranslatedName()
    {
        return Piwik::translate('General_ColumnRevenue');
    }

    public function getDependentMetrics()
    {
        return ['revenue'];
    }

    public function getTemporaryMetrics()
    {
        return ['revenue'];
    }

    public function compute(Row $row)
    {
        return round((float) self::getMetric($row, 'revenue'), 2);
    }

    public function format($value, Formatter $formatter)
    {
        return $this->idSite > 0 ? $formatter->getPrettyMoney($value, $this->idSite) : $value;
    }

    public function beforeCompute($report, DataTable $table)
    {
        // A table of a period or segment that is compared with does not name its website.
        $this->idSite = (int) DataTableFactory::getSiteIdFromMetadata($table)
            ?: Request::fromRequest()->getIntegerParameter('idSite', 0);

        return true;
    }

    public function getSemanticType(): ?string
    {
        return Dimension::TYPE_MONEY;
    }
}
