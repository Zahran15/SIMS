<?php

return [
    'environment' => env('MIDTRANS_ENV', 'sandbox'),
    'api_url'     => env('MIDTRANS_API_URL'),
    'server_key'  => env('MIDTRANS_SERVER_KEY', ''),
    'client_key'  => env('MIDTRANS_CLIENT_KEY', ''),
    'snap_url'    => env('MIDTRANS_ENV', 'sandbox') === 'production' 
        ? 'https://app.midtrans.com/snap/snap.js' 
        : 'https://app.sandbox.midtrans.com/snap/snap.js',

    'enabled_payments' => [],
    'expiry' => ['unit' => 'minutes', 'duration' => 15,],
];