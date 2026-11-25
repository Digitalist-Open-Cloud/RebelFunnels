<?php

namespace Piwik\Plugins\RebelFunnels\tests\Integration;

use Piwik\Common;
use Piwik\Db;
use Piwik\Plugin\Manager;
use Piwik\Plugins\RebelFunnels\API;
use Piwik\Tests\Framework\Fixture;
use Piwik\Tests\Framework\TestCase\IntegrationTestCase;

/**
 * Steps that are matched by a form the plugin RebelMyForms stored.
 *
 * @group RebelFunnels
 * @group Plugins
 */
class FormStepsTest extends IntegrationTestCase
{
    private const DAY = '2024-03-05';

    private const PAGE = ['name' => 'Contact page', 'rules' => [['type' => 'url', 'operator' => 'contains', 'value' => '/contact']]];
    private const THANKS = ['name' => 'Thanks', 'rules' => [['type' => 'url', 'operator' => 'contains', 'value' => '/thanks']]];

    private API $api;

    protected static function configureFixture($fixture)
    {
        parent::configureFixture($fixture);

        $fixture->createSuperUser = true;
        $fixture->extraPluginsToLoad = array_values(array_filter(['RebelMyForms'], function (string $plugin): bool {
            return is_dir(PIWIK_INCLUDE_PATH . '/plugins/' . $plugin);
        }));
    }

    public function setUp(): void
    {
        parent::setUp();

        if (!Manager::getInstance()->isPluginActivated('RebelMyForms')) {
            $this->markTestSkipped('The plugin RebelMyForms is not installed.');
        }

        Fixture::loadAllTranslations();
        Fixture::createWebsite('2024-01-01 00:00:00');
        $_GET['idSite'] = 1;
        $this->api = API::getInstance();

        // Each visit opens the contact page at 10:00 and has the form "contact" on it.
        // 1: starts the form after one minute, submits it two minutes later, and gets to the thanks page.
        $this->visit(1, [0 => '/contact', 300 => '/thanks'], ['outcome' => 2, 'time_to_start' => 60000, 'time_spent' => 120000]);
        // 2: starts the form and leaves.
        $this->visit(2, [0 => '/contact'], ['outcome' => 1, 'time_to_start' => 30000, 'time_spent' => 5000]);
        // 3: only sees the form.
        $this->visit(3, [0 => '/contact'], ['outcome' => 0]);
        // 4: is on the thanks page first, and submits the form four minutes later.
        $this->visit(4, [0 => '/thanks', 60 => '/contact'], ['outcome' => 3, 'time_to_start' => 100000, 'time_spent' => 140000]);
    }

    public function tearDown(): void
    {
        unset($_GET['idSite']);
        parent::tearDown();
    }

    public function testAStepCanBeReachedByStartingOrSubmittingAForm()
    {
        $this->assertSame([4, 3, 2, 1], $this->visitsOf([self::PAGE, $this->form('form_start'), $this->form('form_submit'), $this->form('form_success')]));
    }

    /**
     * @dataProvider getFunnels
     */
    public function testAFormIsMatchedByItsNameAndCountsFromTheTimeItWasSubmitted(array $steps, array $expectedVisits)
    {
        $this->assertSame($expectedVisits, $this->visitsOf($steps));
    }

    public function getFunnels(): array
    {
        $form = function (string $type, string $operator = 'equals', string $value = 'contact'): array {
            return ['name' => $type, 'rules' => [['type' => $type, 'operator' => $operator, 'value' => $value]]];
        };

        return [
            'another name' => [[self::PAGE, $form('form_start', 'equals', 'newsletter')], [4, 0]],
            'start of the name' => [[self::PAGE, $form('form_start', 'starts_with', 'con')], [4, 3]],
            'regular expression' => [[self::PAGE, $form('form_start', 'regex', '^cont?act$')], [4, 3]],
            // Visit 1 submitted at 10:03 and saw the thanks page at 10:05. Visit 4 saw it before.
            'page after the form was submitted' => [[$form('form_submit'), self::THANKS], [2, 1]],
            'form submitted after a page' => [[self::THANKS, $form('form_submit')], [2, 1]],
        ];
    }

    public function testTheTimeFromTheStepBeforeToAFormIsCounted()
    {
        $idFunnel = $this->api->addFunnel(1, 'Time', [self::PAGE, $this->form('form_submit')]);

        $submitted = $this->api->getSteps(1, 'day', self::DAY, $idFunnel)->getLastRow();

        // Visit 1 three minutes after the page, visit 4 four minutes after it.
        $this->assertEquals(180 + 240, $submitted->getColumn('sum_time'));
    }

    public function testAFormStepCanNotBeRequiredToComeDirectlyAfterTheStepBefore()
    {
        $this->expectExceptionMessage('Step 2 is matched by a form');

        $this->api->addFunnel(1, 'Direct', [self::PAGE, $this->form('form_submit') + ['direct' => 1]]);
    }

    private function form(string $type, string $operator = 'equals', string $value = 'contact'): array
    {
        return ['name' => $type, 'rules' => [['type' => $type, 'operator' => $operator, 'value' => $value]]];
    }

    private function visitsOf(array $steps): array
    {
        $idFunnel = $this->api->addFunnel(1, 'Funnel', $steps);

        return $this->api->getSteps(1, 'day', self::DAY, $idFunnel)->getColumn('nb_visits');
    }

    /**
     * @param array<int, string> $pages seconds after 10:00 => a page path
     * @param array<string, int> $form what the visit did with the form on the contact page
     */
    private function visit(int $number, array $pages, array $form): void
    {
        $tracker = Fixture::getTracker(1, self::DAY . ' 10:00:00', true, true);
        $tracker->setTokenAuth(Fixture::getTokenAuth());
        $tracker->setNewVisitorId();
        $tracker->setIp('10.0.0.' . $number);

        foreach ($pages as $seconds => $path) {
            $time = date('Y-m-d H:i:s', strtotime(self::DAY . ' 10:00:00 UTC') + $seconds);
            $tracker->setForceVisitDateTime($time);
            $tracker->setUrl('http://example.org' . $path);
            Fixture::checkResponse($tracker->doTrackPageView($path));

            if ($path === '/contact') {
                $idVisit = Db::fetchOne('SELECT MAX(idvisit) FROM ' . Common::prefixTable('log_visit'));
                Db::query(
                    'INSERT INTO ' . Common::prefixTable('rebelmyforms_log') . ' (idsite, idvisit, idvisitor, server_time, idpageview, form_name,
                        started, submitted, outcome, time_to_start, time_spent, fields) VALUES (1, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
                    [
                        $idVisit, 'visitor' . $number, $time, 'page' . $number, 'contact',
                        (int) ($form['outcome'] >= 1), (int) ($form['outcome'] >= 2), $form['outcome'],
                        $form['time_to_start'] ?? 0, $form['time_spent'] ?? 0, '[]',
                    ]
                );
            }
        }
    }
}
