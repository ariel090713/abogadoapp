<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Twilio Account SID
    |--------------------------------------------------------------------------
    |
    | Your Twilio Account SID from twilio.com/console
    |
    */
    'account_sid' => env('TWILIO_ACCOUNT_SID'),

    /*
    |--------------------------------------------------------------------------
    | Twilio Auth Token
    |--------------------------------------------------------------------------
    |
    | Your Twilio Auth Token from twilio.com/console
    |
    */
    'auth_token' => env('TWILIO_AUTH_TOKEN'),

    /*
    |--------------------------------------------------------------------------
    | Twilio API Key SID
    |--------------------------------------------------------------------------
    |
    | Your Twilio API Key SID for generating access tokens
    | Create at: twilio.com/console/project/api-keys
    |
    */
    'api_key_sid' => env('TWILIO_API_KEY_SID'),

    /*
    |--------------------------------------------------------------------------
    | Twilio API Key Secret
    |--------------------------------------------------------------------------
    |
    | Your Twilio API Key Secret for generating access tokens
    |
    */
    'api_key_secret' => env('TWILIO_API_KEY_SECRET'),

    /*
    |--------------------------------------------------------------------------
    | Video Room Settings
    |--------------------------------------------------------------------------
    |
    | Default settings for video rooms
    |
    */
    'video' => [
        'room_type' => env('TWILIO_ROOM_TYPE', 'group'),
        'max_participants' => env('TWILIO_MAX_PARTICIPANTS', 2),
        'recording_enabled' => env('TWILIO_RECORDING_ENABLED', false),
        'token_ttl' => env('TWILIO_TOKEN_TTL', 3600), // 1 hour
    ],
];
