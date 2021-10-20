<?php

/**
 * @var \yii\web\View $this
 * @var array $landingData [
 *   int $id
 *   string $name
 *   string $alias
 *   array entities [[
 *     int $id
 *     int $modelTypeId
 *     ?int parentId
 *     string $name
 *     string $value
 *     array $image [ int $id, string $url ]
 *     int $weight
 *     entity[] children
 *   ]]
 *   array $background [
 *     string $value
 *   ]
 * ]
 */

use client\assets\landing\EntityDisplayAsset;
use client\widgets\landing\EntityDisplayWidget;

EntityDisplayAsset::register($this);

?>

<div class="landing" style="background:<?= $landingData['background']['value'] ?>">
    <div class="container">
        <h1 class="page-title"><?= $landingData['name'] ?></h1>

        <?php foreach ($landingData['entities'] as $entity) : ?>
            <?= EntityDisplayWidget::widget([
                'entity' => $entity
            ]) ?>
        <?php endforeach ?>
    </div>
</div>