<?php

namespace core\models\payment;

use core\components\CreatableInterface;
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
        $userId = $attributes['userId'];
        $serviceDurationId = $attributes['serviceDurationId'];

        $similarServiceDurations = ServiceDuration::findSimilar($serviceDurationId);

        // check if such a paid service already exists
        $foundModels = self::find()
            ->alias('ps')
            ->where([
                'and',
                ['ps.user_id' => $userId],
                ['in', 'ps.service_duration_id', array_map(fn (ServiceDuration $serviceDuration) => $serviceDuration->getId(), $similarServiceDurations)],
                ['is', 'ps.expiration_date', null],
                ['is', 'i.payment_date', null],
                ['i.model_type_id' => ModelType::PAID_SERVICE]
            ])
            ->innerJoin(['i' => Invoice::tableName()], 'i.model_id = ps.id')
            ->all();

        foreach ($foundModels as $foundModel) {
            if ($foundModel->delete() === false) {
                return new ExecutionResult(false, $foundModel->getFirstErrors());
            }
        }

        $model = new self([
            'creationDate' => DateHelper::now(),
            'userId' => $userId,
            'serviceDurationId' => $serviceDurationId,
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

    public function delete()
    {
        $invoice = $this->getInvoice();
        if ($invoice->delete() === false) {
            $this->addErrors($invoice->getFirstErrors());
            return false;
        }

        return parent::delete();
    }

    public function onInvoicePaid(): ExecutionResult
    {
        $serviceDuration = $this->getServiceDuration();

        $this->setAttributes([
            'expirationDate' => DateHelper::addDays($serviceDuration->getDuration())
        ]);

        return new ExecutionResult($this->save(), $this->getFirstErrors());
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getExpirationDate(): ?string
    {
        return $this->expirationDate;
    }

    public function getServiceDurationId(): int
    {
        return $this->serviceDurationId;
    }

    public function getUser(): User
    {
        return User::findOne($this->getUserId());
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

    public function isPaid(): bool
    {
        return $this->getExpirationDate() && $this->getInvoice()->isPaid();
    }

    public function getName(): string
    {
        $serviceDuration = $this->getServiceDuration();
        $serviceName = $serviceDuration->getService()->getName();

        return $serviceName . ' (' . $serviceDuration->getDuration() . ' дней)';
    }
}
