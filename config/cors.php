<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    | The list of allowed origins comes from the CORS_ALLOWED_ORIGINS
    | environment variable as a comma separated list of absolute origins, e.g.
    |
    |     CORS_ALLOWED_ORIGINS="https://marketplace.uz,https://admin.marketplace.uz"
    |
    | The previous value was the single malformed string '*, *, *, *, *', which
    | is not a wildcard and matches no origin at all.
    |
    */

    'paths'                    => ['api/*', 'sanctum/csrf-cookie', 'storage/*'],
    'allowed_methods'          => ['*'],

    'allowed_origins'          => array_values(array_filter(array_map(
        'trim',
        explode(',', (string) env('CORS_ALLOWED_ORIGINS', 'http://localhost,http://localhost:3000,http://localhost:5173,http://127.0.0.1:8000'))
    ), static fn (string $origin): bool => $origin !== '')),

    'allowed_origins_patterns' => [],

    'allowed_headers'          => ['*'],

    'exposed_headers'          => [],
    'max_age'                  => 3600,

    /*
    | The API authenticates with a JWT sent in the Authorization header, not
    | with a cookie, so cookie credentials are not required. Keeping this at
    | false also keeps a misconfigured origin list from becoming a session
    | hijacking primitive.
    */
    'supports_credentials'     => false,

];
