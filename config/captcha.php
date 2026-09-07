<?php


return [
    'google-captcha' => [
        'url'        => env('RECAPTCHA_SITE'),
        'site-key'   => env('RECAPTCHA_SITE_KEY'),
        'secret-key' => env('RECAPTCHA_SECRET_KEY')
    ]
];
