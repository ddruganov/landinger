<?php

$exception = Yii::$app->getErrorHandler()->exception;

?>

<div class="error container">
    <h1>Ошибка!</h1>
    <i class="icon far fa-frown"></i>
    <?php if (in_array($exception->getCode(), [404])) : ?>
        <h3><?= $exception->getMessage() ?></h3>
    <?php else : ?>
        <h3>Ничего страшного!</h3>
        <div>Мы уже знаем об ошибке и скоро всё исправим!</div>
    <?php endif ?>
</div>