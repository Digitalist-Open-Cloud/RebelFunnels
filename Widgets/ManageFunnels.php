<?php

namespace Piwik\Plugins\RebelFunnels\Widgets;

use Piwik\Config;
use Piwik\Container\StaticContainer;
use Piwik\Piwik;
use Piwik\Plugins\RebelFunnels\Categories\ManageFunnelsSubcategory;
use Piwik\Plugins\RebelFunnels\FunnelReport;
use Piwik\Plugins\RebelFunnels\SystemSettings;
use Piwik\Request;
use Piwik\Widget\Widget;
use Piwik\Widget\WidgetConfig;

/**
 * The page to create, change and delete funnels.
 */
class ManageFunnels extends Widget
{
    public static function configure(WidgetConfig $config)
    {
        $idSite = Request::fromRequest()->getIntegerParameter('idSite', 0);

        $config->setCategoryId(FunnelReport::CATEGORY);
        $config->setSubcategoryId(ManageFunnelsSubcategory::ID);
        $config->setName(ManageFunnelsSubcategory::ID);
        $config->setIsNotWidgetizable();
        $config->setIsEnabled($idSite > 0 && Piwik::isUserHasWriteAccess($idSite));
    }

    public function render()
    {
        Piwik::checkUserHasWriteAccess(Request::fromRequest()->getIntegerParameter('idSite'));

        $months = (int) str_replace('last', '', (string) Config::getInstance()->General['rearchive_reports_in_past_last_n_months']);

        return sprintf(
            '<div vue-entry="RebelFunnels.ManageFunnels" rearchive-months="%d" max-steps="%d"></div>',
            $months,
            StaticContainer::get(SystemSettings::class)->getMaxSteps()
        );
    }
}
