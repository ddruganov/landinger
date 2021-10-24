<?php

namespace api\controllers;

use api\controllers\actions\generic\CollectorAction;
use core\collectors\payment\ServiceAllCollector;
use core\collectors\payment\ServiceDurationAllCollector;
use yii\web\Controller;

class PaymentController extends Controller
{
    public function actions()
    {
        return [
            'allServices' => [
                'class' => CollectorAction::class,
                'collectorClass' => ServiceAllCollector::class
            ],
            'allServiceDurations' => [
                'class' => CollectorAction::class,
                'collectorClass' => ServiceDurationAllCollector::class
            ]
        ];
    }
}
