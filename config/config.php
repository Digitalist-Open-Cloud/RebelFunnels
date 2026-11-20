<?php

return [
    // Lets core find a funnel report by this request parameter, e.g. for row evolution and exports.
    'entities.idNames' => Piwik\DI::add(['idFunnel']),
];
