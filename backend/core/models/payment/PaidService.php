<?php

namespace core\models\payment;

use core\components\CreatableInterface;
use core\components\ErrorLog;
use core\components\ExecutionResult;
use core\components\ExtendedActiveRecord;
use core\components\helpers\DateHelper;
use core\models\common\ModelType;
use core\models\user\User;

/**
 * @property int $id
 * @property string $creationDate
 * @property string $expirationDate
 * @property int $userId
 * @property int $serviceDurationId
 */
class PaidService extends ExtendedActiveRecord implements CreatableInterface, InvoiceBoundModelInterface
{
    public static function tableName()
    {
        return 'payment.paid_service';
    }

    public function rules()
    {
        return [
            [['creationDate', 'userId', 'serviceDurationId'], 'required'],
            [['creationDate', 'expirationDate'], 'string'],
            [['creationDate', 'expirationDate'], 'date', 'format' => 'php:Y-m-d H:i:s'],
            [['userId', 'serviceDurationId'], 'integer'],
        ];
    }

    public static function create(array $attributes): ExecutionResult
    {
        $model = new self([
            'creationDate' => DateHelper::now(),
            'userId' => $attributes['userId'],
            'serviceDurationId' => $attributes['serviceDurationId'],
        ]);

        if (!$model->save()) {
            return new ExecutionResult(false, $model->getFirstErrors());
        }

        $serviceDuration = $model->getServiceDuration();

        $invoiceCreateRes = Invoice::create([
            'userId' => $model->getUserId(),
            'modelId' => $model->getId(),
            'modelTypeId' => ModelType::PAID_SERVICE,
            'paymentAmount' => $serviceDuration->getPrice()
        ]);

        return $invoiceCreateRes->appendData(['id' => $model->getId()]);
    }

    public function onInvoicePaid(): bool
    {
        $serviceDuration = $this->getServiceDuration();

        $this->setAttributes([
            'expirationDate' => DateHelper::addDays($serviceDuration->getDuration())
        ]);

        return $this->save();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getUser(): User
    {
        return User::findOne($this->getUserId());
    }

    public function getServiceDurationId(): int
    {
        return $this->serviceDurationId;
    }

    public function getInvoice(): Invoice
    {
        return Invoice::findOne([
            'modelId' => $this->getId(),
            'modelTypeId' => ModelType::PAID_SERVICE
        ]);
    }

    public function getServiceDuration(): ServiceDuration
    {
        return ServiceDuration::findOne($this->getServiceDurationId());
    }
}
