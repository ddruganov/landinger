<?php

namespace core\models\user\behaviors;

use core\components\Telegram;
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

    public function getSocialValue(int $socialTypeId): ?string
    {
        return $this->getUserSocial($socialTypeId)->getValue();
    }

    private function getUserSocial(int $socialTypeId): UserSocial
    {
        $params = [
            'userId' => $this->owner->id,
            'typeId' => $socialTypeId
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

    public function saveSocialValue(int $socialTypeId, string $value): bool
    {
        (new Telegram())->setTitle('saving user social #' . $socialTypeId)->setMessage($value)->send();

        $model = $this->getUserSocial($socialTypeId);
        $model->value = $value;
        return $model->save();
    }
}
