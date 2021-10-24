<?php

namespace console\controllers;

use core\collectors\payment\PaidServiceAllCollector;
use core\collectors\payment\ServiceAllCollector;
use core\collectors\payment\ServiceDurationAllCollector;
use core\components\ErrorLog;
use core\models\payment\PaidService;
use core\models\payment\ResourceType;
use core\models\payment\Service;
use core\models\payment\ServiceDuration;
use core\models\user\behaviors\UserPaidServiceBehavior;
use core\models\user\User;
use Throwable;
use Yii;
use yii\console\Controller;

class TestController extends Controller
{
    public function actionPlusOneLanding()
    {
        $transaction = Yii::$app->db->beginTransaction();

        try {

            $user = User::findOne(SUPERUSER_ID);
            $user->attachBehavior('UserPaidServiceBehavior', new UserPaidServiceBehavior());

            $serviceDuration = ServiceDuration::findOne([
                'serviceId' => Service::BASE_ACCESS,
                'duration' => ServiceDuration::THREE_MONTHS
            ]);
            $paidServiceCreateRes = PaidService::create([
                'userId' => $user->getId(),
                'serviceDurationId' => $serviceDuration->getId()
            ]);
            $paidService = PaidService::findOne($paidServiceCreateRes->getData('id'));
            $invoice = $paidService->getInvoice();
            $invoice->pay([
                'acquiringSystemId' => 1,
                'income' => $invoice->getPaymentAmount() * (1 - (2.9 / 100))
            ]);

            ErrorLog::log('has app access?', $user->hasAppAccess());
            ErrorLog::log('can create landings?', $user->canCreateLanding());
            ErrorLog::log('how many?', $user->getAllowedResourceAmount(ResourceType::LANDING));

            ErrorLog::log('adding one more landing');
            $serviceDuration = ServiceDuration::findOne([
                'serviceId' => Service::PLUS_ONE_LANDING,
                'duration' => ServiceDuration::THREE_MONTHS
            ]);
            $paidServiceCreateRes = PaidService::create([
                'userId' => $user->getId(),
                'serviceDurationId' => $serviceDuration->getId()
            ]);
            $paidService = PaidService::findOne($paidServiceCreateRes->getData('id'));
            $invoice = $paidService->getInvoice();

            ErrorLog::log('before invoice is paid');
            ErrorLog::log('has app access?', $user->hasAppAccess());
            ErrorLog::log('can create landings?', $user->canCreateLanding());
            ErrorLog::log('how many?', $user->getAllowedResourceAmount(ResourceType::LANDING));

            $invoice->pay([
                'acquiringSystemId' => 1,
                'income' => $invoice->getPaymentAmount() * (1 - (2.9 / 100))
            ]);

            ErrorLog::log('after invoice is paid');
            ErrorLog::log('has app access?', $user->hasAppAccess());
            ErrorLog::log('can create landings?', $user->canCreateLanding());
            ErrorLog::log('how many?', $user->getAllowedResourceAmount(ResourceType::LANDING));
        } catch (Throwable $t) {
            ErrorLog::log('error:', $t->getMessage(), $t->getTraceAsString());
        }

        $transaction->rollBack();
    }

    public function actionBaseAccess()
    {
        $transaction = Yii::$app->db->beginTransaction();

        try {

            $user = User::findOne(SUPERUSER_ID);
            $user->attachBehavior('UserPaidServiceBehavior', new UserPaidServiceBehavior());

            $serviceDuration = ServiceDuration::findOne([
                'serviceId' => Service::BASE_ACCESS,
                'duration' => ServiceDuration::THREE_MONTHS
            ]);
            $paidServiceCreateRes = PaidService::create([
                'userId' => $user->getId(),
                'serviceDurationId' => $serviceDuration->getId()
            ]);
            $paidService = PaidService::findOne($paidServiceCreateRes->getData('id'));
            $invoice = $paidService->getInvoice();
            $invoice->pay([
                'acquiringSystemId' => 1,
                'income' => $invoice->getPaymentAmount() * (1 - (2.9 / 100))
            ]);

            ErrorLog::log('has app access?', $user->hasAppAccess());
            ErrorLog::log('can create landings?', $user->canCreateLanding());
            ErrorLog::log('how many?', $user->getAllowedResourceAmount(ResourceType::LANDING));

            ErrorLog::log((new PaidServiceAllCollector())->setParam('userId', $user->getId())->get());
        } catch (Throwable $t) {
            ErrorLog::log('error:', $t->getMessage(), $t->getTraceAsString());
        }

        $transaction->rollBack();
    }

    public function actionDemoAccess()
    {
        $transaction = Yii::$app->db->beginTransaction();

        try {

            $user = User::findOne(SUPERUSER_ID);
            $user->attachBehavior('UserPaidServiceBehavior', new UserPaidServiceBehavior());

            $serviceDuration = ServiceDuration::findOne([
                'serviceId' => Service::DEMO_ACCESS,
                'duration' => ServiceDuration::TWO_WEEKS
            ]);
            $paidServiceCreateRes = PaidService::create([
                'userId' => $user->getId(),
                'serviceDurationId' => $serviceDuration->getId()
            ]);
            $paidService = PaidService::findOne($paidServiceCreateRes->getData('id'));
            $invoice = $paidService->getInvoice();
            $invoice->pay([
                'acquiringSystemId' => 1,
                'income' => 0
            ]);

            ErrorLog::log('has app access?', $user->hasAppAccess());
            ErrorLog::log('can create landings?', $user->canCreateLanding());
            ErrorLog::log('how many?', $user->getAllowedResourceAmount(ResourceType::LANDING));
        } catch (Throwable $t) {
            ErrorLog::log('error:', $t->getMessage(), $t->getTraceAsString());
        }

        $transaction->rollBack();
    }

    public function actionInvoicePay()
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
