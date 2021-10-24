<?php

namespace core\models\common;

use core\components\ExtendedActiveRecord;
use core\models\landing\Landing;
use core\models\landing\LandingBackground;
use core\models\landing\LandingEntity;
use core\models\landing\LandingImage;
use core\models\landing\LandingLink;
use core\models\landing\LandingLinkGroup;
use core\models\landing\LandingText;
use core\models\payment\Invoice;
use core\models\payment\PaidService;
use core\models\payment\Resource;
use core\models\payment\ResourceType;
use core\models\payment\Service;
use core\models\payment\ServiceDuration;
use core\models\payment\ServiceResource;

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
    public const LANDING_IMAGE = 6;
    public const LANDING_TEXT = 7;

    public const INVOICE = 100;
    public const SERVICE = 101;
    public const SERVICE_DURATION = 102;
    public const RESOURCE_TYPE = 103;
    public const RESOURCE = 104;
    public const SERVICE_RESOURCE = 105;
    public const PAID_SERVICE = 106;

    public static function tableName()
    {
        return 'public.model_type';
    }

    public function rules()
    {
        return [
            [['tableName', 'class', 'name', 'tableAlias'], 'required'],
            [['tableName', 'class', 'name', 'tableAlias'], 'string']
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
            case self::LANDING_IMAGE:
                return LandingImage::class;
            case self::LANDING_TEXT:
                return LandingText::class;
            case self::INVOICE:
                return Invoice::class;
            case self::SERVICE:
                return Service::class;
            case self::SERVICE_DURATION:
                return ServiceDuration::class;
            case self::RESOURCE_TYPE:
                return ResourceType::class;
            case self::RESOURCE:
                return Resource::class;
            case self::SERVICE_RESOURCE:
                return ServiceResource::class;
            case self::PAID_SERVICE:
                return PaidService::class;
        }
    }
}
