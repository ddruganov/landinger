<?php

namespace app\models\token;

use app\components\helpers\CookieHelper;
use app\models\user\User;

class TokenHelper
{
    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

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
        if ($refreshTokenCookie = CookieHelper::getCookie(TokenGroupGenerator::REFRESH_TOKEN_NAME)) {
            $refreshTokenAr = RefreshToken::findOne(['value' => $refreshTokenCookie]);
            if ($refreshTokenAr) {
                $tokens = (new TokenGroupGenerator())->refreshTokenGroup($refreshTokenAr);
                if (!$tokens) {
                    return null;
                }

                return AccessToken::findOne($tokens['access']['model_id']);
            }
        }

        // если же refresh token неверный или его нет, то человек протупил и разлогинился, либо с его страницы сидит кто-то другой
        return null;
    }

    public function getModelId(): ?int
    {
        return $this->getAccessTokenFromCookies()?->getModelId();
    }
}
