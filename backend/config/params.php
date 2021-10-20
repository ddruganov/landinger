<?php

$hosts = [
    'api' => 'http://localhost:8001',
    'admin' => 'http://localhost:8080',
    'client' => 'http://localhost:8005',
    'service' => 'http://localhost:8007'
];

return [
    'token' => [
        'accessTTL' => 1800, // 30 minutes
        'refreshTTL' => 2592000 // 30 days
    ],
    'hosts' => $hosts,
    'links' => [
        'admin' => [
            'home' => $hosts['admin'],
            'login' => $hosts['admin'] . '/auth/login',
            'auth' => [
                'social' => [
                    'vk' => $hosts['admin'] . '/auth/social/vk',
                    'yandex' => $hosts['admin'] . '/auth/social/yandex',
                    'google' => $hosts['admin'] . '/auth/social/google',
                    'facebook' => $hosts['admin'] . '/auth/social/facebook',
                ]
            ]
        ],
        'service' => [
            'uploadFolder' => $hosts['service'] . '/upload/image',
            'defaultImage' => $hosts['service'] . '/images/default.svg',
        ]
    ],
    'socialNetworkApi' => require __DIR__ . '/socialNetworkApi.php',
];
