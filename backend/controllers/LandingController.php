<?php

namespace app\controllers;

use app\controllers\actions\generic\SaveAction;
use app\controllers\actions\landing\CreateLandingAction;
use app\controllers\actions\landing\CreateLandingLinkAction;
use app\controllers\actions\landing\GetAllLandingsAction;
use app\models\landing\Landing;
use yii\filters\VerbFilter;
use yii\web\Controller;

class LandingController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'all'  => ['GET'],
                    'create' => ['POST'],
                    'save' => ['PATCH'],
                    'create_link' => ['POST'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'all' => GetAllLandingsAction::class,
            'create' => CreateLandingAction::class,
            'save' => [
                'class' => SaveAction::class,
                'modelClass' => Landing::class,
            ],
            'create_link' => CreateLandingLinkAction::class,
        ];
    }
}
