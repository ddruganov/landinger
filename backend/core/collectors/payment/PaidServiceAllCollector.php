<?php

namespace core\collectors\payment;

use core\collectors\AbstractDataCollector;
use core\components\helpers\DateHelper;
use core\models\common\ModelType;
use core\models\payment\Invoice;
use core\models\payment\PaidService;
use core\models\payment\Service;
use core\models\payment\ServiceDuration;
use yii\db\Query;

class PaidServiceAllCollector extends AbstractDataCollector
{
    public function get(): array
    {
        $query = (new Query())
            ->select([
                'ps.id',
                'creationDate' => "to_char(ps.creation_date, 'DD-MM-YYYY')",
                'expirationDate' => "to_char(ps.expiration_date, 'DD-MM-YYYY')",
                'serviceName' => 's.name',
                'pricePaid' => 'i.payment_amount'
            ])
            ->from(['ps' => PaidService::tableName()])
            ->innerJoin(['sd' => ServiceDuration::tableName()], 'sd.id = ps.service_duration_id')
            ->innerJoin(['s' => Service::tableName()], 's.id = sd.service_id')
            ->innerJoin(['i' => Invoice::tableName()], 'i.model_id = ps.id')
            ->orderBy(['ps.id' => SORT_DESC])
            ->where([
                'and',
                ['ps.user_id' => $this->getParam('userId')],
                ['i.model_type_id' => ModelType::PAID_SERVICE],
                [
                    'or',
                    ['>', 'ps.expiration_date', DateHelper::now()],
                    ['is', 'ps.expiration_date', null]
                ]
            ]);

        return $query->all();
    }
}
