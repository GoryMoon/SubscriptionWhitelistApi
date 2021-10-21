<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'patreon' => [
        'client_id' => env('PATREON_CLIENT_ID'),
        'client_secret' => env('PATREON_CLIENT_SECRET'),
        'redirect' => env('PATREON_REDIRECT_URI'),
    ],
];
