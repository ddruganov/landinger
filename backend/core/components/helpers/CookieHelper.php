<?php

namespace core\components\helpers;

use Yii;

class CookieHelper
{
    public static function setCookie(string $name, string $value, int $expirationDate)
    {
        $name = self::filterCookieName($name);
        $cookieOptions = [
            'expires' => $expirationDate,
            'path' => '/',
            'domain' => YII_DEBUG ? '' : Yii::$app->params['token']['domain'],
            'secure' => !YII_DEBUG,
            'httponly' => true,
            'samesite' => 'Lax'
        ];
        setcookie($name, $value, $cookieOptions);
        $_COOKIE[$name] = $value;
    }

    public static function removeCookie(string $name)
    {
        $name = self::filterCookieName($name);
        setcookie($name, null, 1, '/');
        if (isset($_COOKIE[$name])) {
            unset($_COOKIE[$name]);
        }
    }

    public static function getCookie(string $name): ?string
    {
        $name = self::filterCookieName($name);
        return $_COOKIE[$name] ?? null;
    }

    private static function filterCookieName(string $name): string
    {
        return str_replace('.', '_', $name);
    }
}
