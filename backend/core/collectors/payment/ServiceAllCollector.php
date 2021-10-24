<?php

namespace core\collectors\payment;

use core\collectors\AbstractDataCollector;
use core\models\payment\Service;
use yii\db\Query;

class ServiceAllCollector extends AbstractDataCollector
{
    public function get(): array
    {
        $query = (new Query())
            ->select(['id', 'name'])
            ->from(Service::tableName())
            ->orderBy(['weight' => SORT_ASC])
            ->where([
                'not in', 'id', Service::DEMO_ACCESS
            ]);

        return $query->all();
    }
}
