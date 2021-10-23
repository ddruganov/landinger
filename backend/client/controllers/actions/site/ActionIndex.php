<?php

namespace client\controllers\actions\site;

use yii\base\Action;

class ActionIndex extends Action
{
    public function run()
    {
        return $this->controller->redirect('/about');
    }
}
