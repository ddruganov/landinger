<?php

$params = require __DIR__ . '/params.php';

$config = [
    'id' => 'service.linktome',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'service\controllers',
    'params' => $params,
];

return $config;
