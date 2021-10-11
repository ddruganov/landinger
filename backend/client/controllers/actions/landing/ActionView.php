<?php

namespace client\controllers\actions\landing;

use core\collectors\landing\LandingAllCollector;
use core\models\landing\Landing;
use core\models\landing\LandingVisit;
use Yii;
use yii\base\Action;
use yii\base\UserException;

class ActionView extends Action
{
    public function run(string $alias)
    {
        if (!$alias) {
            throw new UserException('Такой страницы не существует');
        }

        $landing = Landing::findOne(['alias' => $alias]);
        if (!$landing) {
            throw new UserException('Такого лендинга не существует');
        }

        LandingVisit::create([
            'landingId' => $landing->id,
            'ip' => Yii::$app->request->userIP
        ]);

        $landing_data = (new LandingAllCollector())->setParams([
            'userId' => $landing->creatorId,
            'ids' => $landing->id
        ])->one();

        return $this->controller->render('index', [
            'landing_data' => $landing_data
        ]);
    }
}
