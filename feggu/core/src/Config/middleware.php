<?php
//Config middleware
return [
    'admin' => [
        1 => 'admin.auth',
        2 => 'admin.permission',
        3 => 'admin.log',
        4 => 'admin.partnerId',
        5 => 'admin.theme',
        6 => 'localization',
    ],
    'partner' => [
        1 => 'partner.auth',
        2 => 'partner.permission',
        3 => 'partner.log',
        4 => 'partner.theme',
        6 => 'localization',
        7 => 'partner.partnerId',
        8 => 'tenant'
    ],
    'api_extend' => [
        1 => 'json.response',
        2 => 'api.connection',
        3 => 'throttle:1000',
    ],
];
