<?php

namespace Piwik\Plugins\RebelFunnels;

use Piwik\DataTable;
use Piwik\Piwik;
use Piwik\Plugin\ViewDataTable;
use Piwik\Plugins\RebelFunnels\Matching\Rules;
use Piwik\Plugins\RebelFunnels\Metrics\Ratio;
use Piwik\Plugins\RebelFunnels\Metrics\Revenue;
use Piwik\Plugins\RebelFunnels\Visualizations\Funnel;

/**
 * The steps of a funnel with the visits that reached each of them.
 */
class GetSteps extends FunnelReport
{
    protected function init()
    {
        $this->action = 'getSteps';
        $this->order = 1;
        $this->metrics = ['nb_visits', 'funnel_proceeded', 'funnel_exits'];
        $this->processedMetrics = [
            new Ratio('funnel_step_rate', 'RebelFunnels_ColumnStepRate', 'nb_visits', 'funnel_entries', false, ['funnel_entries']),
            new Ratio('funnel_dropoff_rate', 'RebelFunnels_ColumnDropoffRate', 'funnel_exits', 'nb_visits'),
            new Ratio('funnel_avg_step_time', 'RebelFunnels_ColumnAvgStepTime', 'sum_time', 'nb_visits', true, ['sum_time']),
            new Revenue(),
        ];
        $this->defaultSortColumn = '';
        // The rows below a step are what the visits that left there did next.
        $this->actionToLoadSubTables = $this->action;

        parent::init();
    }

    public function getDefaultTypeViewDataTable()
    {
        return Funnel::ID;
    }

    public function configureView(ViewDataTable $view)
    {
        parent::configureView($view);

        $view->config->addTranslation('label', Piwik::translate('RebelFunnels_Step'));
        $view->config->columns_to_display = ['label', 'nb_visits', 'funnel_step_rate', 'funnel_proceeded', 'funnel_exits', 'funnel_dropoff_rate', 'funnel_avg_step_time'];
        if ($this->funnel && Rules::getGoalIds(array_merge(...array_column($this->funnel['steps'], 'rules')))) {
            // Only a step that a goal or an order reaches has revenue.
            $view->config->columns_to_display[] = 'funnel_revenue';
        }
        // The rows are the steps in their order.
        $view->config->enable_sort = false;
        $view->config->show_search = false;
        $view->config->show_exclude_low_population = false;
        $view->config->show_limit_control = false;
        $view->config->show_pagination_control = false;
        $view->config->show_offset_information = false;
        $view->config->show_flatten_table = false;
        if (empty($view->requestConfig->idSubtable)) {
            // Nothing comes after the last step, so it has nobody who went on or left.
            $view->config->filters[] = function (DataTable $table) {
                $last = $table->getLastRow();
                if ($last && !$last->hasColumn('funnel_proceeded')) {
                    foreach (['funnel_proceeded', 'funnel_exits', 'funnel_dropoff_rate'] as $column) {
                        $last->setColumn($column, '-');
                    }
                }
            };
        }

        $view->requestConfig->filter_sort_column = '';
        $view->requestConfig->filter_limit = -1;
    }
}
