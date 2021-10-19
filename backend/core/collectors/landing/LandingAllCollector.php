<?php

namespace core\collectors\landing;

use core\collectors\AbstractDataCollector;
use core\models\landing\Landing;
use core\models\landing\LandingBackground;
use Yii;
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
            $landings[$idx]['entities'] = (new LandingEntityCollector())->setParam('landingId', $landing['id'])->get();

            $landings[$idx]['background'] = (new Query())
                ->select(['value', 'params'])
                ->from(LandingBackground::tableName())
                ->where(['id' => $landing['id']])
                ->one();

            $landings[$idx]['link'] = Yii::$app->params['hosts']['client'] . '/' . $landing['alias'];
        }

        return $landings;
    }

    public function one(): array
    {
        return @reset($this->get());
    }
}
