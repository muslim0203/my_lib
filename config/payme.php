<?php

return [
    'merchant_id' => env('PAYME_MERCHANT_ID'),
    'login' => env('PAYME_LOGIN'),
    'key' => env('PAYME_KEY'),
    'url' => env('PAYME_URL'),
    'test_mode' => env('PAYME_TEST_MODE', true),
];
