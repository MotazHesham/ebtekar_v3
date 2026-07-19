<?php

return [
    'view_date_format'    => 'F j, Y',
    'date_format'         => 'd/m/Y',
    'time_format'         => 'h:i a',
    'primary_language'    => 'ar',
    'available_languages' => [
        'en' => 'English',
        'ar' => 'Arabic',
    ],
    'registration_default_role' => '2',
    'phone_number_validation' => 'phone:EG,SA,AE,QA,KW', //EGYPT, SAUDI , EMIRATES ,QATAR , KUWAIT
    'phone_number_language' => 'regex:/[0-9]/',
    'phone_number_format' => '/(01)[0-9]{9}/',
    'phone_number_size' => '11',

    'cache_time_long'     => 86400,
    'cache_time_medium'   => 3600,
    'cache_time_short'    => 600,
    'max_characters_long' => 3000,
    'max_characters_medium' => 1000,
    'max_characters_short' => 255,
    'password_min_length' => 8,
    'password_max_length' => 20,
    'phone_validation' => 'regex:/^05[0-9]{8}$/',
    'identity_validation' => 'regex:/^[12]\d{9}$/',
    'max_file_size' => 2048,
    'validation_price'    => 'lt:9999999999999.99',
    'validation_integer' => 'lt:99999999999',
];
