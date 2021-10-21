<?php

$dbLocalPath = __DIR__ . '/db-local.php';
if (file_exists($dbLocalPath)) {
    return require_once $dbLocalPath;
}

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'pgsql:host=localhost;dbname=linktome',
    'username' => 'ddruganov',
    'password' => 'admin',
    'charset' => 'utf8'
];
