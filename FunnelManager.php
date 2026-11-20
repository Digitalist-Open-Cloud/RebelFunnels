<?php

namespace Piwik\Plugins\RebelFunnels;

use Exception;
use Piwik\Archive\ArchiveInvalidator;
use Piwik\Piwik;
use Piwik\Plugin\Manager;
use Piwik\Plugins\Goals\API as GoalsApi;
use Piwik\Plugins\RebelFunnels\Archiving\ObsoleteRecords;
use Piwik\Plugins\RebelFunnels\Matching\Rules;
use Piwik\Segment;

/**
 * Creates, changes and deletes funnels, and keeps their archived data in step with them.
 */
class FunnelManager
{
    private const PLUGIN = 'RebelFunnels';
    private const MAX_NAME_LENGTH = 100;
    private const MAX_DESCRIPTION_LENGTH = 1000;
    private const MAX_RULES_PER_STEP = 20;
    private const MAX_VALUE_LENGTH = 1000;

    private Model $model;
    private ArchiveInvalidator $archiveInvalidator;
    private ObsoleteRecords $obsoleteRecords;
    private SystemSettings $settings;

    public function __construct(Model $model, ArchiveInvalidator $archiveInvalidator, ObsoleteRecords $obsoleteRecords, SystemSettings $settings)
    {
        $this->model = $model;
        $this->archiveInvalidator = $archiveInvalidator;
        $this->obsoleteRecords = $obsoleteRecords;
        $this->settings = $settings;
    }

    /**
     * Returns the funnel if it is available for the site.
     *
     * @throws Exception if there is no such funnel
     */
    public function getFunnel(int $idSite, int $idFunnel): array
    {
        $funnel = $this->model->getFunnel($idFunnel);

        if (!$funnel || ($funnel['idsite'] !== 0 && $funnel['idsite'] !== $idSite)) {
            throw new Exception(Piwik::translate('RebelFunnels_ErrorFunnelNotFound', $idFunnel));
        }

        return $funnel;
    }

    /**
     * @param array $definition name, description, steps and segment_filter
     * @throws Exception if the definition is not valid
     */
    public function addFunnel(int $idSite, array $definition, bool $allWebsites): int
    {
        $definition = $this->validate($idSite, $definition, $allWebsites);
        $definition['idsite'] = $allWebsites ? 0 : $idSite;

        $idFunnel = $this->model->addFunnel($definition);
        $this->scheduleArchiving($this->model->getFunnel($idFunnel));

        return $idFunnel;
    }

    /**
     * @throws Exception if the definition is not valid
     */
    public function updateFunnel(int $idSite, array $funnel, array $definition): void
    {
        $definition = $this->validate($idSite, $definition, $funnel['idsite'] === 0);

        // The names only change how the archived data is shown.
        $changesData = $this->getCountedPartOf($definition['steps']) !== $this->getCountedPartOf($funnel['steps'])
            || $definition['segment_filter'] !== $funnel['segment_filter'];

        $this->model->updateFunnel($funnel['idfunnel'], $definition, $changesData);

        if ($changesData) {
            $this->discardArchivedData($funnel);
            $this->scheduleArchiving($this->model->getFunnel($funnel['idfunnel']));
        }
    }

    /**
     * Sets the order of the funnels of the site, which is also the order of their pages.
     *
     * @param int[] $idFunnels the IDs of all funnels of the site in their new order
     * @param bool $mayMoveFunnelsOfAllWebsites whether a funnel defined for all websites may
     *                                          get another place, which changes it for every site
     * @throws Exception if the IDs are not the funnels of the site
     */
    public function reorderFunnels(int $idSite, array $idFunnels, bool $mayMoveFunnelsOfAllWebsites): void
    {
        $funnels = array_column($this->model->getFunnels($idSite), null, 'idfunnel');

        if (count($idFunnels) !== count($funnels) || array_diff(array_keys($funnels), $idFunnels)) {
            throw new Exception(Piwik::translate('RebelFunnels_ErrorFunnelOrder'));
        }

        // The funnels hand their positions around, so funnels of other sites keep their place.
        $positions = array_combine($idFunnels, array_column($funnels, 'position'));

        foreach ($positions as $idFunnel => $position) {
            $funnel = $funnels[$idFunnel];
            if ($funnel['idsite'] === 0 && $funnel['position'] !== $position && !$mayMoveFunnelsOfAllWebsites) {
                throw new Exception(Piwik::translate('RebelFunnels_ErrorOrderAllWebsites'));
            }
        }

        $this->model->setPositions($positions);
    }

    public function deleteFunnel(array $funnel): void
    {
        $this->model->deleteFunnel($funnel['idfunnel']);
        $this->discardArchivedData($funnel);
    }

    /**
     * Deletes the funnels of a website that no longer exists.
     */
    public function deleteFunnelsOfSite(int $idSite): void
    {
        foreach ($this->model->deleteFunnelsOfSite($idSite) as $funnel) {
            $this->discardArchivedData($funnel);
        }
    }

    /**
     * @return array the normalized definition
     * @throws Exception if the definition is not valid
     */
    public function validate(int $idSite, array $definition, bool $allWebsites): array
    {
        $name = trim($definition['name']);
        if ($name === '' || mb_strlen($name) > self::MAX_NAME_LENGTH) {
            throw new Exception(Piwik::translate('RebelFunnels_ErrorName', self::MAX_NAME_LENGTH));
        }

        $description = trim($definition['description']);
        if (mb_strlen($description) > self::MAX_DESCRIPTION_LENGTH) {
            throw new Exception(Piwik::translate('RebelFunnels_ErrorDescription', self::MAX_DESCRIPTION_LENGTH));
        }

        // Throws if the segment is not valid for the site.
        $segment = new Segment(trim($definition['segment_filter']), [$idSite]);

        return [
            'name' => $name,
            'description' => $description,
            'steps' => $this->validateSteps($definition['steps'], $idSite, $allWebsites),
            'segment_filter' => $segment->getString(),
        ];
    }

    /**
     * @param mixed $steps
     * @param bool $allWebsites whether the steps are for all websites, which have no goals in common
     * @return array<int, array{name: string, rules: array<int, array{type: string, operator: string, value: string}>, direct: bool}>
     * @throws Exception if the steps are not valid
     */
    public function validateSteps($steps, int $idSite, bool $allWebsites = false): array
    {
        $idGoals = $this->getGoalIds($idSite, $allWebsites);

        $maxSteps = $this->settings->getMaxSteps();
        if (!is_array($steps) || count($steps) < 2 || count($steps) > $maxSteps) {
            throw new Exception(Piwik::translate('RebelFunnels_ErrorStepCount', $maxSteps));
        }

        $valid = [];
        foreach (array_values($steps) as $index => $step) {
            $number = $index + 1;
            $name = trim((string) ($step['name'] ?? ''));
            if ($name === '' || mb_strlen($name) > self::MAX_NAME_LENGTH) {
                throw new Exception(Piwik::translate('RebelFunnels_ErrorStepName', [$number, self::MAX_NAME_LENGTH]));
            }

            $rules = $step['rules'] ?? [];
            if (!is_array($rules) || empty($rules) || count($rules) > self::MAX_RULES_PER_STEP) {
                throw new Exception(Piwik::translate('RebelFunnels_ErrorStepRules', [$number, self::MAX_RULES_PER_STEP]));
            }

            $valid[] = [
                'name' => $name,
                'rules' => array_map(fn ($rule) => $this->validateRule($rule, $number, $idGoals), array_values($rules)),
                // The first step has no step before it.
                'direct' => $index > 0 && in_array($step['direct'] ?? false, [true, 1, '1', 'true'], true),
            ];
        }

        return $valid;
    }

    /**
     * @param mixed $rule
     * @param string[] $idGoals the values a goal rule can have
     * @return array{type: string, operator: string, value: string}
     */
    private function validateRule($rule, int $stepNumber, array $idGoals): array
    {
        $type = (string) ($rule['type'] ?? '');
        $operator = (string) ($rule['operator'] ?? '');
        $value = trim((string) ($rule['value'] ?? ''));

        if ($type === Rules::GOAL) {
            if (!in_array($value, $idGoals, true)) {
                throw new Exception(Piwik::translate('RebelFunnels_ErrorRuleGoal', [$stepNumber, implode(', ', $idGoals)]));
            }

            return ['type' => $type, 'operator' => 'equals', 'value' => $value];
        }

        if (!isset(Rules::TYPES[$type])) {
            throw new Exception(Piwik::translate('RebelFunnels_ErrorRuleType', [$stepNumber, implode(', ', array_merge(array_keys(Rules::TYPES), [Rules::GOAL]))]));
        }
        if (!in_array($operator, Rules::OPERATORS, true)) {
            throw new Exception(Piwik::translate('RebelFunnels_ErrorRuleOperator', [$stepNumber, implode(', ', Rules::OPERATORS)]));
        }
        if ($value === '' || mb_strlen($value) > self::MAX_VALUE_LENGTH) {
            throw new Exception(Piwik::translate('RebelFunnels_ErrorRuleValue', [$stepNumber, self::MAX_VALUE_LENGTH]));
        }
        if ($operator === 'regex') {
            try {
                Rules::assertValidRegex($value);
            } catch (Exception $e) {
                throw new Exception(Piwik::translate('RebelFunnels_ErrorRuleRegex', [$stepNumber, $value]));
            }
        }

        return ['type' => $type, 'operator' => $operator, 'value' => $value];
    }

    /**
     * @return string[] the values a goal rule can have: "order", "cart" and the IDs of the
     *         goals of the website
     */
    private function getGoalIds(int $idSite, bool $allWebsites): array
    {
        $ids = array_keys(Rules::ECOMMERCE_GOALS);

        if (!$allWebsites && Manager::getInstance()->isPluginActivated('Goals')) {
            foreach (GoalsApi::getInstance()->getGoals($idSite) as $goal) {
                $ids[] = (string) $goal['idgoal'];
            }
        }

        return $ids;
    }

    private function getCountedPartOf(array $steps): array
    {
        return array_map(fn (array $step) => [$step['rules'], !empty($step['direct'])], $steps);
    }

    /**
     * Has core archive the funnel for past periods the next time archiving runs.
     */
    private function scheduleArchiving(array $funnel): void
    {
        $idSites = $funnel['idsite'] === 0 ? 'all' : [$funnel['idsite']];

        $this->archiveInvalidator->scheduleReArchiving($idSites, self::PLUGIN, Archiver::getRecordName($funnel));
    }

    /**
     * Drops what is waiting to be archived for the funnel's current record and has the data
     * archived for it removed.
     */
    private function discardArchivedData(array $funnel): void
    {
        $idSites = $funnel['idsite'] === 0 ? 'all' : [$funnel['idsite']];
        $recordName = Archiver::getRecordName($funnel);

        $this->archiveInvalidator->removeInvalidationsSafely($idSites, self::PLUGIN, $recordName);
        $this->obsoleteRecords->add($recordName, $funnel['idsite']);
    }
}
