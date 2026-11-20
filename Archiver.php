<?php

namespace Piwik\Plugins\RebelFunnels;

// Needed for Matomo to archive this plugin: its record builders are registered by event.
class Archiver extends \Piwik\Plugin\Archiver
{
    public static function getRecordName(array $funnel): string
    {
        return 'RebelFunnels_' . (int) $funnel['idfunnel'] . '_' . (int) $funnel['revision'];
    }
}
