<?php

namespace app\controllers;

use app\collectors\landing\LandingAllCollector;
use app\controllers\actions\generic\CollectorAction;
use app\controllers\actions\generic\CreateAction;
use app\controllers\actions\generic\DeleteAction;
use app\controllers\actions\generic\SaveAction;
use app\models\landing\Landing;
use app\models\landing\LandingLink;
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
            'all' => [
                'class' => CollectorAction::class,
                'collectorClass' => LandingAllCollector::class
            ],
            'create' => [
                'class' => CreateAction::class,
                'modelClass' => Landing::class
            ],
            'save' => [
                'class' => SaveAction::class,
                'modelClass' => Landing::class,
            ],
            'delete' => [
                'class' => DeleteAction::class,
                'modelClass' => Landing::class
            ],
            'create_link' => [
                'class' => CreateAction::class,
                'modelClass' => LandingLink::class
            ],
            'delete_link' => [
                'class' => DeleteAction::class,
                'modelClass' => LandingLink::class
            ]
        ];
    }
}
