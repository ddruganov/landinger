<?php

use yii\helpers\ArrayHelper;

$paramsLocalPath = __DIR__ . '/params-local.php';
$paramsLocal = file_exists($paramsLocalPath) ? require $paramsLocalPath : [];

$finalParams = ArrayHelper::merge([
    'hosts' => [
        'api' => 'https://api.linktome.site',
        'admin' => 'https://admin.linktome.site',
        'service' => 'https://service.linktome.site',
        'main' => 'https://linktome.site',
    ]
], $paramsLocal);

return [
    'token' => [
        'accessTTL' => 1800, // 30 minutes
        'refreshTTL' => 2592000, // 30 days
        'domain' => '.linktome.site'
    ],
    'hosts' => $finalParams['hosts'],
    'links' => [
        'admin' => [
            'home' => $finalParams['hosts']['admin'],
            'login' => $finalParams['hosts']['admin'] . '/auth/login',
            'auth' => [
                'social' => [
                    'vk' => $finalParams['hosts']['admin'] . '/auth/social/vk',
                    'yandex' => $finalParams['hosts']['admin'] . '/auth/social/yandex',
                    'google' => $finalParams['hosts']['admin'] . '/auth/social/google',
                    'facebook' => $finalParams['hosts']['admin'] . '/auth/social/facebook',
                ]
            ]
        ],
        'service' => [
            'uploadFolder' => $finalParams['hosts']['service'] . '/upload/image',
            'defaultImage' => $finalParams['hosts']['service'] . '/images/default.svg',
        ]
    ],
    'socialNetworkApi' => require __DIR__ . '/socialNetworkApi.php',
    'telegram' => require __DIR__ . '/telegram.php',
];
