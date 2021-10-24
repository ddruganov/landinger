<?php

namespace core\models\payment;

use core\components\ExtendedActiveRecord;

/**
 * @property int $id
 * @property int $serviceId
 * @property int $duration
 * @property int $price
 */
class ServiceDuration extends ExtendedActiveRecord
{
    public const TWO_WEEKS = 14;
    public const MONTH = 30;
    public const THREE_MONTHS = 3 * self::MONTH;

    public static function tableName()
    {
        return 'payment.service_duration';
    }

    public function rules()
    {
        return [
            [['serviceId', 'duration', 'price'], 'required'],
            [['serviceId', 'duration', 'price'], 'integer'],
        ];
    }
}
