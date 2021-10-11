<?php

namespace client\controllers\actions\site;

use yii\base\Action;

class ActionError extends Action
{
    public function run()
    {
        return $this->controller->render('error');
    }
}
