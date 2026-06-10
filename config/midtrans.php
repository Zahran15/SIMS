<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Midtrans Environment
    |--------------------------------------------------------------------------
    | 'sandbox' untuk testing, 'production' untuk live
    */
    'environment' => env('MIDTRANS_ENV', 'sandbox'),

    /*
    |--------------------------------------------------------------------------
    | Server Key & Client Key
    |--------------------------------------------------------------------------
    | Ambil dari dashboard Midtrans:
    | Sandbox  : https://dashboard.sandbox.midtrans.com/settings/config_info
    | Production: https://dashboard.midtrans.com/settings/config_info
    */
    'server_key'  => env('MIDTRANS_SERVER_KEY', ''),
    'client_key'  => env('MIDTRANS_CLIENT_KEY', ''),

    /*
    |--------------------------------------------------------------------------
    | Snap URL
    |--------------------------------------------------------------------------
    */
    'snap_url' => env('MIDTRANS_ENV', 'sandbox') === 'production'
        ? 'https://app.midtrans.com/snap/snap.js'
        : 'https://app.sandbox.midtrans.com/snap/snap.js',

    'api_url' => env('MIDTRANS_ENV', 'sandbox') === 'production'
        ? 'https://app.midtrans.com/snap/v1/transactions'
        : 'https://app.sandbox.midtrans.com/snap/v1/transactions',

    /*
    |--------------------------------------------------------------------------
    | Enabled Payment Methods
    |--------------------------------------------------------------------------
    | Kosongkan array untuk mengaktifkan semua metode pembayaran
    | Atau isi spesifik: ['credit_card', 'bca_va', 'bni_va', 'bri_va',
    |   'permata_va', 'other_va', 'gopay', 'shopeepay', 'qris',
    |   'indomaret', 'alfamart', 'akulaku', 'kredivo']
    */
    'enabled_payments' => [],

    /*
    |--------------------------------------------------------------------------
    | Expiry (opsional)
    |--------------------------------------------------------------------------
    */
    'expiry' => [
        'unit'     => 'hours',
        'duration' => 24,
    ],

];
