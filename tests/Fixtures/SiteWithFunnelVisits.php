<?php

namespace Piwik\Plugins\RebelFunnels\tests\Fixtures;

use Piwik\Plugins\Goals\API as GoalsApi;
use Piwik\Tests\Framework\Fixture;

/**
 * A website with a goal for the page /thanks and the visits of FunnelVisits.
 */
class SiteWithFunnelVisits extends Fixture
{
    public function setUp(): void
    {
        if (!self::siteCreated(1)) {
            self::createWebsite('2024-01-01 00:00:00');
            GoalsApi::getInstance()->addGoal(1, 'Thanks page', 'url', '/thanks', 'contains');
        }

        FunnelVisits::track(1);
    }

    public function tearDown(): void
    {
    }
}
