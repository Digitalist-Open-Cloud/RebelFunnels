<?php

namespace Piwik\Plugins\RebelFunnels\Visualizations;

use Piwik\API\Request as ApiRequest;
use Piwik\Common;
use Piwik\DataTable;
use Piwik\Period\Factory as PeriodFactory;
use Piwik\Plugin\ViewDataTable;
use Piwik\Plugin\Visualization;
use Piwik\Request;
use Piwik\Segment;
use Piwik\Site;

/**
 * Shows the steps of a funnel as bars, with how many visits went on and how many left between
 * them, and the conversion rate of each period and segment it is compared with.
 */
class Funnel extends Visualization
{
    public const ID = 'rebelFunnel';
    public const TEMPLATE_FILE = '@RebelFunnels/_dataTableViz_funnel.twig';
    public const FOOTER_ICON = 'icon-funnel';
    public const FOOTER_ICON_TITLE = 'RebelFunnels_Funnel';

    public static function canDisplayViewDataTable(ViewDataTable $view)
    {
        return $view->requestConfig->getApiModuleToRequest() === 'RebelFunnels'
            && $view->requestConfig->getApiMethodToRequest() === 'getSteps';
    }

    public function supportsComparison()
    {
        return true;
    }

    public function beforeRender()
    {
        $request = Request::fromRequest();
        $idSite = $request->getIntegerParameter('idSite');
        $period = $request->getStringParameter('period');
        $date = $request->getStringParameter('date');

        $periods = [[$period, $date]];
        $compareDates = $request->getArrayParameter('compareDates', []);
        foreach ($request->getArrayParameter('comparePeriods', []) as $index => $comparePeriod) {
            if (isset($compareDates[$index])) {
                $periods[] = [(string) $comparePeriod, (string) $compareDates[$index]];
            }
        }
        $segments = array_merge([null], Common::unsanitizeInputValues($request->getArrayParameter('compareSegments', [])));

        $comparisons = [];
        foreach ($segments as $segment) {
            $segmentName = $segment === null ? '' : (new Segment($segment, [$idSite]))->getStoredSegmentName($idSite);
            foreach ($periods as $index => [$comparePeriod, $compareDate]) {
                if ($segment === null && $index === 0) {
                    continue;
                }

                $steps = $this->getSteps($comparePeriod, $compareDate, $segment);
                $periodLabel = PeriodFactory::build($comparePeriod, $compareDate)->getLocalizedShortString();
                $comparisons[] = [
                    'label' => implode(', ', array_filter([$segmentName, $segment === null || $index > 0 ? $periodLabel : ''])),
                    'funnel_entries' => $steps ? $steps[0]['nb_visits'] : 0,
                    'funnel_conversions' => $steps ? end($steps)['nb_visits'] : 0,
                ];
            }
        }

        $this->assignTemplateVar('currency', Site::getCurrencySymbolFor($idSite));
        $this->assignTemplateVar('idFunnel', $request->getIntegerParameter('idFunnel', 0));
        $this->assignTemplateVar('steps', $this->getSteps($period, $date, null));
        $this->assignTemplateVar('comparisons', $comparisons);
    }

    /**
     * @param string|null $segment null for the segment of the request
     * @return array[] the unformatted numbers of each step
     */
    private function getSteps(string $period, string $date, ?string $segment): array
    {
        $table = ApiRequest::processRequest('RebelFunnels.getSteps', [
            'idFunnel' => Request::fromRequest()->getIntegerParameter('idFunnel', 0),
            'period' => $period,
            'date' => $date,
            'format_metrics' => '0',
            'compare' => '0',
            'filter_limit' => '-1',
        ] + ($segment === null ? [] : ['segment' => $segment]));

        $steps = [];
        foreach ($table instanceof DataTable ? $table->getRows() : [] as $row) {
            $steps[] = [
                // The API encodes labels for HTML, and the template encodes what it prints again.
                'name' => Common::unsanitizeInputValue((string) $row->getColumn('label')),
                'nb_visits' => (int) $row->getColumn('nb_visits'),
                'funnel_proceeded' => $row->hasColumn('funnel_proceeded') ? (int) $row->getColumn('funnel_proceeded') : null,
                'funnel_exits' => $row->hasColumn('funnel_exits') ? (int) $row->getColumn('funnel_exits') : null,
                'funnel_avg_step_time' => (int) $row->getColumn('funnel_avg_step_time'),
                'funnel_revenue' => (float) $row->getColumn('funnel_revenue'),
                'idsubtable' => $row->getIdSubDataTable(),
            ];
        }

        return $steps;
    }
}
