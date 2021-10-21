<?php

use yii\helpers\ArrayHelper;

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

// comment out the following two lines when deployed to production
defined('YII_DEBUG') or define('YII_DEBUG', false);
defined('YII_ENV') or define('YII_ENV', 'dev');

require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/../../vendor/yiisoft/yii2/Yii.php';

require __DIR__ . '/../../config/bootstrap.php';

$config = ArrayHelper::merge(
    require __DIR__ . '/../../config/web.php',
    require __DIR__ . '/../config/web.php',
);

(new yii\console\Application($config))->run();
