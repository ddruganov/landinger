<?php

namespace client\controllers\actions\site;

use core\components\Telegram;
use Yii;
use yii\base\Action;

class ActionError extends Action
{
    public function run()
    {
        (new Telegram())
            ->setTitle('Ошибка на клиенте')
            ->setMessage(Yii::$app->errorHandler->exception->getMessage())
            ->setTrace(Yii::$app->errorHandler->exception->getTraceAsString())
            ->send();

        return $this->controller->render('error');
    }
}
