<?php

return [
    'models' => [
        'user' => 'App\Models\User',
        'receipt' => \Cr4sec\AppleAppsFlyer\Models\Receipt::class,
        'purchase' => \Cr4sec\AppleAppsFlyer\Models\Purchase::class
    ],

    'apple' => [
        'mode' => env('APPLE_MODE', 'sandbox'),
        'shared_secret' => env('APPLE_SHARED_SECRET')
    ],

    'appsflyer' => [
        'app_id' => env('APPSFLYER_APP_ID'),
        'authentication_token' => env('APPSFLYER_AUTHENTICATION_TOKEN')
    ]
];
