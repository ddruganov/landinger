<?php

namespace app\controllers;

use app\controllers\actions\landing\CreateLandingAction;
use app\controllers\actions\landing\GetAllLandingsAction;
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
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'all' => GetAllLandingsAction::class,
            'create' => CreateLandingAction::class,
        ];
    }
}
