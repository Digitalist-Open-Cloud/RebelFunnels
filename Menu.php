<?php

namespace Piwik\Plugins\RebelFunnels;

use Piwik\Menu\MenuAdmin;
use Piwik\Piwik;

class Menu extends \Piwik\Plugin\Menu
{
    public function configureAdminMenu(MenuAdmin $menu)
    {
        if (Piwik::isUserHasSomeWriteAccess()) {
            $menu->addSystemItem('RebelFunnels_Documentation', $this->urlForAction('documentation'), 62);
        }
    }
}
