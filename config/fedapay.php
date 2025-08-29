<?php

return [
    'public_key' => env('FEDAPAY_PUBLIC_KEY', '') . '_',
    'secret_key' => env('FEDAPAY_SECRET_KEY', '') . 'D',
    'environment' => env('FEDAPAY_ENVIRONMENT', 'sandbox'), // sandbox | live
    'base_url' => env('FEDAPAY_ENVIRONMENT', 'sandbox') === 'live'
        ? 'https://api.fedapay.com'
        : 'https://sandbox.fedapay.com',
];


