<?php

namespace Piwik\Plugins\RebelFunnels\Categories;

use Piwik\Category\Subcategory;
use Piwik\Plugins\RebelFunnels\FunnelReport;

class ManageFunnelsSubcategory extends Subcategory
{
    public const ID = 'RebelFunnels_ManageFunnels';

    protected $categoryId = FunnelReport::CATEGORY;
    protected $id = self::ID;
    protected $order = 9999;
}
