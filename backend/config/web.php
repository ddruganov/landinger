<?php

use yii\helpers\ArrayHelper;

$webLocalPath = __DIR__ . '/web-local.php';
$webLocal = file_exists($webLocalPath) ? require $webLocalPath : [];

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'components' => [
        'db' => $db,
    ],
    'params' => $params,
];

return ArrayHelper::merge($config, $webLocal);
