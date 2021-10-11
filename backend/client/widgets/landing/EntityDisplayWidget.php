<?php

namespace client\widgets\landing;

use yii\base\Widget;

class EntityDisplayWidget extends Widget
{
    public array $entity;
    public int $depth = 0;

    public function run()
    {
        return $this->render('entity-display', [
            'entity' => $this->entity,
            'depth' => $this->depth,
        ]);
    }
}
