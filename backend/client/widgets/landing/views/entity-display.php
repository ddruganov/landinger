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

use client\widgets\landing\EntityDisplayWidget;
use core\models\common\ModelType;
use yii\web\View;

$id = 'EntityDisplay_' . md5(microtime());

if ($entity['modelTypeId'] === ModelType::LANDING_LINK_GROUP) {
    $this->registerJs("
    new EntityDisplayWidget({
        selector: '#$id'
    })
", View::POS_END);
}

?>

<div id="<?= $id ?>" class="entity-display-widget" style="padding-left: <?= $depth * 2 ?>rem">

    <?php if ($entity['modelTypeId'] === ModelType::LANDING_LINK_GROUP) : ?>
        <div class="group">
            <span class="name"><?= $entity['name'] ?></span>
            <div class="arrow left"></div>
        </div>
    <?php else : ?>
        <a class="link" href="<?= $entity['value'] ?>"><?= $entity['name'] ?></a>
    <?php endif ?>

    <?php if ($entity['children']) : ?>
        <div class="children" hidden>
            <?php foreach ($entity['children'] as $child) : ?>
                <?= EntityDisplayWidget::widget([
                    'entity' => $child,
                    'depth' => $depth + 1
                ]) ?>
            <?php endforeach; ?>
        </div>
    <?php endif ?>
</div>