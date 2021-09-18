<?php

namespace app\collectors\landing;

use app\collectors\AbstractDataCollector;
use app\models\landing\LandingBackground;
use yii\db\Query;

class LandingCommonCollector extends AbstractDataCollector
{
    public function get(): array
    {
        return [
            'backgrounds' => (new Query())
                ->select(['id', 'name', 'value'])
                ->from(LandingBackground::tableName())
                ->all()
        ];
    }
}
