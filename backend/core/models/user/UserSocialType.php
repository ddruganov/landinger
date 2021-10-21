<?php

namespace core\models\user;

use core\components\ExtendedActiveRecord;

/**
 * This is the model class for table "user.user_social_type".
 *
 * @property int $id
 * @property string $name
 */
class UserSocialType extends ExtendedActiveRecord
{
    public const VK = 1;
    public const YANDEX = 2;
    public const GOOGLE = 3;
    public const FACEBOOK = 4;

    public static function tableName(): string
    {
        return 'user.user_social_type';
    }

    public static function fromAlias(string $alias): static
    {
        return self::find()->where(['name' => $alias])->cache(0)->one();
    }

    public function getId(): int
    {
        return $this->id;
    }
}
