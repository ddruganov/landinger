<?php

namespace app\models\landing;

use app\components\ExtendedActiveRecord;

/**
 * @property int $id
 * @property string $value
 * @property string $params
 */
class LandingBackground extends ExtendedActiveRecord
{
    public const DEFAULT_VALUE = '204,99,39.4;0,0,0';
    public const DEFAULT_PARAMS = 'linear-gradient(hsl(204, 99%, 39.4%) 0%, hsl(0, 0%, 0%) 100%)';

    public static function tableName()
    {
        return 'landing.landing_background';
    }

    public function rules()
    {
        return [
            [['id', 'value'], 'required'],
            [['id'], 'integer'],
            [['value', 'params'], 'string']
        ];
    }
}
