<?php

/**
 * @var \yii\web\View $this
 * @var array $landing_data [
 *   int $id
 *   string $name
 *   string $alias
 *   array entities [[
 *     int $id
 *     int $modelTypeId
 *     ?int parentId
 *     string $name
 *     string $value
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

<div class="landing" style="background:<?= $landing_data['background']['value'] ?>">
    <div class="container">
        <h1 class="page-title"><?= $landing_data['name'] ?></h1>

        <?php foreach ($landing_data['entities'] as $entity) : ?>
            <?= EntityDisplayWidget::widget([
                'entity' => $entity
            ]) ?>
        <?php endforeach ?>
    </div>
</div>