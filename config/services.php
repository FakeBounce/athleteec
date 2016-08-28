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
        'client_id' => '670367580165-47irghgle4pfmk5i5srf1s13u3phckmv.apps.googleusercontent.com',
        'client_secret' => '0ZBD5r3mh-OAa1IRS0ueQOq2',
        'redirect' => env('APP_URL', 'https://localhost').'login/callback/google',
    ]

];
