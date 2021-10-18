<?php

$params = require __DIR__ . '/params.php';

$config = [
    'id' => 'api.flinq',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'api\controllers',
    'params' => $params,
    'components' => [
        'urlManager' => [
            'rules' => require __DIR__ . '/routes.php'
        ],
    ],
];

return $config;
