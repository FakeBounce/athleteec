<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, Mandrill, and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => 'us-east-1',
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],

    'facebook' => [
        'client_id'     => '575914482592723',
        'client_secret' => '51f3b67977268f564ecabdf0ef300881',
        'redirect'      => env('APP_URL', 'https://localhost').'login/callback/facebook',
    ],

    'twitter' => [
        'client_id' => 'aY3nP0eqLsn2GIugrWcnXCd9h',
        'client_secret' => 'aWiRIWeMxG1N5l1Ys2PQ6tJXmZNepg2LPT0fufc3wsFU7AdqDW',
        'redirect' => env('APP_URL', 'https://localhost').'login/callback/twitter',
    ],

    'google' => [
        'client_id' => '77559020404-gug01gc8j48nmoe6pbs8j7iaudmgft9j.apps.googleusercontent.com',
        'client_secret' => 'ZSDML300Kf2O0eY5X1hAyypZ',
        'redirect' => env('APP_URL', 'https://localhost').'login/callback/google',
    ]

];
