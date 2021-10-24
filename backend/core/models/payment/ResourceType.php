<?php

namespace core\models\payment;

use core\components\ExtendedActiveRecord;

/**
 * @property int $id
 * @property string $name
 */
class ResourceType extends ExtendedActiveRecord
{
    public const APP_ACCESS = 1;
    public const LANDING = 2;

    public static function tableName()
    {
        return 'payment.resource_type';
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
