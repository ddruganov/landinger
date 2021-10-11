<?php

namespace core\components\helpers;

use core\models\token\TokenHelper;
use core\models\user\User;

class UserHelper
{
    private static ?User $currentUser = null;

    public static function id(): ?int
    {
        if (self::$currentUser) {
            return self::$currentUser->id;
        }
        return (new TokenHelper())->getUserId();
    }

    public static function get(): ?User
    {
        if (self::$currentUser) {
            return self::$currentUser;
        }

        $id = self::id();
        if (!$id) {
            return null;
        }

        self::$currentUser = User::findOne($id);

        return self::$currentUser;
    }
}
