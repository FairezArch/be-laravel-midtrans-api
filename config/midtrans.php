<?php

return [
    'mercant_id' => env('MIDTRANS_MERCHAT_ID'),
    'client_key' => env('MIDTRANS_CLIENT_KEY'),
    'server_key' => env('MIDTRANS_SERVER_KEY'),
    'sandbox_url' => env('MIDTRANS_URL_SANDBOX'),

    'is_production' => false,
    'is_sanitized' => false,
    'is_3ds' => false,
];
