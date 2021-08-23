<?php

namespace app\models\common;

use app\components\ExtendedActiveRecord;

/**
 * This is the model class for table "public.model_type".
 *
 * @property int $id
 * @property string $tableName
 * @property string $class
 * @property string $name
 * @property string $tableAlias
 */
class ModelType extends ExtendedActiveRecord
{
    public const USER = 1;
    public const LANDING = 2;

    public static function tableName()
    {
        return 'public.model_type';
    }

    public function rules()
    {
        return [
            [['table_name', 'class', 'name', 'table_alias'], 'required'],
            [['table_name', 'class', 'name', 'table_alias'], 'string']
        ];
    }
}
