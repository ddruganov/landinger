<?php

namespace core\models\token;

use core\components\helpers\CookieHelper;
use core\models\user\User;

class TokenHelper
{
    public function getAccessTokenFromCookies(): ?AccessToken
    {
        // проверяем, жив ли access token
        if ($accessTokenCookie = CookieHelper::getCookie(TokenGroupGenerator::ACCESS_TOKEN_NAME)) {
            $at = AccessToken::findOne(['value' => $accessTokenCookie]);
            if ($at && $at->verify()) {
                return $at;
            }
        }

        // если access token помер, то надо выдать новый, но только при условии, что клиент передал нам верный refresh token
        if ($refreshTokenAr = $this->getRefreshTokenFromCookies()) {
            $tokens = (new TokenGroupGenerator())->refreshTokenGroup($refreshTokenAr);
            if (!$tokens) {
                return null;
            }

            $at = AccessToken::findOne($tokens['access']['model_id']);
            $user = User::findOne($at->getUserId());
            $user->login($tokens);
            return $at;
        }

        // если же refresh token неверный или его нет, то человек протупил и разлогинился, либо с его страницы сидит кто-то другой
        return null;
    }

    public function getRefreshTokenFromCookies(): ?RefreshToken
    {
        return RefreshToken::findOne(['value' => CookieHelper::getCookie(TokenGroupGenerator::REFRESH_TOKEN_NAME)]);
    }

    public function getUserId(): ?int
    {
        return $this->getAccessTokenFromCookies()?->getUserId();
    }
}
