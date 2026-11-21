<?php

namespace Piwik\Plugins\RebelFunnels\Archiving;

use Piwik\Common;
use Piwik\DataAccess\LogAggregator;
use Piwik\Plugins\RebelFunnels\Matching\Rules;

/**
 * Counts from the raw log how many visits reach each step of a funnel, and what the visits
 * that left at a step did next. A visit reaches a step with the first action that matches it
 * after the action it reached the step before with. A step marked "direct" has to be matched
 * by the very next action of the visit. A step can also be reached by converting a goal.
 */
class FunnelCounter
{
    // Labels of what a visit did after the last step it reached, besides "<action type>:<name>".
    public const NEXT_EXIT = 'exit';
    public const NEXT_OTHER = 'other';

    private const VISITS_PER_QUERY = 1000;

    /**
     * @param array[] $steps
     * @return array<int, array{nb_visits: int, sum_time: int, revenue: float, next: array<string, int>}>
     *         per step, the visits that reached it, the seconds they took from the step before,
     *         the revenue of the conversions that reached it and, by label, how many visits
     *         that did not reach the next step did what next
     */
    public function count(LogAggregator $logAggregator, array $steps): array
    {
        $counts = array_fill(0, count($steps), ['nb_visits' => 0, 'sum_time' => 0, 'revenue' => 0.0, 'next' => []]);

        // Visit ID => when the visit reached the previous step: the time, and the ID of the
        // action. The ID is 0 for a conversion that no action triggered.
        $reached = [];

        foreach ($steps as $index => $step) {
            $previous = $reached;
            $reached = [];
            // Visit ID => the revenue of the conversion the visit reached the step with.
            $revenues = [];
            $onlyNext = $index > 0 && !empty($step['direct']) ? $this->getNextActions($logAggregator, $previous) : null;

            // ponytail: steps after the first read every match of the day. Join a temporary
            // table of the visits still in the funnel if that gets slow.
            foreach ($this->query($logAggregator, $step['rules'], $index === 0) as $isConversion => $cursor) {
                while ($row = $cursor->fetch()) {
                    $idVisit = $row['idvisit'];
                    $moment = [(int) $row['time'], (int) $row['idlink_va']];

                    if ($index > 0) {
                        if (!isset($previous[$idVisit]) || !$this->isAfter($moment, $previous[$idVisit], (bool) $isConversion)) {
                            continue;
                        }
                        if ($onlyNext !== null && $moment[1] !== ($onlyNext[$idVisit][0] ?? null)) {
                            continue;
                        }
                    }
                    if (!isset($reached[$idVisit]) || $moment < $reached[$idVisit]) {
                        $reached[$idVisit] = $moment;
                        $revenues[$idVisit] = (float) ($row['revenue'] ?? 0);
                    }
                }
                $cursor->closeCursor();
            }

            $counts[$index]['nb_visits'] = count($reached);
            $counts[$index]['revenue'] = round(array_sum($revenues), 2);
            if ($index > 0) {
                foreach ($reached as $idVisit => [$time]) {
                    $counts[$index]['sum_time'] += max(0, $time - $previous[$idVisit][0]);
                }

                $left = array_diff_key($previous, $reached);
                $counts[$index - 1]['next'] = $this->countNextActions($left, $onlyNext ?? $this->getNextActions($logAggregator, $left));
            }

            if (empty($reached)) {
                break;
            }
        }

        return $this->labelNextActions($logAggregator, $counts);
    }

    /**
     * Whether something happened after a visit reached the previous step. An action has to be a
     * later action. A conversion can also be triggered by the action that reached that step.
     * Without two actions to compare, as with a goal triggered from the tracking code, time
     * decides, and the same second counts as after.
     *
     * @param array{0: int, 1: int} $moment time and action ID
     * @param array{0: int, 1: int} $previous
     */
    private function isAfter(array $moment, array $previous, bool $isConversion): bool
    {
        if ($moment[1] > 0 && $previous[1] > 0) {
            return $isConversion ? $moment[1] >= $previous[1] : $moment[1] > $previous[1];
        }

        return $moment[0] >= $previous[0];
    }

    /**
     * @return \Zend_Db_Statement[] what matches the rules in the period, each row with idvisit,
     *         idlink_va and time: the actions under the key 0 and the conversions under 1.
     *         With $firstPerVisit only the first action of each visit.
     */
    private function query(LogAggregator $logAggregator, array $rules, bool $firstPerVisit): array
    {
        $cursors = [];

        $actionRules = Rules::getActionRules($rules);
        if ($actionRules) {
            $select = 'log_link_visit_action.idvisit AS idvisit, log_link_visit_action.idlink_va AS idlink_va, '
                . 'UNIX_TIMESTAMP(log_link_visit_action.server_time) AS time';
            if ($firstPerVisit) {
                $select = 'log_link_visit_action.idvisit AS idvisit, MIN(log_link_visit_action.idlink_va) AS idlink_va, '
                    . 'UNIX_TIMESTAMP(MIN(log_link_visit_action.server_time)) AS time';
            }

            $query = $logAggregator->generateQuery(
                $select,
                array_merge(['log_link_visit_action'], Rules::getJoins($actionRules)),
                $logAggregator->getWhereStatement('log_link_visit_action', 'server_time') . ' AND ' . Rules::getCondition($actionRules),
                $firstPerVisit ? 'log_link_visit_action.idvisit' : false,
                false
            );
            $cursors[0] = $logAggregator->getDb()->query($query['sql'], $query['bind']);
        }

        $idGoals = Rules::getGoalIds($rules);
        if ($idGoals) {
            $query = $logAggregator->generateQuery(
                'log_conversion.idvisit AS idvisit, log_conversion.idlink_va AS idlink_va, '
                . 'UNIX_TIMESTAMP(log_conversion.server_time) AS time, log_conversion.revenue AS revenue',
                ['log_conversion'],
                $logAggregator->getWhereStatement('log_conversion', 'server_time') . ' AND log_conversion.idgoal IN (' . implode(',', array_map('intval', $idGoals)) . ')',
                false,
                false
            );
            $cursors[1] = $logAggregator->getDb()->query($query['sql'], $query['bind']);
        }

        return $cursors;
    }

    /**
     * @param array<int|string, array{0: int, 1: int}> $after visit ID => a time and the ID of
     *        one of the visit's actions, or 0
     * @return array<int|string, array{0: int, 1: int}> visit ID => the ID of the action that came
     *         next in the visit and the ID of what it was: the event action of an event,
     *         otherwise the URL or else the name. Visits without a next action are left out.
     */
    private function getNextActions(LogAggregator $logAggregator, array $after): array
    {
        $table = Common::prefixTable('log_link_visit_action');
        $next = [];

        // ponytail: reads every action of these visits. Ask the database for the next action
        // per visit if visits with very many actions make this slow.
        foreach (array_chunk(array_keys($after), self::VISITS_PER_QUERY) as $idVisits) {
            $cursor = $logAggregator->getDb()->query(
                "SELECT idvisit, idlink_va, UNIX_TIMESTAMP(server_time) AS time,
                        COALESCE(NULLIF(idaction_event_action, 0), NULLIF(idaction_url, 0), idaction_name, 0) AS idaction
                   FROM $table WHERE idvisit IN (" . implode(',', array_map('intval', $idVisits)) . ')'
            );

            while ($row = $cursor->fetch()) {
                $idVisit = $row['idvisit'];
                $idAction = (int) $row['idlink_va'];

                if (
                    $this->isAfter([(int) $row['time'], $idAction], $after[$idVisit], false)
                    && (!isset($next[$idVisit]) || $idAction < $next[$idVisit][0])
                ) {
                    $next[$idVisit] = [$idAction, (int) $row['idaction']];
                }
            }
            $cursor->closeCursor();
        }

        return $next;
    }

    /**
     * @param array $visits visit ID => anything
     * @param array $next as returned by getNextActions
     * @return array<string|int, int> how many of the visits did what next: by action ID, or by
     *         NEXT_EXIT when the visit ended
     */
    private function countNextActions(array $visits, array $next): array
    {
        $counts = [];
        foreach ($visits as $idVisit => $unused) {
            $key = isset($next[$idVisit]) ? $next[$idVisit][1] : self::NEXT_EXIT;
            $counts[$key] = ($counts[$key] ?? 0) + 1;
        }

        return $counts;
    }

    /**
     * Replaces the action IDs of the next actions with "<action type>:<name>".
     */
    private function labelNextActions(LogAggregator $logAggregator, array $counts): array
    {
        $ids = [];
        foreach ($counts as $stepCounts) {
            $ids += array_filter($stepCounts['next'], 'is_int', ARRAY_FILTER_USE_KEY);
        }

        $labels = [];
        foreach (array_chunk(array_keys($ids), self::VISITS_PER_QUERY) as $chunk) {
            $rows = $logAggregator->getDb()->fetchAll(
                'SELECT idaction, type, name FROM ' . Common::prefixTable('log_action') . ' WHERE idaction IN (' . implode(',', array_map('intval', $chunk)) . ')'
            );
            foreach ($rows as $row) {
                $labels[(int) $row['idaction']] = $row['type'] . ':' . $row['name'];
            }
        }

        foreach ($counts as &$stepCounts) {
            $next = [];
            foreach ($stepCounts['next'] as $key => $count) {
                $label = is_int($key) ? ($labels[$key] ?? self::NEXT_OTHER) : $key;
                $next[$label] = ($next[$label] ?? 0) + $count;
            }
            $stepCounts['next'] = $next;
        }

        return $counts;
    }
}
