<?php

$params = require __DIR__ . '/params.php';

$config = [
    'id' => 'service.linktome',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'service\controllers',
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
