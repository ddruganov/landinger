<?php

namespace core\models\payment;

use core\components\ExtendedActiveRecord;

/**
 * @property int $id
 * @property int $typeId
 * @property int $amount
 */
class Resource extends ExtendedActiveRecord
{
    public const APP_ACCESS = 1;
    public const ONE_LANDING = 2;
    public const THREE_LANDINGS = 3;

    public static function tableName()
    {
        return 'payment.resource';
    }

    public function rules()
    {
        return [
            [['id', 'typeId', 'amount'], 'required'],
            [['id', 'typeId', 'amount'], 'integer'],
        ];
    }
}
