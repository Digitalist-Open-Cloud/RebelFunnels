<?php

namespace Piwik\Plugins\RebelFunnels\tests\Fixtures;

use Piwik\Tests\Framework\Fixture;

/**
 * Visits around a checkout of the pages /cart, /shipping, /payment and /thanks.
 *
 * On DAY:
 * - visit 1 (1920x1080): /cart, /other, /shipping after 2 minutes, /payment after 3 more,
 *   /thanks after 1 more, then a download
 * - visit 2: /cart, /shipping after 1 minute, and a minute later the goal $idManualGoal if given
 * - visit 3: /shipping, /payment
 * - visit 4: /shipping, /cart
 * - visit 5: /cart, then the event Checkout / pay / card
 * On NEXT_DAY:
 * - visit 6: /cart
 */
class FunnelVisits
{
    public const DAY = '2024-03-05';
    public const NEXT_DAY = '2024-03-06';

    public static function track(int $idSite, int $idManualGoal = 0): void
    {
        self::visit($idSite, 1, self::DAY . ' 10:00:00', [
            0 => '/cart', 60 => '/other', 120 => '/shipping', 300 => '/payment', 360 => '/thanks',
            420 => ['download', 'http://www.example.org/files/report.pdf'],
        ], [1920, 1080]);
        self::visit($idSite, 2, self::DAY . ' 11:00:00', [0 => '/cart', 60 => '/shipping'] + ($idManualGoal ? [120 => ['goal', $idManualGoal]] : []));
        self::visit($idSite, 3, self::DAY . ' 12:00:00', [0 => '/shipping', 60 => '/payment']);
        self::visit($idSite, 4, self::DAY . ' 13:00:00', [0 => '/shipping', 60 => '/cart']);
        self::visit($idSite, 5, self::DAY . ' 14:00:00', [0 => '/cart', 60 => ['event', 'Checkout', 'pay', 'card']]);
        self::visit($idSite, 6, self::NEXT_DAY . ' 12:00:00', [0 => '/cart']);
    }

    /**
     * @param array<int, string|array> $actions seconds after the start => a page path, or
     *                                          ['download', url], ['goal', ID] or
     *                                          ['event', category, action, name]
     */
    private static function visit(int $idSite, int $number, string $start, array $actions, array $resolution = [1024, 768]): void
    {
        $tracker = Fixture::getTracker($idSite, $start, true, true);
        $tracker->setTokenAuth(Fixture::getTokenAuth());
        $tracker->setNewVisitorId();
        $tracker->setIp('10.0.0.' . $number);
        $tracker->setResolution($resolution[0], $resolution[1]);

        foreach ($actions as $seconds => $action) {
            $tracker->setForceVisitDateTime(date('Y-m-d H:i:s', strtotime($start . ' UTC') + $seconds));

            if (is_string($action)) {
                $tracker->setUrl('http://example.org' . $action);
                Fixture::checkResponse($tracker->doTrackPageView(ucfirst(trim($action, '/'))));
            } elseif ($action[0] === 'download') {
                Fixture::checkResponse($tracker->doTrackAction($action[1], 'download'));
            } elseif ($action[0] === 'goal') {
                Fixture::checkResponse($tracker->doTrackGoal($action[1]));
            } else {
                Fixture::checkResponse($tracker->doTrackEvent($action[1], $action[2], $action[3]));
            }
        }
    }
}
