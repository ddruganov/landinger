<?php

namespace api\controllers;

use api\controllers\actions\auth\GetCurrentUserAction;
use api\controllers\actions\auth\LoginAction;
use api\controllers\actions\auth\LogoutAction;
use api\controllers\actions\auth\RegisterAction;
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
