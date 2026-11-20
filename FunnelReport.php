<?php

namespace Piwik\Plugins\RebelFunnels;

use Piwik\Container\StaticContainer;
use Piwik\Piwik;
use Piwik\Plugin\Report;
use Piwik\Plugin\ViewDataTable;
use Piwik\Plugins\RebelFunnels\Archiving\ArchiveStatus;
use Piwik\Request;

/**
 * A report of one funnel. Not in the Reports folder because there is one instance per funnel,
 * added by the Report.addReports event.
 */
abstract class FunnelReport extends Report
{
    public const CATEGORY = 'RebelFunnels_Funnels';
    public const PAGE_PREFIX = 'RebelFunnels_funnel_';

    protected ?array $funnel = null;

    protected function init()
    {
        parent::init();

        $this->module = 'RebelFunnels';
        $this->categoryId = self::CATEGORY;
        $this->name = Piwik::translate(self::CATEGORY);

        // Core creates this class without arguments to handle a request for one funnel.
        $request = Request::fromRequest();
        $idSite = $request->getIntegerParameter('idSite', 0);
        $idFunnel = $request->getIntegerParameter('idFunnel', 0);

        if ($idSite > 0 && $idFunnel > 0) {
            try {
                $this->initFromFunnel(StaticContainer::get(FunnelManager::class)->getFunnel($idSite, $idFunnel));
            } catch (\Exception $e) {
                // No such funnel for the site: the report stays disabled.
            }
        }
    }

    public function initFromFunnel(array $funnel): void
    {
        $this->funnel = $funnel;
        $this->name = $funnel['name'];
        $this->documentation = $funnel['description'];
        $this->subcategoryId = self::PAGE_PREFIX . $funnel['idfunnel'];
        $this->parameters = ['idFunnel' => $funnel['idfunnel']];
    }

    public function isEnabled()
    {
        return $this->funnel !== null;
    }

    public function configureView(ViewDataTable $view)
    {
        if ($this->funnel === null) {
            return;
        }

        $idFunnel = $this->funnel['idfunnel'];

        // Export and row evolution requests need to name the funnel again.
        $view->requestConfig->request_parameters_to_modify['idFunnel'] = $idFunnel;
        $view->requestConfig->request_parameters_to_modify['reportUniqueId'] = sprintf('RebelFunnels_%s_idFunnel--%d', $this->action, $idFunnel);

        $idSite = Request::fromRequest()->getIntegerParameter('idSite', 0);
        $view->config->show_footer_message = (new ArchiveStatus())->getBuildingMessage($this->funnel, $idSite);
        $view->config->show_goals = false;
        $view->config->show_table_all_columns = false;
        $view->config->show_insights = false;
    }

    public function configureReportMetadata(&$availableReports, $infos)
    {
        if (!$this->isEnabled()) {
            return;
        }

        $idSites = $infos['idSites'] ?? [];
        if (!empty($infos['idSite']) || count($idSites) === 1) {
            $availableReports[] = $this->buildReportMetadata();
        }
    }
}
