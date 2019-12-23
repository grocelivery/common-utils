<?php

declare(strict_types=1);

return [
    'oauth_key' => [ // OAuth public key source
        'api' => [ // load from URL
            'url' => env('OAUTH_KEY_URL', 'http://idp-webserver/api/keys/public'),
        ],
//      'file' => [ // load from local file
//            'path' => env('OAUTH_KEY_PATH', '/storage/oauth-public.key'),
//       ],
    ],
    'notifier' => [
        'host' => env('NOTIFIER_HOST', 'http://notifier-webserver'),
    ],
    'geolocalizer' => [
        'host' => env('GEOLOCALIZER_HOST', 'http://geolocalizer-webserver'),
    ],
];