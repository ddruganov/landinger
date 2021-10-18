<?php

$hosts = [
    'api' => 'http://localhost:8001',
    'admin' => 'http://localhost:8080',
];

return [
    'token' => [
        'accessTTL' => 1800, // 30 minutes
        'refreshTTL' => 2592000 // 30 days
    ],
    'hosts' => $hosts,
    'links' => [
        'api' => [
            'auth' => [
                'social' => [
                    'vk' => $hosts['api'] . '/auth/social/vk'
                ]
            ]
        ],
        'admin' => [
            'home' => $hosts['admin'],
            'login' => $hosts['admin'] . '/auth/login'
        ]
    ],
    'socialNetworkApi' => require __DIR__ . '/socialNetworkApi.php',
];
