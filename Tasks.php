<?php

namespace Piwik\Plugins\RebelFunnels;

use Piwik\Container\StaticContainer;
use Piwik\Plugins\RebelFunnels\Archiving\ObsoleteRecords;

class Tasks extends \Piwik\Plugin\Tasks
{
    public function schedule()
    {
        $this->daily('purgeObsoleteRecords', null, self::LOW_PRIORITY);
    }

    /**
     * Removes the archived data of funnels that were deleted or whose data changed.
     */
    public function purgeObsoleteRecords(): void
    {
        StaticContainer::get(ObsoleteRecords::class)->purge();
    }
}
