<?php

return [

    'default' => env('REVERB_SERVER', 'reverb'),

    'servers' => [

        'reverb' => [
            'host' => env('REVERB_HOST', '0.0.0.0'),
            'port' => env('REVERB_PORT', 8080),
            'hostname' => env('REVERB_HOST', 'localhost'),
            'options' => [
                'tls' => [],
            ],
            'max_request_size' => env('REVERB_MAX_REQUEST_SIZE', 10_000),
            'scaling' => [
                'enabled' => env('REVERB_SCALING_ENABLED', false),
                'channel' => env('REVERB_SCALING_CHANNEL', 'reverb'),
                'server' => [
                    'url' => env('REDIS_URL'),
                    'host' => env('REDIS_HOST', '127.0.0.1'),
                    'port' => env('REDIS_PORT', '6379'),
                    'database' => env('REVERB_SCALING_DATABASE', '0'),
                ],
            ],
            'pulse_ingest_interval' => env('REVERB_PULSE_INGEST_INTERVAL', 15),
        ],

    ],

    'apps' => [

        'provider' => 'config',

        'apps' => [
            [
                'key'     => env('REVERB_APP_KEY', 'icaretrue-key'),
                'secret'  => env('REVERB_APP_SECRET', 'icaretrue-secret'),
                'app_id'  => env('REVERB_APP_ID', 'icaretrue'),
                'options' => [
                    'host' => env('REVERB_HOST', '127.0.0.1'),
                    'port' => env('REVERB_PORT', 8080),
                    'scheme' => env('REVERB_SCHEME', 'http'),
                    'useTLS' => env('REVERB_SCHEME', 'http') === 'https',
                ],
                'allowed_origins' => ['*'],
                'ping_interval'   => env('REVERB_APP_PING_INTERVAL', 60),
                'ping_timeout'    => env('REVERB_APP_PING_TIMEOUT', 10),
                'activity_timeout'=> env('REVERB_APP_ACTIVITY_TIMEOUT', 30),
            ],
        ],

    ],

];
