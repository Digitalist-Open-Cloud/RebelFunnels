<?php

namespace Piwik\Plugins\RebelFunnels\Categories;

use Piwik\Category\Category;
use Piwik\Plugins\RebelFunnels\FunnelReport;

class FunnelsCategory extends Category
{
    protected $id = FunnelReport::CATEGORY;
    protected $order = 66;
    protected $icon = 'rebelFunnelsIcon';
}
