<?php

namespace core\models\payment;

use core\components\ExtendedActiveRecord;

/**
 * @property int $id
 * @property string $name
 */
class Service extends ExtendedActiveRecord
{
    public const DEMO_ACCESS = 1;
    public const BASE_ACCESS = 2;

    public static function tableName()
    {
        return 'payment.service';
    }

    public function rules()
    {
        return [
            [['id', 'name'], 'required'],
            [['id'], 'integer'],
            [['name'], 'string'],
        ];
    }
}
