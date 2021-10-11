<?php

/**
 * @var array $entity [
 *     int $id
 *     int $modelTypeId
 *     ?int parentId
 *     string $name
 *     string $value
 *     int $weight
 *     entity[] children
 * ]
 * @var int $depth
 */

use client\widgets\landing\EntityDisplay;
use core\models\common\ModelType;
use yii\web\View;

$id = 'EntityDisplay_' . md5(microtime());

$this->registerJs("
    new EntityDisplayWidget({
        seletor: '#$id'
    })
", View::POS_END);

?>

<div id="<?= $id ?>" style="display: flex; flex-direction: column; margin-top: 1rem; padding-left: <?= $depth * 2 ?>rem">

    <div style="border: 1px solid black; padding: 1rem; display: flex; align-items: center; justify-content: space-between">
        <?php if ($entity['modelTypeId'] === ModelType::LANDING_LINK_GROUP) : ?>
            <span><?= $entity['name'] ?></span>
        <?php else : ?>
            <a href="<?= $entity['value'] ?>"><?= $entity['name'] ?></a>
        <?php endif ?>
        <?php if ($entity['children']) : ?>
            <button class="child-toggler">+</button>
        <?php endif ?>
    </div>

    <?php if ($entity['children']) : ?>
        <div class="children" style="display: none">
            <?php foreach ($entity['children'] as $child) : ?>
                <?= EntityDisplay::widget([
                    'entity' => $child,
                    'depth' => $depth + 1
                ]) ?>
            <?php endforeach; ?>
        </div>
    <?php endif ?>
</div>