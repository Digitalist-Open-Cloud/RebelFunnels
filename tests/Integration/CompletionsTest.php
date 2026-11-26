<?php

/**
 * Matomo - free/libre analytics platform
 *
 * @link    https://matomo.org
 * @license https://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */

namespace Piwik\Plugins\RebelFunnels\tests\Integration;

use Piwik\ArchiveProcessor\Parameters;
use Piwik\Common;
use Piwik\DataAccess\LogAggregator;
use Piwik\Db;
use Piwik\Period\Factory as PeriodFactory;
use Piwik\Plugins\RebelFunnels\Archiving\FunnelCounter;
use Piwik\Segment;
use Piwik\Site;
use Piwik\Tests\Framework\Fixture;
use Piwik\Tests\Framework\TestCase\IntegrationTestCase;

/**
 * @group RebelFunnels
 * @group Plugins
 */
class CompletionsTest extends IntegrationTestCase
{
    private const DAY = '2024-03-05';
    private const STEPS = [
        ['name' => 'Cart', 'rules' => [['type' => 'url', 'operator' => 'contains', 'value' => '/cart']]],
        ['name' => 'Thanks', 'rules' => [['type' => 'url', 'operator' => 'contains', 'value' => '/thanks']]],
    ];

    public function setUp(): void
    {
        parent::setUp();

        Fixture::createSuperUser();
        Fixture::createWebsite('2024-01-01 00:00:00');
    }

    public function testTheVisitsThatReachedTheLastStepAreReturnedWithTheActionTheyReachedItWith()
    {
        $this->visit(1, ['/cart', '/other', '/thanks', '/thanks']);
        $this->visit(2, ['/cart']);
        $this->visit(3, ['/thanks', '/cart']);

        $completions = (new FunnelCounter())->getCompletions($this->getLogAggregator(), self::STEPS);

        $this->assertCount(1, $completions);
        [$time, $idAction] = reset($completions);
        // The first time the visit reached the step.
        $this->assertSame(strtotime(self::DAY . ' 10:02:00 UTC'), $time);
        $this->assertSame(
            'example.org/thanks',
            Db::fetchOne(
                'SELECT a.name FROM ' . Common::prefixTable('log_link_visit_action') . ' l JOIN ' . Common::prefixTable('log_action') . ' a ON a.idaction = l.idaction_url WHERE l.idlink_va = ?',
                [$idAction]
            )
        );
    }

    public function testAFunnelNoVisitEntersHasNoCompletions()
    {
        $this->visit(1, ['/thanks']);

        $this->assertSame([], (new FunnelCounter())->getCompletions($this->getLogAggregator(), self::STEPS));
    }

    private function getLogAggregator(): LogAggregator
    {
        return new LogAggregator(new Parameters(new Site(1), PeriodFactory::build('day', self::DAY), new Segment('', [1])));
    }

    /**
     * @param string[] $pages
     */
    private function visit(int $number, array $pages): void
    {
        $tracker = Fixture::getTracker(1, self::DAY . ' 10:00:00', true, true);
        $tracker->setTokenAuth(Fixture::getTokenAuth());
        $tracker->setNewVisitorId();
        // Matomo takes requests from one address for one visit.
        $tracker->setIp('10.0.0.' . $number);
        foreach ($pages as $minute => $page) {
            $tracker->setForceVisitDateTime(sprintf('%s 10:%02d:00', self::DAY, $minute));
            $tracker->setUrl('http://example.org' . $page);
            Fixture::checkResponse($tracker->doTrackPageView('Page'));
        }
    }
}
