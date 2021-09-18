<?php

namespace app\collectors\landing;

use app\collectors\AbstractDataCollector;
use app\models\landing\Landing;
use app\models\landing\LandingLink;
use yii\db\Query;

class LandingAllCollector extends AbstractDataCollector
{
    private array $ids = [];

    public function get(): array
    {
        $query = (new Query())
            ->select(['id', 'name', 'alias', 'backgroundId' => 'background_id'])
            ->from(Landing::tableName())
            ->where(['creator_id' => $this->getParam('userId')])
            ->orderBy(['id' => SORT_DESC]);

        $this->ids && $query->where(['in', 'id', $this->ids]);

        $landings = $query->all();

        foreach ($landings as $idx => $landing) {
            $landings[$idx]['links'] = (new Query())
                ->select([
                    'id',
                    'name',
                    'value',
                    'weight'
                ])
                ->from(LandingLink::tableName())
                ->where(['landing_id' => $landing['id']])
                ->orderBy(['weight' => SORT_ASC])
                ->all();
        }

        return $landings;
    }

    public function setIds(array $value): static
    {
        $this->ids = $value;
        return $this;
    }
}
