<?php

/**
 * @var \yii\web\View $this
 * @var string $content
 */

use yii\helpers\Html;

?>

<?php $this->beginPage() ?>

<!DOCTYPE html>
<html lang="<?= Yii::$app->charset ?>" style="width: 100%; min-height: 100vh; margin: 0; padding: 0; display: flex;">

<head>
    <meta charset="<?= Yii::$app->charset ?>" />
    <title><?= Html::encode($this->title) ?></title>
    <link rel="icon" type="image/vnd.microsoft.icon" href="/img/favicon.ico">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <?= Html::csrfMetaTags() ?>
    <?php $this->head() ?>

</head>

<body style="width: 100%; min-height: 100vh; margin: 0; padding: 0; display: flex;">
    <?php $this->beginBody() ?>

    <?= $content ?>

    <?php $this->endBody() ?>
</body>

</html>

<?php $this->endPage() ?>