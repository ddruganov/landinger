<?php

namespace app\models\token;

use app\components\helpers\CookieHelper;
use app\models\common\ModelType;
use app\models\user\User;
use Yii;
use Firebase\JWT\JWT;

class TokenGroupGenerator
{
    public const ACCESS_TOKEN_NAME = 'AccessToken';
    public const REFRESH_TOKEN_NAME = 'RefreshToken';

    public function issueTokenGroup(User $user): ?array
    {
        $issueDate = time();
        $accessTokenExpirationDate = $issueDate + Yii::$app->params['token']['accessTTL'];
        $refreshTokenExpirationDate = $issueDate + Yii::$app->params['token']['refreshTTL'];

        // generate access token
        $accessToken = JWT::encode([
            'issuer' => Yii::$app->params['hosts']['admin'],
            'audience' => Yii::$app->params['hosts']['admin'],
            'userId' => $user->id,
            'issueDate' => date('Y-m-d H:i:s', $issueDate),
            'expirationDate' => date('Y-m-d H:i:s', $accessTokenExpirationDate)
        ], $user->password);
        // save access token
        $accessTokenAr = new AccessToken();
        $accessTokenAr->setAttributes([
            'value' => $accessToken,
            'isBlacklisted' => false
        ]);
        if (!$accessTokenAr->save()) {
            return null;
        }


        // generate refresh token
        $refreshToken = $this->generateRefreshToken($accessToken);
        // save refresh token and kill other active tokens
        $refreshTokenAr = new RefreshToken();
        $refreshTokenAr->setAttributes([
            'userId' => $user->id,
            'issueDate' => date('Y-m-d H:i:s', $issueDate),
            'expirationDate' => date('Y-m-d H:i:s', $refreshTokenExpirationDate),
            'value' => $refreshToken
        ]);
        $refreshTokenAr->voidCurrentTokens();
        if (!$refreshTokenAr->save()) {
            return null;
        }

        return [
            'access' => [
                'model_id' => $accessTokenAr->id,
                'token' => $accessToken,
                'expirationDate' => $accessTokenExpirationDate
            ],
            'refresh' => [
                'model_id' => $refreshTokenAr->id,
                'token' => $refreshToken,
                'expirationDate' => $refreshTokenExpirationDate
            ]
        ];
    }

    // Метод обновляет токен-группу
    public function refreshTokenGroup(?RefreshToken $refreshTokenAr): ?array
    {
        if (!$refreshTokenAr) {
            return null;
        }

        if ($refreshTokenAr->isExpired()) {
            CookieHelper::removeCookie(self::REFRESH_TOKEN_NAME);
            return null;
        }

        $user = User::findOne($refreshTokenAr->userId);

        return $user ? $this->issueTokenGroup($user) : null;
    }

    private function generateRefreshToken(string $accessToken): string
    {
        return md5(time() . $accessToken);
    }
}
