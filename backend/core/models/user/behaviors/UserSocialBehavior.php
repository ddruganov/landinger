<?php

namespace core\models\user\behaviors;

use core\models\user\UserSocial;
use core\models\user\UserSocialType;
use yii\base\Behavior;

class UserSocialBehavior extends Behavior
{
    public function getVkId(): ?string
    {
        return $this->getSocialValue(UserSocialType::VK);
    }

    public function getYandexId(): ?string
    {
        return $this->getSocialValue(UserSocialType::YANDEX);
    }

    public function getGoogleId(): ?string
    {
        return $this->getSocialValue(UserSocialType::GOOGLE);
    }

    public function getSocialValue(int $social_type_id): ?string
    {
        return $this->getUserSocial($social_type_id)->getValue();
    }

    private function getUserSocial(int $social_type_id): UserSocial
    {
        $params = [
            'userId' => $this->owner->id,
            'typeId' => $social_type_id
        ];
        return UserSocial::findOne($params) ?? new UserSocial($params);
    }

    public function saveVkId(string $value): bool
    {
        return $this->saveSocialValue(UserSocialType::VK, $value);
    }

    public function saveYandexId(string $value): bool
    {
        return $this->saveSocialValue(UserSocialType::YANDEX, $value);
    }

    public function saveGoogleId(string $value): bool
    {
        return $this->saveSocialValue(UserSocialType::GOOGLE, $value);
    }

    public function saveSocialValue(int $social_type_id, string $value): bool
    {
        $model = $this->getUserSocial($social_type_id);
        $model->value = $value;
        return $model->save();
    }
}
