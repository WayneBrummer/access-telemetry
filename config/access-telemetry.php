<?php

return [
    'enabled'    => true,
    'frequency'  => ['daily'],
    'at'         => '16:00',
    'log_age'    => env('PRUNE_ACCESS_LOGS_SECONDS', 15552000),
];
