<?php

namespace Piwik\Plugins\RebelFunnels\Matching;

use Piwik\Common;
use Piwik\Db;
use Piwik\Plugin\Manager;
use Piwik\Tracker\Action;
use Piwik\Tracker\GoalManager;

/**
 * The rules of funnel steps as SQL conditions on the names of tracked actions. The database
 * compares, also when a single URL is tested, so there is one implementation of each operator.
 */
class Rules
{
    // Rule type => the column of log_link_visit_action with the action, and the action's type.
    public const TYPES = [
        'url' => ['idaction_url', Action::TYPE_PAGE_URL],
        'title' => ['idaction_name', Action::TYPE_PAGE_TITLE],
        'event_category' => ['idaction_event_category', Action::TYPE_EVENT_CATEGORY],
        'event_action' => ['idaction_event_action', Action::TYPE_EVENT_ACTION],
        'event_name' => ['idaction_name', Action::TYPE_EVENT_NAME],
        'download' => ['idaction_url', Action::TYPE_DOWNLOAD],
        'outlink' => ['idaction_url', Action::TYPE_OUTLINK],
        'search' => ['idaction_name', Action::TYPE_SITE_SEARCH],
    ];

    // A rule of this type is matched by a conversion, not an action. Its value is the ID of a
    // goal or one of ECOMMERCE_GOALS.
    public const GOAL = 'goal';
    public const ECOMMERCE_GOALS = ['order' => GoalManager::IDGOAL_ORDER, 'cart' => GoalManager::IDGOAL_CART];

    // A rule of these types is matched by a form that the plugin RebelMyForms stored, by the name
    // of the form. Rule type => how far the visitor has to have got with the form, as that plugin
    // numbers it.
    public const FORM_TYPES = ['form_start' => 1, 'form_submit' => 2, 'form_success' => 3];
    public const FORMS_PLUGIN = 'RebelMyForms';
    public const FORMS_TABLE = 'rebelmyforms_log';

    public const OPERATORS = ['equals', 'contains', 'starts_with', 'ends_with', 'regex'];

    private const URL_TYPES = ['url', 'download', 'outlink'];
    private const URL_START = '~^https?://(www\.)?~i';

    /**
     * @param array[] $rules
     * @return array[] the rules that actions are compared with
     */
    public static function getActionRules(array $rules): array
    {
        return array_values(array_filter($rules, fn (array $rule) => isset(self::TYPES[$rule['type']])));
    }

    /**
     * @param array[] $rules
     * @return int[] the IDs of the goals whose conversions match one of the rules
     */
    public static function getGoalIds(array $rules): array
    {
        $ids = [];
        foreach ($rules as $rule) {
            if ($rule['type'] === self::GOAL) {
                $ids[] = self::ECOMMERCE_GOALS[$rule['value']] ?? (int) $rule['value'];
            }
        }

        return array_values(array_unique($ids));
    }

    public static function canUseForms(): bool
    {
        return Manager::getInstance()->isPluginActivated(self::FORMS_PLUGIN);
    }

    /**
     * @param array[] $rules
     * @return array[] the rules that stored forms are compared with
     */
    public static function getFormRules(array $rules): array
    {
        return array_values(array_filter($rules, fn (array $rule) => isset(self::FORM_TYPES[$rule['type']])));
    }

    /**
     * @param array[] $rules form rules
     * @param bool $onlySubmitted only the rules that need the form to be submitted
     * @return string a condition that is true for the stored forms any of the rules match
     */
    public static function getFormCondition(array $rules, bool $onlySubmitted = false): string
    {
        $conditions = ['0'];
        foreach ($rules as $rule) {
            $outcome = self::FORM_TYPES[$rule['type']];
            if ($onlySubmitted && $outcome < self::FORM_TYPES['form_submit']) {
                continue;
            }
            // The names of forms are stored encoded for HTML.
            if ($rule['operator'] !== 'regex') {
                $rule['value'] = Common::sanitizeInputValue($rule['value']);
            }
            $conditions[] = '(' . self::FORMS_TABLE . ".outcome >= $outcome AND " . self::compare(self::FORMS_TABLE . '.form_name', $rule) . ')';
        }

        return '(' . implode(' OR ', $conditions) . ')';
    }

    /**
     * When a stored form matched the rules: when it was started, or when it was submitted for a
     * rule that needs that. The form is stored with the time it was seen and how long it took
     * from there.
     *
     * @param array[] $rules form rules
     */
    public static function getFormTime(array $rules): string
    {
        $table = self::FORMS_TABLE;

        return "UNIX_TIMESTAMP($table.server_time) + FLOOR(($table.time_to_start + IF("
            . self::getFormCondition($rules, true) . ", $table.time_spent, 0)) / 1000)";
    }

    /**
     * @return string the alias of the log_action table that holds the names the rule compares
     */
    public static function getTableAlias(array $rule): string
    {
        return 'rebelfunnels_' . self::TYPES[$rule['type']][0];
    }

    /**
     * @param array[] $rules action rules
     * @return array[] the joins of log_action that the conditions of the rules need
     */
    public static function getJoins(array $rules): array
    {
        $joins = [];
        foreach ($rules as $rule) {
            $alias = self::getTableAlias($rule);
            $joins[$alias] = [
                'table' => 'log_action',
                'tableAlias' => $alias,
                'join' => 'LEFT JOIN',
                'joinOn' => 'log_link_visit_action.' . self::TYPES[$rule['type']][0] . " = $alias.idaction",
            ];
        }

        return array_values($joins);
    }

    /**
     * @param array[] $rules action rules
     * @return string a condition that is true for the actions any of the rules match
     */
    public static function getCondition(array $rules): string
    {
        $conditions = [];
        foreach ($rules as $rule) {
            $alias = self::getTableAlias($rule);
            $name = "$alias.name";
            if ($rule['type'] !== 'url' && in_array($rule['type'], self::URL_TYPES, true)) {
                // Page URLs are stored without protocol and "www.", other URLs with them.
                $name = "TRIM(LEADING 'www.' FROM SUBSTRING($name, IF(LOCATE('://', $name) BETWEEN 1 AND 6, LOCATE('://', $name) + 3, 1)))";
            }

            $conditions[] = "($alias.type = " . self::TYPES[$rule['type']][1] . ' AND ' . self::compare($name, $rule) . ')';
        }

        return '(' . implode(' OR ', $conditions) . ')';
    }

    /**
     * @param array[] $steps
     * @return bool[] for each step, whether one of its page URL rules matches the URL
     */
    public static function testUrl(array $steps, string $url): array
    {
        $subject = self::literal(self::normalize('url', 'equals', $url));

        $selects = [];
        foreach ($steps as $step) {
            $conditions = ['0'];
            foreach ($step['rules'] as $rule) {
                if ($rule['type'] === 'url') {
                    $conditions[] = self::compare($subject, $rule);
                }
            }
            $selects[] = '(' . implode(' OR ', $conditions) . ')';
        }

        return array_map('boolval', array_values(Db::fetchRow('SELECT ' . implode(', ', $selects))));
    }

    /**
     * Returns the value as it is compared: URLs lose their protocol and "www.", except in a
     * regular expression, which is used as it is written.
     */
    public static function normalize(string $type, string $operator, string $value): string
    {
        $value = trim($value);
        if ($operator !== 'regex' && in_array($type, self::URL_TYPES, true)) {
            $value = preg_replace(self::URL_START, '', $value);
        }

        return $value;
    }

    /**
     * @throws \Exception if the database cannot use the value as a regular expression
     */
    public static function assertValidRegex(string $value): void
    {
        Db::fetchOne('SELECT ' . self::literal('') . ' REGEXP ' . self::literal($value));
    }

    private static function compare(string $subject, array $rule): string
    {
        $value = self::normalize($rule['type'], $rule['operator'], $rule['value']);

        switch ($rule['operator']) {
            case 'equals':
                return "$subject = " . self::literal($value);
            case 'regex':
                return "$subject REGEXP " . self::literal($value);
        }

        $pattern = addcslashes($value, '\\%_');
        $pattern = ($rule['operator'] === 'starts_with' ? '' : '%') . $pattern . ($rule['operator'] === 'ends_with' ? '' : '%');

        return "$subject LIKE " . self::literal($pattern);
    }

    /**
     * A string as a hex literal, so that nothing a user typed is part of the query text.
     */
    private static function literal(string $value): string
    {
        return "_utf8mb4 X'" . bin2hex($value) . "'";
    }
}
