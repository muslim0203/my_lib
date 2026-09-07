<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application. Just store away!
    |
    */

    'default' => env('FILESYSTEM_DISK', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been set up for each driver as an example of the required values.
    |
    | Supported Drivers: "local", "ftp", "sftp", "s3"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root'   => storage_path('app'),
            'throw'  => false,
        ],

        'public' => [
            'driver'     => 'local',
            'root'       => storage_path('app/public'),
            'url'        => env('APP_URL') . '/storage',
            'visibility' => 'public',
            'throw'      => false,
        ],

        /*
         * Pullik manba kitoblar, audio va ariza hujjatlari uchun disk.
         * Web root'dan tashqarida turadi va faqat avtorizatsiyadan o'tgan
         * `file-view` marshruti orqali o'qiladi.
         */
        'private' => [
            'driver'     => 'local',
            'root'       => storage_path('app/private'),
            'visibility' => 'private',
            'throw'      => false,
        ],

        's3' => [
            'driver'                  => 's3',
            'key'                     => env('AWS_ACCESS_KEY_ID'),
            'secret'                  => env('AWS_SECRET_ACCESS_KEY'),
            'region'                  => env('AWS_DEFAULT_REGION'),
            'bucket'                  => env('AWS_BUCKET'),
            'url'                     => env('AWS_URL'),
            'endpoint'                => env('AWS_ENDPOINT'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
            'throw'                   => false,
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Symbolic Links
    |--------------------------------------------------------------------------
    |
    | Here you may configure the symbolic links that will be created when the
    | `storage:link` Artisan command is executed. The array keys should be
    | the locations of the links and the values should be their targets.
    |
    */

    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],

    'allow_extensions' => [
        "png", "jpg", "pdf", "xlsx","jpeg","mp3", "wav", "aac", "flac", "ogg", "m4a", "wma", "aiff", "opus", "amr", "m4b", "dsf", "dff", "webp"
    ],

    'max_upload_size' => 70 * 1024, // 70 MB default

    'hash' => 'md5',

    'upload_path' => '',

    /*
     * Yuklangan fayllar jismonan qaysi diskka yoziladi. Standart qiymat
     * `private`: hech qanday fayl web root'dan to'g'ridan-to'g'ri o'qilmaydi.
     */
    'upload_disk' => env('FILESYSTEM_UPLOAD_DISK', 'private'),

    /*
     * Fayl yozuvlarida saqlanadigan mantiqiy URL prefiksi. Barcha fayl
     * havolalari shu himoyalangan marshrutga yo'naltiriladi.
     */
    'public_url_prefix' => '/api/file-view',

    /*
     * Eski fayllar hali ko'chirilmagan bo'lishi mumkin. O'qishda shu
     * disklar navbat bilan tekshiriladi (birinchi mos kelgani ishlatiladi).
     */
    'legacy_read_disks' => ['local', 'public'],
];
