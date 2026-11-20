<?php

namespace Piwik\Plugins\RebelFunnels;

use Piwik\Piwik;
use Piwik\Plugin\ViewDataTable;
use Piwik\Plugins\CoreVisualizations\Visualizations\JqplotGraph\Evolution;
use Piwik\Plugins\RebelFunnels\Metrics\Ratio;

/**
 * How many visits entered a funnel and how many reached its last step, over time.
 */
class GetSummary extends FunnelReport
{
    protected function init()
    {
        $this->action = 'getSummary';
        $this->order = 2;
        $this->metrics = ['funnel_entries', 'funnel_conversions'];
        $this->processedMetrics = [
            new Ratio('funnel_conversion_rate', 'General_ColumnConversionRate', 'funnel_conversions', 'funnel_entries'),
        ];

        parent::init();
    }

    public function initFromFunnel(array $funnel): void
    {
        parent::initFromFunnel($funnel);

        $this->name = Piwik::translate('RebelFunnels_SummaryReportName', $funnel['name']);
    }

    public function getDefaultTypeViewDataTable()
    {
        return Evolution::ID;
    }

    public function configureView(ViewDataTable $view)
    {
        parent::configureView($view);

        $view->config->columns_to_display = ['funnel_conversion_rate'];
        $view->config->selectable_columns = ['funnel_conversion_rate', 'funnel_entries', 'funnel_conversions'];
    }
}
