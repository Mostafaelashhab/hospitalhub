<?php

return [
    'app_url' => env('WAAPI_APP_URL', 'https://waapi.octopusteam.net/api/create-message'),
    'app_key' => env('WAAPI_APP_KEY', ''),
    'auth_key' => env('WAAPI_AUTH_KEY', ''),

    'webhook' => [
        'enabled' => env('WAAPI_WEBHOOK_ENABLED', true),
        'token' => env('WAAPI_WEBHOOK_SITE_TOKEN', null),
        'device_uuid' => env('WAAPI_UPDATE_DEVICE_WEBHOOK', null),
    ],
];
