<?php

namespace Piwik\Plugins\RebelFunnels\tests\Integration;

use Piwik\Container\StaticContainer;
use Piwik\CronArchive\ReArchiveList;
use Piwik\NoAccessException;
use Piwik\Plugins\Goals\API as GoalsApi;
use Piwik\Plugins\RebelFunnels\API;
use Piwik\Plugins\RebelFunnels\Archiving\ObsoleteRecords;
use Piwik\Plugins\SitesManager\API as SitesManagerApi;
use Piwik\Tests\Framework\Fixture;
use Piwik\Tests\Framework\Mock\FakeAccess;
use Piwik\Tests\Framework\TestCase\IntegrationTestCase;

/**
 * @group RebelFunnels
 * @group Plugins
 */
class ApiTest extends IntegrationTestCase
{
    private const STEPS = [
        ['name' => ' Cart ', 'rules' => [['type' => 'url', 'operator' => 'contains', 'value' => ' /cart ']]],
        ['name' => 'Thanks', 'rules' => [['type' => 'title', 'operator' => 'equals', 'value' => 'Thank you']]],
    ];

    private int $idSite;
    private int $idOtherSite;
    private API $api;

    public function setUp(): void
    {
        parent::setUp();

        Fixture::loadAllTranslations();
        FakeAccess::clearAccess(true);

        $this->idSite = Fixture::createWebsite('2024-01-01 00:00:00');
        $this->idOtherSite = Fixture::createWebsite('2024-01-01 00:00:00');
        $this->api = API::getInstance();
    }

    public function provideContainerConfig()
    {
        return ['Piwik\Access' => new FakeAccess()];
    }

    public function testAddFunnelStoresTheDefinition()
    {
        $idFunnel = $this->api->addFunnel($this->idSite, ' Checkout ', self::STEPS, 'From cart to thanks', 'visitCount>1');

        $funnel = $this->api->getFunnel($this->idSite, $idFunnel);

        $this->assertSame('Checkout', $funnel['name']);
        $this->assertSame('From cart to thanks', $funnel['description']);
        $this->assertSame('visitCount>1', $funnel['segment_filter']);
        $this->assertSame($this->idSite, $funnel['idsite']);
        $this->assertSame([
            ['name' => 'Cart', 'rules' => [['type' => 'url', 'operator' => 'contains', 'value' => '/cart']], 'direct' => false],
            self::STEPS[1] + ['direct' => false],
        ], $funnel['steps']);
    }

    /**
     * @dataProvider getInvalidDefinitions
     */
    public function testAddFunnelRejectsInvalidDefinitions(string $name, array $steps, string $segmentFilter, string $expectedMessage)
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage($expectedMessage);

        $this->api->addFunnel($this->idSite, $name, $steps, '', $segmentFilter);
    }

    public function getInvalidDefinitions(): array
    {
        $rule = self::STEPS[0]['rules'][0];

        return [
            'no name' => [' ', self::STEPS, '', 'needs a name'],
            'one step' => ['Funnel', [self::STEPS[0]], '', 'at least 2'],
            'too many steps' => ['Funnel', array_fill(0, 11, self::STEPS[0]), '', 'at most 10 steps'],
            'step without name' => ['Funnel', [self::STEPS[0], ['name' => '', 'rules' => [$rule]]], '', 'Step 2 needs a name'],
            'step without rules' => ['Funnel', [self::STEPS[0], ['name' => 'Two', 'rules' => []]], '', 'Step 2 needs at least one rule'],
            'unknown type' => ['Funnel', [self::STEPS[0], ['name' => 'Two', 'rules' => [['type' => 'visit'] + $rule]]], '', 'unknown type'],
            'unknown operator' => ['Funnel', [self::STEPS[0], ['name' => 'Two', 'rules' => [['operator' => 'like'] + $rule]]], '', 'unknown operator'],
            'no value' => ['Funnel', [self::STEPS[0], ['name' => 'Two', 'rules' => [['value' => ' '] + $rule]]], '', 'needs a value'],
            'bad regex' => ['Funnel', [self::STEPS[0], ['name' => 'Two', 'rules' => [['operator' => 'regex', 'value' => '(a'] + $rule]]], '', 'regular expression'],
            'bad segment' => ['Funnel', self::STEPS, 'noSuchSegment==1', 'noSuchSegment'],
            'unknown goal' => ['Funnel', [self::STEPS[0], ['name' => 'Two', 'rules' => [['type' => 'goal', 'value' => '99']]]], '', 'Use one of: order, cart.'],
        ];
    }

    public function testANewFunnelIsScheduledForArchiving()
    {
        $idFunnel = $this->api->addFunnel($this->idSite, 'Checkout', self::STEPS);

        $this->assertSame(["RebelFunnels_{$idFunnel}_1"], $this->getScheduledRecords());
        $status = $this->api->getArchiveStatus($this->idSite)[0];
        $this->assertSame($idFunnel, $status['idfunnel']);
        $this->assertTrue($status['is_queued']);
        $this->assertNull($status['first_date']);
    }

    public function testChangingTheRulesBuildsTheDataAgain()
    {
        $idFunnel = $this->api->addFunnel($this->idSite, 'Checkout', self::STEPS);
        $steps = self::STEPS;
        $steps[1]['rules'][0]['value'] = 'Thanks';

        $this->api->updateFunnel($this->idSite, $idFunnel, 'Checkout', $steps);

        $this->assertSame(2, $this->api->getFunnel($this->idSite, $idFunnel)['revision']);
        $this->assertContains("RebelFunnels_{$idFunnel}_2", $this->getScheduledRecords());
        $this->assertSame(
            [['record' => "RebelFunnels_{$idFunnel}_1", 'idsite' => $this->idSite]],
            StaticContainer::get(ObsoleteRecords::class)->getAll()
        );
    }

    public function testChangingWhetherAStepHasToComeDirectlyBuildsTheDataAgain()
    {
        $idFunnel = $this->api->addFunnel($this->idSite, 'Checkout', self::STEPS);
        $steps = self::STEPS;
        // The first step has no step before it, so it cannot be direct.
        $steps[0]['direct'] = '1';
        $steps[1]['direct'] = '1';

        $this->api->updateFunnel($this->idSite, $idFunnel, 'Checkout', $steps);

        $funnel = $this->api->getFunnel($this->idSite, $idFunnel);
        $this->assertSame([false, true], array_column($funnel['steps'], 'direct'));
        $this->assertSame(2, $funnel['revision']);
    }

    public function testRenamingKeepsTheArchivedData()
    {
        $idFunnel = $this->api->addFunnel($this->idSite, 'Checkout', self::STEPS);
        $steps = self::STEPS;
        $steps[1]['name'] = 'Done';

        $this->api->updateFunnel($this->idSite, $idFunnel, 'Purchase', $steps, 'New words');

        $funnel = $this->api->getFunnel($this->idSite, $idFunnel);
        $this->assertSame(['Purchase', 'New words', 'Done', 1], [$funnel['name'], $funnel['description'], $funnel['steps'][1]['name'], $funnel['revision']]);
        $this->assertSame([], StaticContainer::get(ObsoleteRecords::class)->getAll());
    }

    public function testAFunnelBelongsToItsWebsiteUnlessItIsForAllWebsites()
    {
        $idFunnel = $this->api->addFunnel($this->idSite, 'Checkout', self::STEPS);
        $idForAll = $this->api->addFunnel($this->idSite, 'Everywhere', self::STEPS, '', '', true);

        $this->assertSame([$idForAll], array_column($this->api->getFunnels($this->idOtherSite), 'idfunnel'));

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('no funnel with ID');
        $this->api->getFunnel($this->idOtherSite, $idFunnel);
    }

    public function testAGoalOfTheWebsiteCanBeAStepUnlessTheFunnelIsForAllWebsites()
    {
        $idGoal = (string) GoalsApi::getInstance()->addGoal($this->idSite, 'Signup', 'url', '/signup', 'contains');
        $steps = [self::STEPS[0], ['name' => 'Signed up', 'rules' => [['type' => 'goal', 'value' => $idGoal, 'operator' => 'contains']]]];

        $idFunnel = $this->api->addFunnel($this->idSite, 'Signup', $steps);
        $this->assertSame(
            [['type' => 'goal', 'operator' => 'equals', 'value' => $idGoal]],
            $this->api->getFunnel($this->idSite, $idFunnel)['steps'][1]['rules']
        );

        $steps[1]['rules'][0]['value'] = 'order';
        $this->api->addFunnel($this->idSite, 'Orders everywhere', $steps, '', '', true);

        $steps[1]['rules'][0]['value'] = $idGoal;
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Use one of: order, cart.');
        $this->api->addFunnel($this->idSite, 'Signup everywhere', $steps, '', '', true);
    }

    public function testAPausedFunnelIsNotArchivedUntilItIsResumed()
    {
        $idFunnel = $this->api->addFunnel($this->idSite, 'Checkout', self::STEPS);

        $this->api->pauseFunnel($this->idSite, $idFunnel);
        $this->assertTrue($this->api->getFunnel($this->idSite, $idFunnel)['paused']);
        $this->assertSame([], $this->getScheduledRecords());

        // A paused funnel is not built again when it changes either.
        $steps = self::STEPS;
        $steps[1]['rules'][0]['value'] = 'Thanks';
        $this->api->updateFunnel($this->idSite, $idFunnel, 'Checkout', $steps);
        $this->assertSame([], $this->getScheduledRecords());

        $this->api->resumeFunnel($this->idSite, $idFunnel);
        $this->assertFalse($this->api->getFunnel($this->idSite, $idFunnel)['paused']);
        $this->assertSame(["RebelFunnels_{$idFunnel}_2"], $this->getScheduledRecords());
    }

    public function testAFunnelIsDuplicatedWithinItsWebsiteOrToAnother()
    {
        $idFunnel = $this->api->addFunnel($this->idSite, 'Checkout', self::STEPS, 'From cart to thanks', 'visitCount>1');

        $idCopy = $this->api->duplicateFunnel($this->idSite, $idFunnel);
        $idElsewhere = $this->api->duplicateFunnel($this->idSite, $idFunnel, $this->idOtherSite);

        $original = $this->api->getFunnel($this->idSite, $idFunnel);
        $copy = $this->api->getFunnel($this->idSite, $idCopy);
        $elsewhere = $this->api->getFunnel($this->idOtherSite, $idElsewhere);
        $this->assertSame('Checkout (copy)', $copy['name']);
        $this->assertSame('Checkout', $elsewhere['name']);
        $this->assertSame($this->idOtherSite, $elsewhere['idsite']);
        foreach ([$copy, $elsewhere] as $funnel) {
            $this->assertSame(
                [$original['description'], $original['steps'], $original['segment_filter']],
                [$funnel['description'], $funnel['steps'], $funnel['segment_filter']]
            );
        }
        $this->assertContains("RebelFunnels_{$idCopy}_1", $this->getScheduledRecords());
    }

    public function testAFunnelWithAGoalCannotBeDuplicatedToAWebsiteWithoutThatGoal()
    {
        $idGoal = (string) GoalsApi::getInstance()->addGoal($this->idSite, 'Signup', 'url', '/signup', 'contains');
        $steps = [self::STEPS[0], ['name' => 'Signed up', 'rules' => [['type' => 'goal', 'value' => $idGoal]]]];
        $idFunnel = $this->api->addFunnel($this->idSite, 'Signup', $steps);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Use one of: order, cart.');
        $this->api->duplicateFunnel($this->idSite, $idFunnel, $this->idOtherSite);
    }

    public function testDuplicatingNeedsWriteAccessToTheWebsiteTheCopyGoesTo()
    {
        $idFunnel = $this->api->addFunnel($this->idSite, 'Checkout', self::STEPS);
        FakeAccess::clearAccess(false, [], [$this->idSite], 'writer', [$this->idSite]);

        $this->assertGreaterThan($idFunnel, $this->api->duplicateFunnel($this->idSite, $idFunnel));

        $this->expectException(NoAccessException::class);
        $this->api->duplicateFunnel($this->idSite, $idFunnel, $this->idOtherSite);
    }

    public function testViewUserCanReadButNotChangeFunnels()
    {
        $idFunnel = $this->api->addFunnel($this->idSite, 'Checkout', self::STEPS);
        FakeAccess::clearAccess(false, [], [$this->idSite]);

        $this->assertCount(1, $this->api->getFunnels($this->idSite));

        $this->expectException(NoAccessException::class);
        $this->api->deleteFunnel($this->idSite, $idFunnel);
    }

    public function testWriteUserCannotChangeFunnelsForAllWebsites()
    {
        $idFunnel = $this->api->addFunnel($this->idSite, 'Everywhere', self::STEPS, '', '', true);
        FakeAccess::clearAccess(false, [], [], 'writer', [$this->idSite]);

        $this->expectException(NoAccessException::class);
        $this->api->updateFunnel($this->idSite, $idFunnel, 'Mine now', self::STEPS);
    }

    public function testFunnelsAreListedInTheOrderThatWasSet()
    {
        $first = $this->api->addFunnel($this->idSite, 'First', self::STEPS);
        $second = $this->api->addFunnel($this->idSite, 'Second', self::STEPS);

        $this->api->setFunnelOrder($this->idSite, [$second, $first]);

        $this->assertSame(['Second', 'First'], array_column($this->api->getFunnels($this->idSite), 'name'));
    }

    public function testDeletingAFunnelOrItsWebsiteDropsItsArchivedData()
    {
        $idFunnel = $this->api->addFunnel($this->idSite, 'Checkout', self::STEPS);
        $idOfOtherSite = $this->api->addFunnel($this->idOtherSite, 'Other', self::STEPS);

        $this->api->deleteFunnel($this->idSite, $idFunnel);
        SitesManagerApi::getInstance()->deleteSite($this->idOtherSite);

        $this->assertSame([], $this->api->getFunnels($this->idSite));
        $this->assertSame(
            ["RebelFunnels_{$idFunnel}_1", "RebelFunnels_{$idOfOtherSite}_1"],
            array_column(StaticContainer::get(ObsoleteRecords::class)->getAll(), 'record')
        );
    }

    public function testTestUrlTellsWhichStepsAUrlMatches()
    {
        $this->assertSame([true, false], $this->api->testUrl($this->idSite, self::STEPS, 'https://example.org/cart?a=1&amp;b=2'));
    }

    /**
     * @return string[]
     */
    private function getScheduledRecords(): array
    {
        return array_map(
            fn (string $entry) => json_decode($entry, true)['report'],
            StaticContainer::get(ReArchiveList::class)->getAll()
        );
    }
}
