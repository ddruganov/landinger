<?php

$params = require __DIR__ . '/params.php';

$config = [
    'id' => 'console.linktome',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'console\controllers',
    'params' => $params,
];

return $config;
