<?php

namespace api\controllers;

use api\controllers\actions\auth\GetCurrentUserAction;
use api\controllers\actions\auth\LogoutAction;
use api\controllers\actions\auth\ActionSocial;
use api\controllers\actions\generic\CollectorAction;
use core\collectors\auth\SocialLinkCollector;
use yii\web\Controller;

class AuthController extends Controller
{
    public function actions()
    {
        return [
            'logout' => LogoutAction::class,

            'getCurrentUser' => GetCurrentUserAction::class,

            'getSocialLinks' => [
                'class' => CollectorAction::class,
                'collectorClass' => SocialLinkCollector::class,
            ],
            'social' => ActionSocial::class
        ];
    }
}
