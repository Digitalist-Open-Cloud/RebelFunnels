<?php

namespace Piwik\Plugins\RebelFunnels;

use Piwik\Config;
use Piwik\Container\StaticContainer;
use Piwik\Piwik;
use Piwik\Plugin\ControllerAdmin;

class Controller extends ControllerAdmin
{
    /**
     * The page that tells how funnels count and how to use the plugin, with the limit as it is set.
     */
    public function documentation(): string
    {
        Piwik::checkUserHasSomeWriteAccess();

        return $this->renderTemplate('documentation', [
            'maxSteps' => StaticContainer::get(SystemSettings::class)->getMaxSteps(),
            'rearchiveMonths' => (int) str_replace('last', '', (string) Config::getInstance()->General['rearchive_reports_in_past_last_n_months']),
        ]);
    }
}
