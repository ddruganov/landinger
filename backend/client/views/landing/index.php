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

use client\widgets\landing\EntityDisplay;

?>

<div style="background:<?= $landing_data['background']['value'] ?>; width: 100%; display: flex; flex-direction: column; padding: 1rem;">
    <div style="width: 100%; max-width: 768px; margin: 0 auto">
        <h3 style="text-align: center;"><?= $landing_data['name'] ?></h3>

        <?php foreach ($landing_data['entities'] as $entity) : ?>
            <?= EntityDisplay::widget([
                'entity' => $entity
            ]) ?>
        <?php endforeach ?>
    </div>
</div>