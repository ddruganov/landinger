<?php

namespace app\models\common;

use app\components\ExtendedActiveRecord;
use app\models\landing\Landing;
use app\models\landing\LandingBackground;
use app\models\landing\LandingEntity;
use app\models\landing\LandingLink;
use app\models\landing\LandingLinkGroup;

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
    public const LANDING = 1;
    public const LANDING_BACKGROUND = 2;
    public const LANDING_ENTITY = 3;
    public const LANDING_LINK_GROUP = 4;
    public const LANDING_LINK = 5;

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

    public static function getModelClassById(int $id): string
    {
        switch ($id) {
            case self::LANDING:
                return Landing::class;
            case self::LANDING_BACKGROUND:
                return LandingBackground::class;
            case self::LANDING_ENTITY:
                return LandingEntity::class;
            case self::LANDING_LINK_GROUP:
                return LandingLinkGroup::class;
            case self::LANDING_LINK:
                return LandingLink::class;
        }
    }
}
