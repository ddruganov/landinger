<?php

$dbLocalPath = __DIR__ . '/db-local.php';
if (file_exists($dbLocalPath)) {
    return require_once $dbLocalPath;
}

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'pgsql:host=5.45.120.15;dbname=linktome',
    'username' => 'demid',
    'password' => 'p3dfJKDHB9HC4x2g',
    'charset' => 'utf8'
];
