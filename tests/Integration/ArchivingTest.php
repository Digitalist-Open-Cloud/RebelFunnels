<?php

namespace Piwik\Plugins\RebelFunnels\tests\Integration;

use Piwik\API\Request;
use Piwik\DataTable;
use Piwik\Date;
use Piwik\Plugins\Goals\API as GoalsApi;
use Piwik\Plugins\RebelFunnels\API;
use Piwik\Plugins\RebelFunnels\tests\Fixtures\FunnelVisits;
use Piwik\Tests\Framework\Fixture;
use Piwik\Tests\Framework\TestCase\IntegrationTestCase;

/**
 * @group RebelFunnels
 * @group Plugins
 */
class ArchivingTest extends IntegrationTestCase
{
    private const CHECKOUT = [
        ['name' => 'Cart', 'rules' => [['type' => 'url', 'operator' => 'contains', 'value' => '/cart']]],
        ['name' => 'Shipping', 'rules' => [['type' => 'url', 'operator' => 'contains', 'value' => '/shipping']]],
        ['name' => 'Payment', 'rules' => [['type' => 'url', 'operator' => 'contains', 'value' => '/payment']]],
        ['name' => 'Thanks', 'rules' => [['type' => 'url', 'operator' => 'contains', 'value' => '/thanks']]],
    ];

    private int $idSite;
    private int $idUrlGoal;
    private int $idManualGoal;
    private API $api;

    protected static function configureFixture($fixture)
    {
        parent::configureFixture($fixture);

        $fixture->createSuperUser = true;
    }

    public function setUp(): void
    {
        parent::setUp();

        Fixture::loadAllTranslations();
        $this->idSite = Fixture::createWebsite('2024-01-01 00:00:00');
        $_GET['idSite'] = $this->idSite;
        $this->api = API::getInstance();

        $this->idUrlGoal = GoalsApi::getInstance()->addGoal($this->idSite, 'Thanks page', 'url', '/thanks', 'contains', false, 12.5);
        $this->idManualGoal = GoalsApi::getInstance()->addGoal($this->idSite, 'Newsletter', 'manually', '', '');
        FunnelVisits::track($this->idSite, $this->idManualGoal);
    }

    public function tearDown(): void
    {
        unset($_GET['idSite']);
        Date::$now = null;
        parent::tearDown();
    }

    public function testAVisitReachesAStepOnlyAfterTheStepBefore()
    {
        $steps = $this->getSteps($this->api->addFunnel($this->idSite, 'Checkout', self::CHECKOUT));

        $this->assertSame(['Cart', 'Shipping', 'Payment', 'Thanks'], $steps->getColumn('label'));
        $this->assertSame([4, 2, 1, 1], $steps->getColumn('nb_visits'));
        $this->assertSame([2, 1, 1, false], $steps->getColumn('funnel_proceeded'));
        $this->assertSame([2, 1, 0, false], $steps->getColumn('funnel_exits'));
    }

    public function testRatesAndTheTimeFromTheStepBeforeAreCalculated()
    {
        $idFunnel = $this->api->addFunnel($this->idSite, 'Checkout', self::CHECKOUT);

        $steps = Request::processRequest('RebelFunnels.getSteps', [
            'idSite' => $this->idSite,
            'idFunnel' => $idFunnel,
            'period' => 'day',
            'date' => FunnelVisits::DAY,
            'format_metrics' => '0',
        ]);

        $this->assertSame([1.0, 0.5, 0.25, 0.25], array_map('floatval', $steps->getColumn('funnel_step_rate')));
        $this->assertSame([0.5, 0.5, 0.0], array_slice(array_map('floatval', $steps->getColumn('funnel_dropoff_rate')), 0, 3));
        // Visit 1 took 2 minutes from the cart to shipping and visit 2 took 1.
        $this->assertSame([0, 90, 180, 60], array_map('intval', $steps->getColumn('funnel_avg_step_time')));
        $this->assertSame([false, false, false, false], $steps->getColumn('sum_time'), 'only there to calculate the average');
    }

    public function testAWeekIsTheSumOfItsDays()
    {
        $steps = $this->getSteps($this->api->addFunnel($this->idSite, 'Checkout', self::CHECKOUT), FunnelVisits::DAY, 'week');

        $this->assertSame([5, 2, 1, 1], $steps->getColumn('nb_visits'));
        $this->assertSame([0, 180, 180, 60], $steps->getColumn('sum_time'));
    }

    public function testTheSegmentAndTheFilterOfTheFunnelRestrictTheVisits()
    {
        $idFunnel = $this->api->addFunnel($this->idSite, 'Checkout', self::CHECKOUT);
        $idFiltered = $this->api->addFunnel($this->idSite, 'Large screens', self::CHECKOUT, '', 'resolution==1920x1080');

        $this->assertSame([3, 1, 0, 0], $this->getSteps($idFunnel, FunnelVisits::DAY, 'day', 'resolution==1024x768')->getColumn('nb_visits'));
        $this->assertSame([1, 1, 1, 1], $this->getSteps($idFiltered)->getColumn('nb_visits'));
        $this->assertSame([0, 0, 0, 0], $this->getSteps($idFiltered, FunnelVisits::DAY, 'day', 'resolution==1024x768')->getColumn('nb_visits'));
    }

    public function testAStepIsReachedByAnyOfItsRulesAndByOtherActionsThanPages()
    {
        $steps = [
            self::CHECKOUT[0],
            ['name' => 'Next', 'rules' => [
                ['type' => 'url', 'operator' => 'ends_with', 'value' => 'https://example.org/shipping'],
                ['type' => 'event_category', 'operator' => 'equals', 'value' => 'checkout'],
            ]],
            ['name' => 'Report', 'rules' => [['type' => 'download', 'operator' => 'equals', 'value' => 'https://example.org/files/report.pdf']]],
        ];

        $steps = $this->getSteps($this->api->addFunnel($this->idSite, 'Mixed', $steps));

        $this->assertSame([4, 3, 1], $steps->getColumn('nb_visits'));
    }

    public function testAStepCanHaveToComeDirectlyAfterTheStepBefore()
    {
        $steps = array_slice(self::CHECKOUT, 0, 2);
        $steps[1]['direct'] = 1;

        // Visit 1 looked at another page between the cart and shipping.
        $this->assertSame([4, 1], $this->getSteps($this->api->addFunnel($this->idSite, 'Direct', $steps))->getColumn('nb_visits'));
    }

    public function testAStepTellsWhatTheVisitsThatLeftThereDidNext()
    {
        $steps = array_slice(self::CHECKOUT, 0, 2);
        $steps[1]['direct'] = 1;
        $idFunnel = $this->api->addFunnel($this->idSite, 'Direct', $steps);

        $cart = $this->getSteps($idFunnel, FunnelVisits::DAY, 'week')->getFirstRow();
        $next = $this->api->getSteps($this->idSite, 'week', FunnelVisits::DAY, $idFunnel, false, $cart->getIdSubDataTable());

        // Visit 6, on the next day, also ended in the cart.
        $this->assertEquals(
            ['Left the website' => 2, 'example.org/other' => 1, 'Event: pay' => 1],
            array_combine($next->getColumn('label'), $next->getColumn('nb_visits'))
        );
        $this->assertSame('Left the website', $next->getFirstRow()->getColumn('label'));
    }

    /**
     * @dataProvider getFunnelsWithGoals
     */
    public function testAStepCanBeReachedByConvertingAGoal(array $steps, array $expectedVisits)
    {
        $goals = ['url' => $this->idUrlGoal, 'manual' => $this->idManualGoal];
        foreach ($steps as $index => $step) {
            $rule = is_array($step)
                ? ['type' => 'goal', 'value' => (string) $goals[$step[0]]]
                : ['type' => 'url', 'operator' => 'contains', 'value' => $step];
            $steps[$index] = ['name' => "Step $index", 'rules' => [$rule]];
        }

        $this->assertSame($expectedVisits, $this->getSteps($this->api->addFunnel($this->idSite, 'Goals', $steps))->getColumn('nb_visits'));
    }

    public function getFunnelsWithGoals(): array
    {
        return [
            'goal after a page' => [['/cart', ['url']], [4, 1]],
            'goal that the page of the step before converts' => [['/thanks', ['url']], [1, 1]],
            'the page that converted the goal does not come after it' => [[['url'], '/thanks'], [1, 0]],
            'goal triggered from the tracking code' => [['/shipping', ['manual']], [4, 1]],
            'page before such a goal does not come after it' => [[['manual'], '/shipping'], [1, 0]],
            'goal after a goal' => [['/cart', ['manual'], ['url']], [4, 1, 0]],
        ];
    }

    public function testAStepReachedByAGoalHasTheRevenueOfItsConversions()
    {
        $steps = [
            self::CHECKOUT[0],
            ['name' => 'Converted', 'rules' => [['type' => 'goal', 'value' => (string) $this->idUrlGoal]]],
        ];
        $idFunnel = $this->api->addFunnel($this->idSite, 'To the goal', $steps);
        $request = ['idSite' => $this->idSite, 'idFunnel' => $idFunnel, 'period' => 'week', 'date' => FunnelVisits::DAY, 'format_metrics' => '0'];

        $stepRows = Request::processRequest('RebelFunnels.getSteps', $request);
        $summary = Request::processRequest('RebelFunnels.getSummary', $request);

        $this->assertSame([0.0, 12.5], array_map('floatval', $stepRows->getColumn('funnel_revenue')));
        $this->assertSame(12.5, (float) $summary->getFirstRow()->getColumn('funnel_revenue'));
        $this->assertSame([false, false], $stepRows->getColumn('revenue'), 'only there to calculate the revenue');
    }

    public function testAPausedFunnelGetsNoData()
    {
        $idFunnel = $this->api->addFunnel($this->idSite, 'Checkout', self::CHECKOUT);
        $this->api->pauseFunnel($this->idSite, $idFunnel);

        $this->assertSame([0, 0, 0, 0], $this->getSteps($idFunnel)->getColumn('nb_visits'));
    }

    public function testSummaryHasEntriesConversionsAndTheirRate()
    {
        $idFunnel = $this->api->addFunnel($this->idSite, 'Checkout', self::CHECKOUT);

        $summary = Request::processRequest('RebelFunnels.getSummary', [
            'idSite' => $this->idSite,
            'idFunnel' => $idFunnel,
            'period' => 'day',
            'date' => FunnelVisits::DAY,
            'format_metrics' => '0',
        ]);

        $this->assertEquals(
            ['funnel_entries' => 4, 'funnel_conversions' => 1, 'funnel_conversion_rate' => 0.25, 'funnel_revenue' => 0],
            $summary->getFirstRow()->getColumns()
        );
    }

    public function testPreviewCountsTheLastDaysFromTheRawData()
    {
        Date::$now = strtotime(FunnelVisits::NEXT_DAY . ' 18:00:00');

        $steps = $this->api->previewFunnel($this->idSite, array_slice(self::CHECKOUT, 0, 2));

        $this->assertSame([
            ['name' => 'Cart', 'nb_visits' => 5, 'funnel_proceeded' => 2, 'funnel_exits' => 3, 'funnel_avg_step_time' => 0, 'funnel_revenue' => 0.0],
            ['name' => 'Shipping', 'nb_visits' => 2, 'funnel_proceeded' => null, 'funnel_exits' => null, 'funnel_avg_step_time' => 90, 'funnel_revenue' => 0.0],
        ], $steps);
    }

    private function getSteps(int $idFunnel, string $date = FunnelVisits::DAY, string $period = 'day', string $segment = ''): DataTable
    {
        return $this->api->getSteps($this->idSite, $period, $date, $idFunnel, $segment);
    }
}
