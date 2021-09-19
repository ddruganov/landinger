<?php

namespace app\controllers;

use app\collectors\landing\LandingAllCollector;
use app\collectors\landing\LandingCommonCollector;
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
                    'common'  => ['GET'],
                    'all'  => ['GET'],
                    'create' => ['POST'],
                    'save' => ['PATCH'],
                    'delete' => ['DELETE'],
                    'create_link' => ['POST'],
                    'delete_link' => ['DELETE'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'common' => [
                'class' => CollectorAction::class,
                'collectorClass' => LandingCommonCollector::class
            ],
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
