<?php

declare(strict_types=1);

return [
    'oauth_key' => [
        'api' => [
            'url' => env('OAUTH_KEY_URL', 'http://idp-webserver/api/keys/public'),
        ],
    ],
    'mailer' => [
        'host' => env('NOTIFIER_HOST', 'http://mailer-webserver'),
    ],
    'geolocalizer' => [
        'host' => env('GEOLOCALIZER_HOST', 'http://geolocalizer-webserver'),
    ],
];