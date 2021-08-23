<?php

namespace app\controllers;

use app\controllers\actions\auth\GetCurrentUserAction;
use app\controllers\actions\auth\LoginAction;
use app\controllers\actions\auth\LogoutAction;
use app\controllers\actions\auth\RegisterAction;
use yii\web\Controller;

class AuthController extends Controller
{
    public function actions()
    {
        return [
            'register' => RegisterAction::class,

            'login' => LoginAction::class,
            'logout' => LogoutAction::class,

            'getCurrentUser' => GetCurrentUserAction::class
        ];
    }
}
