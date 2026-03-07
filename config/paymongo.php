<?php

return [
    /*
    |--------------------------------------------------------------------------
    | PayMongo API Keys
    |--------------------------------------------------------------------------
    |
    | Your PayMongo API keys. You can get these from your PayMongo dashboard.
    | Use test keys for development and live keys for production.
    |
    */

    'public_key' => env('PAYMONGO_PUBLIC_KEY'),
    'secret_key' => env('PAYMONGO_SECRET_KEY'),
    'webhook_secret' => env('PAYMONGO_WEBHOOK_SECRET'),

    /*
    |--------------------------------------------------------------------------
    | PayMongo API Version
    |--------------------------------------------------------------------------
    |
    | The PayMongo API version to use.
    |
    */

    'version' => '2022-05-23',

    /*
    |--------------------------------------------------------------------------
    | PayMongo Livemode
    |--------------------------------------------------------------------------
    |
    | Set to true when using live API keys, false for test keys.
    |
    */

    'livemode' => env('PAYMONGO_LIVEMODE', false),
];
