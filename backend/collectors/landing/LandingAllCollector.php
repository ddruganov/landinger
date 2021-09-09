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
            ->select(['id', 'name'])
            ->from(Landing::tableName())
            ->where(['creator_id' => $this->getParam('userId')]);

        $this->ids && $query->where(['in', 'id', $this->ids]);

        $landings = $query->all();

        foreach ($landings as $idx => $landing) {
            $links = (new Query())
                ->select([
                    'id',
                    'name',
                    'value'
                ])
                ->from(LandingLink::tableName())
                ->where(['landing_id' => $landing['id']])
                ->all();

            $landings[$idx]['links'] = $links;
        }

        return $landings;
    }

    public function setIds(array $value): static
    {
        $this->ids = $value;
        return $this;
    }
}
