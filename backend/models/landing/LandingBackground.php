<?php

namespace app\models\landing;

use app\components\ExtendedActiveRecord;

/**
 * @property int $id
 * @property string $name
 * @property string $value
 */
class LandingBackground extends ExtendedActiveRecord
{
    public const STANDART = 1;
    public const GRADIENT = 2;
    public const NEUTRAL = 3;

    public static function tableName()
    {
        return 'landing.landing_background';
    }
}
