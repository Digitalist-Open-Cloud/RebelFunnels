<?php

namespace Piwik\Plugins\RebelFunnels\tests\Integration\Commands;

use Piwik\Archive;
use Piwik\Cache;
use Piwik\Common;
use Piwik\Db;
use Piwik\Plugins\RebelFunnels\API;
use Piwik\Plugins\RebelFunnels\Model;
use Piwik\Plugins\RebelFunnels\tests\Fixtures\FunnelVisits;
use Piwik\Plugins\VisitsSummary\API as VisitsSummaryApi;
use Piwik\Tests\Framework\Fixture;
use Piwik\Tests\Framework\Mock\FakeAccess;
use Piwik\Tests\Framework\TestCase\ConsoleCommandTestCase;

/**
 * @group RebelFunnels
 * @group Plugins
 */
class ArchiveTest extends ConsoleCommandTestCase
{
    private const STEPS = [
        ['name' => 'Cart', 'rules' => [['type' => 'url', 'operator' => 'contains', 'value' => '/cart']]],
        ['name' => 'Shipping', 'rules' => [['type' => 'url', 'operator' => 'contains', 'value' => '/shipping']]],
    ];

    public function setUp(): void
    {
        parent::setUp();

        FakeAccess::clearAccess(true);
        Fixture::createWebsite('2024-01-01 00:00:00');
        Fixture::createWebsite('2024-01-01 00:00:00');
    }

    public function provideContainerConfig()
    {
        return ['Piwik\Access' => new FakeAccess()];
    }

    public function testListShowsFunnelsWithTheirStepsAndRecordName()
    {
        $idFunnel = $this->addFunnel(1);

        $result = $this->applicationTester->run(['command' => 'rebelfunnels:list']);

        $this->assertSame(0, $result, $this->getCommandDisplayOutputErrorMessage());
        $this->assertStringContainsString('Cart > Shipping', $this->applicationTester->getDisplay());
        $this->assertStringContainsString("RebelFunnels_{$idFunnel}_1", $this->applicationTester->getDisplay());
    }

    public function testWithOnlyAWebsiteItsFunnelsAreArchived()
    {
        $first = $this->addFunnel(1);
        $second = $this->addFunnel(2);

        $result = $this->applicationTester->run(['command' => 'rebelfunnels:archive', '--idsite' => '2']);

        $this->assertSame(0, $result, $this->getCommandDisplayOutputErrorMessage());
        $this->assertStringContainsString("RebelFunnels_{$second}_1", $this->applicationTester->getDisplay());
        $this->assertStringNotContainsString("RebelFunnels_{$first}_1", $this->applicationTester->getDisplay());
    }

    public function testAnUnknownFunnelIsRejected()
    {
        $result = $this->applicationTester->run(['command' => 'rebelfunnels:archive', '--idfunnel' => '999999']);

        $this->assertNotSame(0, $result);
        $this->assertStringContainsString("no funnel with ID '999999'", $this->applicationTester->getDisplay());
    }

    public function testAWeekThatIsArchivedAgainKeepsTheDaysArchivedForTheFunnelAlone()
    {
        FunnelVisits::track(1);
        // The days are archived before the funnel exists, so only its own archives have its data.
        VisitsSummaryApi::getInstance()->get(1, 'week', FunnelVisits::DAY);
        $idFunnel = $this->addFunnel(1);
        $result = $this->applicationTester->run([
            'command' => 'rebelfunnels:archive',
            '--idfunnel' => (string) $idFunnel,
            '--rearchive' => true,
            '--date' => FunnelVisits::DAY . ',' . FunnelVisits::NEXT_DAY,
        ]);
        $this->assertSame(0, $result, $this->getCommandDisplayOutputErrorMessage());

        // The week is archived again as a whole, like core does while a week is going on.
        Db::query('DELETE FROM ' . Common::prefixTable('archive_numeric_2024_03') . ' WHERE period = 2');
        Archive::clearStaticCache();
        Cache::getTransientCache()->flushAll();
        VisitsSummaryApi::getInstance()->get(1, 'week', FunnelVisits::DAY);

        $steps = API::getInstance()->getSteps(1, 'week', FunnelVisits::DAY, $idFunnel);
        $this->assertSame([5, 2], $steps->getColumn('nb_visits'));
    }

    private function addFunnel(int $idSite): int
    {
        return (new Model())->addFunnel(['idsite' => $idSite, 'name' => 'Checkout', 'description' => '', 'steps' => self::STEPS, 'segment_filter' => '']);
    }
}
