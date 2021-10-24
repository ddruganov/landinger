<?php

namespace console\controllers;

use core\collectors\payment\ServiceAllCollector;
use core\collectors\payment\ServiceDurationAllCollector;
use core\components\ErrorLog;
use core\models\payment\PaidService;
use core\models\payment\ServiceDuration;
use core\models\user\User;
use Throwable;
use Yii;
use yii\console\Controller;

class TestController extends Controller
{
    public function actionIndex()
    {
        $transaction = Yii::$app->db->beginTransaction();

        try {
            $services = (new ServiceAllCollector())->get();
            ErrorLog::log($services);

            foreach ($services as $service) {
                ErrorLog::log('durations for service #', $service['id']);
                ErrorLog::log((new ServiceDurationAllCollector())->setParam('serviceId', $service['id'])->get());
            }

            $serviceDuration = ServiceDuration::findOne(4);
            $superUser = User::findOne(SUPERUSER_ID);

            $paidServiceCreateRes = PaidService::create([
                'userId' => $superUser->getId(),
                'serviceDurationId' => $serviceDuration->getId()
            ]);

            ErrorLog::log('paid service create res:', $paidServiceCreateRes);
        } catch (Throwable $t) {
            ErrorLog::log('error:', $t->getMessage(), $t->getTraceAsString());
        }

        $transaction->rollBack();
    }
}
