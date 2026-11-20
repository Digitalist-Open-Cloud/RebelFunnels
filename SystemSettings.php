<?php

namespace Piwik\Plugins\RebelFunnels;

use Exception;
use Piwik\Piwik;
use Piwik\Settings\FieldConfig;

/**
 * The limits of the plugin. Core uses a value of the same name in the [RebelFunnels] section
 * of config.ini.php instead, and then hides the setting.
 */
class SystemSettings extends \Piwik\Settings\Plugin\SystemSettings
{
    public const MAX_STEPS = 'max_steps';
    public const DEFAULT_MAX_STEPS = 10;

    // Each step is a query when a day is archived.
    private const HIGHEST_MAX_STEPS = 30;

    protected function init()
    {
        $this->makeSetting(self::MAX_STEPS, self::DEFAULT_MAX_STEPS, FieldConfig::TYPE_INT, function (FieldConfig $field) {
            $field->title = Piwik::translate('RebelFunnels_SettingMaxSteps');
            $field->inlineHelp = Piwik::translate('RebelFunnels_SettingMaxStepsHelp');
            $field->uiControl = FieldConfig::UI_CONTROL_TEXT;
            $field->validate = function ($value) {
                if (!is_numeric($value) || $value < 2 || $value > self::HIGHEST_MAX_STEPS) {
                    throw new Exception(Piwik::translate('RebelFunnels_ErrorSettingRange', [2, self::HIGHEST_MAX_STEPS]));
                }
            };
        });
    }

    public function getMaxSteps(): int
    {
        $value = (int) $this->getSetting(self::MAX_STEPS)->getValue();

        return $value >= 2 ? $value : self::DEFAULT_MAX_STEPS;
    }
}
