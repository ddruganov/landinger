<?php

use yii\helpers\ArrayHelper;

if (isset(getallheaders()['Origin'])) {
    header('Access-Control-Allow-Origin: ' . getallheaders()['Origin']);
}
header('Access-Control-Allow-Methods: POST, PATCH, DELETE, PUT, GET, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Accept, Authorization, Access-Control-Allow-Headers, Origin, Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers, OrganizationId');
header('Access-Control-Allow-Credentials: true');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

$indexLocalPath = __DIR__ . '/index-local.php';
$indexLocal = file_exists($indexLocalPath) ? require($indexLocalPath) : [];

// comment out the following two lines when deployed to production
defined('YII_DEBUG') or define('YII_DEBUG', $indexLocal['YII_DEBUG'] ?? false);
defined('YII_ENV') or define('YII_ENV', $indexLocal['YII_ENV'] ?? 'prod');

require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/../../vendor/yiisoft/yii2/Yii.php';

require __DIR__ . '/../../config/bootstrap.php';

$config = ArrayHelper::merge(
    require __DIR__ . '/../../config/web.php',
    require __DIR__ . '/../config/web.php',
);

(new yii\web\Application($config))->run();
