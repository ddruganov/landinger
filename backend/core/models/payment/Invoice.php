<?php

namespace core\models\payment;

use core\components\ExtendedActiveRecord;

/**
 * @property int $id
 * @property string $creationDate
 * @property int $modelId
 * @property int $modelTypeId
 * @property string $paymentDate
 * @property int $acquiringSystemId
 * @property float $paymentAmount
 * @property float $income
 */
class Invoice extends ExtendedActiveRecord
{
    public static function tableName()
    {
        return 'payment.invoice';
    }

    public function rules()
    {
        return [
            [['creationDate', 'modelId', 'modelTypeId', 'acquiringSystemId', 'paymentAmount'], 'required'],
            [['creationDate', 'paymentDate'], 'string'],
            [['modelId', 'modelTypeId', 'acquiringSystemId'], 'integer'],
            [['creationDate', 'paymentDate'], 'date', 'format' => 'php:Y/m/d'],
            [['paymentAmount', 'income'], 'number']
        ];
    }
}
