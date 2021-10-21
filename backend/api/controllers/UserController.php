<?php

namespace api\controllers;

use api\controllers\actions\generic\SaveAction;
use core\models\user\User;
use yii\web\Controller;

class UserController extends Controller
{
    public function actions()
    {
        return [
            'save' => [
                'class' => SaveAction::class,
                'modelClass' => User::class
            ]
        ];
    }
}
