<?php

return [
    'merchant_id' => env('CLICK_MERCHANT_ID'),
    'merchant_user_id' => env('CLICK_MERCHANT_USER_ID'),
    'secret_key' => env('CLICK_SECRET_KEY'),
    'service_id' => env('CLICK_SERVICE_ID'),

    // To'lov sahifasi; query string shunga ulanadi, shuning uchun "?" bilan tugaydi.
    'base_url' => env('CLICK_BASE_URL', 'https://my.click.uz/services/pay?'),

    // Merchant API ildizi (fiskal chek va boshqa so'rovlar uchun).
    'api_url' => rtrim((string)env('CLICK_API_URL', 'https://api.click.uz/v2'), '/'),

    // To'lovdan keyin foydalanuvchi qaytariladigan sahifa.
    'return_url' => env('CLICK_RETURN_URL'),

    // Fiskal chek (OFD) ma'lumotlari. Click kabinetida berilgan qiymatlar.
    'fiscal' => [
        'enabled' => (bool)env('CLICK_FISCAL_ENABLED', false),
        'inn' => env('CLICK_FISCAL_INN'),
        'vat_percent' => (int)env('CLICK_FISCAL_VAT_PERCENT', 0),
        'spic' => env('CLICK_FISCAL_SPIC'),
        'package_code' => env('CLICK_FISCAL_PACKAGE_CODE'),
        'item_name' => env('CLICK_FISCAL_ITEM_NAME'),
    ],
];
