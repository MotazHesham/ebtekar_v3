<?php

return [
    'name' => 'Shipping',

    /*
    |--------------------------------------------------------------------------
    | Shipping table prefix
    |--------------------------------------------------------------------------
    |
    | Applied to modular shipping tables (partners, shipments, scans, etc.).
    | Core tables such as users and deliver_men are not prefixed.
    |
    */
    'table_prefix' => env('SHIPPING_TABLE_PREFIX', 'sh_'),
];
