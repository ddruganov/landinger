<?php

namespace app\collectors\landing;

use app\collectors\AbstractDataCollector;
use app\models\landing\Landing;
use yii\db\Query;

class LandingAllCollector extends AbstractDataCollector
{
    public function get(): array
    {
        return (new Query())
            ->select(['id', 'name'])
            ->from(Landing::tableName())
            ->where(['creator_id' => $this->getParam('creatorId')])
            ->all();
    }
}
