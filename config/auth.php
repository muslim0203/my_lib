<?php

use App\Models\Users\User;

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    |
    | This option controls the default authentication "guard" and password
    | reset options for your application. You may change these defaults
    | as required, but they're a perfect start for most applications.
    |
    */

    'defaults' => [
        'guard' => 'web',
        'passwords' => 'users',
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    |
    | Next, you may define every authentication guard for your application.
    | Of course, a great default configuration has been defined for you
    | here which uses session storage and the Eloquent user provider.
    |
    | All authentication drivers have a user provider. This defines how the
    | users are actually retrieved out of your database or other storage
    | mechanisms used by this application to persist your user's data.
    |
    | Supported: "session"
    |
    */

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],

        'mail' => [
            'driver' => 'jwt',
            'provider' => 'jwt-user-mail'
        ],

        'google' => [
            'driver' => 'jwt',
            'provider' => 'jwt-user-google'
        ],

        'phone' => [
            'driver' => 'jwt',
            'provider' => 'jwt-user-phone'
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    |
    | All authentication drivers have a user provider. This defines how the
    | users are actually retrieved out of your database or other storage
    | mechanisms used by this application to persist your user's data.
    |
    | If you have multiple user tables or models you may configure multiple
    | sources which represent each model / table. These sources may then
    | be assigned to any extra authentication guards you have defined.
    |
    | Supported: "database", "eloquent"
    |
    */

    'providers' => [
        'users' => [
            'driver' => env('AUTHENTICATION_PROVIDER_ADMIN'),
            'model' => User::class,
        ],

        'jwt-user-mail' => [
            'driver' => env('AUTHENTICATION_PROVIDER_MAIL'),
            'model' => User::class,
        ],

        'jwt-user-google' => [
            'driver' => env('AUTHENTICATION_PROVIDER_GOOGLE'),
            'model' => User::class
        ],

        'jwt-user-phone' => [
            'driver' => env('AUTHENTICATION_PROVIDER_PHONE'),
            'model' => User::class
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Resetting Passwords
    |--------------------------------------------------------------------------
    |
    | You may specify multiple password reset configurations if you have more
    | than one user table or model in the application and you want to have
    | separate password reset settings based on the specific user types.
    |
    | The expiry time is the number of minutes that each reset token will be
    | considered valid. This security feature keeps tokens short-lived so
    | they have less time to be guessed. You may change this as needed.
    |
    | The throttle setting is the number of seconds a user must wait before
    | generating more password reset tokens. This prevents the user from
    | quickly generating a very large amount of password reset tokens.
    |
    */

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Confirmation Timeout
    |--------------------------------------------------------------------------
    |
    | Here you may define the amount of seconds before a password confirmation
    | times out and the user is prompted to re-enter their password via the
    | confirmation screen. By default, the timeout lasts for three hours.
    |
    */

    'password_timeout' => 10800,

    'driver_mail' => env('AUTHENTICATION_PROVIDER_MAIL', 'mail'),
    'driver_google' => env('AUTHENTICATION_PROVIDER_GOOGLE', 'google'),
    'driver_phone' => env('AUTHENTICATION_PROVIDER_GOOGLE', 'phone'),
    'driver_admin' => env('AUTHENTICATION_PROVIDER_ADMIN', 'admin'),

    /*
     * OTP kodining amal qilish muddati (daqiqa). Ilgari standart qiymat
     * yo'q edi: env o'rnatilmaganda addMinutes(null) muddatni darhol
     * tugagan qilib qo'yardi.
     */
    'mail_code_expire' => (int)env('MAIL_CODE_EXPIRE_AT', 5),

    /*
     * Bitta kod uchun ruxsat etilgan noto'g'ri urinishlar soni.
     * Chegaraga yetganda kod bekor qilinadi.
     */
    'mail_code_max_attempts' => (int)env('MAIL_CODE_MAX_ATTEMPTS', 5),

    /*
     * Birinchi admin hisobini yaratish uchun sozlamalar. Parol faqat shu
     * yerdan olinadi; u bo'sh bo'lsa seeder to'xtaydi va hech qanday
     * admin yaratilmaydi. Mavjud admin hech qachon qayta yozilmaydi.
     */
    'admin_initial' => [
        'username' => env('ADMIN_INITIAL_USERNAME', 'admin'),
        'email' => env('ADMIN_INITIAL_EMAIL'),
        'password' => env('ADMIN_INITIAL_PASSWORD'),
    ],

];
