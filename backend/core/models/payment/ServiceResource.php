<?php

namespace core\models\payment;

use core\components\ExtendedActiveRecord;

/**
 * @property int $id
 * @property int $servceId
 * @property int $resourceId
 */
class ServiceResource extends ExtendedActiveRecord
{
    public static function tableName()
    {
        return 'payment.service_resource';
    }

    public function rules()
    {
        return [
            [['serviceId', 'resourceId'], 'required'],
            [['serviceId', 'resourceId'], 'integer'],
        ];
    }
}
