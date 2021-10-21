<?php

namespace client\controllers\actions\landing;

use core\collectors\landing\LandingAllCollector;
use core\models\landing\Landing;
use core\models\landing\LandingVisit;
use yii\base\Action;
use yii\base\UserException;

class ActionView extends Action
{
    public function run(string $alias)
    {
        if (!$alias) {
            throw new UserException('Такой страницы не существует', 404);
        }

        $landing = Landing::findOne(['alias' => $alias]);
        if (!$landing) {
            throw new UserException('Такого лендинга не существует', 401);
        }

        LandingVisit::create([
            'landingId' => $landing->id
        ]);

        $landingData = (new LandingAllCollector())
            ->setParams([
                'userId' => $landing->creatorId,
                'ids' => $landing->id,
                'excludeEmptyEntities' => true,
            ])->one();

        return $this->controller->render('index', [
            'landingData' => $landingData
        ]);
    }
}
