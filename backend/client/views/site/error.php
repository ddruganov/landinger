<?php

$exception = Yii::$app->getErrorHandler()->exception;

?>

<div class="container w-100 h-100 d-flex align-items-center justify-content-center">
    <h1><?= $exception->getMessage() ?></h1>
</div>