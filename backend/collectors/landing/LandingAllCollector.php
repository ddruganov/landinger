<?php

namespace app\collectors\landing;

use app\collectors\AbstractDataCollector;
use app\models\landing\Landing;
use yii\db\Query;

class LandingAllCollector extends AbstractDataCollector
{
    private array $ids = [];

    public function get(): array
    {
        $query = (new Query())
            ->select(['id', 'name'])
            ->from(Landing::tableName())
            ->where(['creator_id' => $this->getParam('creatorId')]);

        $this->ids && $query->where(['in', 'id', $this->ids]);

        return $query->all();
    }

    public function setIds(array $value): static
    {
        $this->ids = $value;
        return $this;
    }
}
