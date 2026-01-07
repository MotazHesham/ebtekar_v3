<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'google' => [
        'client_id'     => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect'      => env('APP_URL').'/social-login/google/callback',
    ],

    'facebook' => [
        'client_id'     => env('FACEBOOK_CLIENT_ID'),
        'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
        'redirect'      => env('APP_URL').'/social-login/facebook/callback',
    ],

    'egyptexpress' => [
        'base_url' => env('EGYPTEXPRESS_BASE_URL', ''),
        'username' => env('EGYPTEXPRESS_USERNAME', ''),
        'password' => env('EGYPTEXPRESS_PASSWORD', ''),
        'account_no' => env('EGYPTEXPRESS_ACCOUNT_NO', ''),
        'sender' => [
            'address1' => env('EGYPTEXPRESS_SENDER_ADDRESS1', ''),
            'address2' => env('EGYPTEXPRESS_SENDER_ADDRESS2', ''),
            'city' => env('EGYPTEXPRESS_SENDER_CITY', ''),
            'company' => env('EGYPTEXPRESS_SENDER_COMPANY', ''),
            'contact_person' => env('EGYPTEXPRESS_SENDER_CONTACT', ''),
            'email' => env('EGYPTEXPRESS_SENDER_EMAIL', ''),
            'mobile' => env('EGYPTEXPRESS_SENDER_MOBILE', ''),
            'phone' => env('EGYPTEXPRESS_SENDER_PHONE', ''),
        ],
    ],
];
