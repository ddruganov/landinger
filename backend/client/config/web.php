<?php

$params = require __DIR__ . '/params.php';

$config = [
    'id' => 'client.flinq',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'client\controllers',
    'params' => $params,
    'components' => [
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'rules' => require __DIR__ . '/routes.php',
        ]
    ],
    'defaultRoute' => 'landing/view',
];

return $config;