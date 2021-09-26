<?php

namespace app\collectors\landing;

use app\collectors\AbstractDataCollector;
use app\models\landing\Landing;
use app\models\landing\LandingBackground;
use yii\db\Query;

class LandingAllCollector extends AbstractDataCollector
{
    public function get(): array
    {
        $query = (new Query())
            ->select(['id', 'name', 'alias'])
            ->from(Landing::tableName())
            ->where(['creator_id' => $this->getParam('userId')])
            ->orderBy(['id' => SORT_DESC]);

        $this->getParam('ids') && $query->where(['in', 'id', $this->getParam('ids')]);

        $landings = $query->all();

        foreach ($landings as $idx => $landing) {
            $landings[$idx]['entities'] = (new LandingLinkCollector())->setParam('landingId', $landing['id'])->get();

            $landings[$idx]['background'] = (new Query())
                ->select(['value', 'params'])
                ->from(LandingBackground::tableName())
                ->where(['id' => $landing['id']])
                ->one();
        }

        return $landings;
    }

    public function one(): array
    {
        return @reset($this->get());
    }
}
