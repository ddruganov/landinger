<?php

namespace core\collectors\payment;

use core\collectors\AbstractDataCollector;
use core\models\payment\Service;
use core\models\payment\ServiceDuration;
use yii\db\Query;

class ServiceDurationAllCollector extends AbstractDataCollector
{
    public function get(): array
    {
        $query = (new Query())
            ->select(['sd.id', 's.name', 'sd.duration', 'sd.price'])
            ->from(['sd' => ServiceDuration::tableName()])
            ->innerJoin(['s' => Service::tableName()], 's.id = sd.service_id')
            ->orderBy(['sd.duration' => SORT_ASC])
            ->andWhere([
                'in', 's.id', $this->getParam('serviceId')
            ]);

        return $query->all();
    }

    public function getDataSource(): string
    {
        return self::DATA_SOURCE_GET;
    }
}
