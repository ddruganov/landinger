<?php

$params = require __DIR__ . '/params.php';

$config = [
    'id' => 'api.linktome',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'api\controllers',
    'params' => $params,
    'components' => [
        'request' => [
            'enableCsrfValidation' => false,
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false
        ]
    ]
];

return $config;
