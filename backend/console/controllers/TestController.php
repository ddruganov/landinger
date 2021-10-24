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
            $serviceDuration = ServiceDuration::findOne(4);
            $superUser = User::findOne(SUPERUSER_ID);

            $paidServiceCreateRes = PaidService::create([
                'userId' => $superUser->getId(),
                'serviceDurationId' => $serviceDuration->getId()
            ]);

            $paidService = PaidService::findOne($paidServiceCreateRes->getData('id'));
            $invoice = $paidService->getInvoice();
            ErrorLog::log('paid service invoice:', $invoice->getAttributes());
            ErrorLog::log('is paid service paid?', $paidService->isPaid());
            ErrorLog::log('is invoice paid?', $invoice->isPaid());

            $invoicePayRes = $invoice->pay([
                'acquiringSystemId' => 1,
                'income' => $invoice->getPaymentAmount() * (1 - (2.9 / 100))
            ]);
            ErrorLog::log('invoice pay res:', $invoicePayRes);
            ErrorLog::log('invoice after payment', $invoice->getAttributes());

            ErrorLog::log('is paid service paid?', $invoice->getBoundModel()->isPaid());
            ErrorLog::log('is invoice paid?', $invoice->isPaid());
        } catch (Throwable $t) {
            ErrorLog::log('error:', $t->getMessage(), $t->getTraceAsString());
        }

        $transaction->rollBack();
    }

    public function actionServiceCollectors()
    {
        $transaction = Yii::$app->db->beginTransaction();

        try {
            $services = (new ServiceAllCollector())->get();
            ErrorLog::log($services);

            foreach ($services as $service) {
                ErrorLog::log('durations for service #', $service['id']);
                ErrorLog::log((new ServiceDurationAllCollector())->setParam('serviceId', $service['id'])->get());
            }
        } catch (Throwable $t) {
            ErrorLog::log('error:', $t->getMessage(), $t->getTraceAsString());
        }

        $transaction->rollBack();
    }
}
