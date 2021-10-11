<?php

namespace api\controllers;

use core\collectors\landing\LandingAllCollector;
use core\collectors\landing\LandingCommonCollector;
use api\controllers\actions\generic\CollectorAction;
use api\controllers\actions\generic\CreateAction;
use api\controllers\actions\generic\DeleteAction;
use api\controllers\actions\generic\SaveAction;
use core\models\landing\Landing;
use core\models\landing\LandingEntity;
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
                    'create_entity' => ['POST'],
                    'delete_entity' => ['DELETE'],
                    '*' => []
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
            'create_entity' => [
                'class' => CreateAction::class,
                'modelClass' => LandingEntity::class
            ],
            'delete_entity' => [
                'class' => DeleteAction::class,
                'modelClass' => LandingEntity::class
            ]
        ];
    }
}
