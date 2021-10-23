<?php

$params = require __DIR__ . '/params.php';

$config = [
    'id' => 'client.linktome',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'client\controllers',
    'params' => $params,
    'components' => [
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'rules' => require __DIR__ . '/routes.php',
            'enablePrettyUrl' => true,
            'showScriptName' => false
        ],
        'assetManager' => [
            'forceCopy' => true
        ],
        'request' => [
            'enableCsrfValidation' => false,
        ],
    ],
    'defaultRoute' => 'site/index',
];

return $config;
