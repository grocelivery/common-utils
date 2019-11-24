<?php

declare(strict_types=1);

return [
    'oauth_key' => [ // OAuth public key source
        'api' => [ // load from URL
            'url' => 'http://idp-webserver/api/keys/public',
        ],
//      'file' => [ // load from local file
//            'path' => '/storage/oauth-public.key',
//       ],
    ],
];