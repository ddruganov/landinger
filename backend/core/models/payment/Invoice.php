<?php

namespace core\models\payment;

use core\components\CreatableInterface;
use core\components\ExecutionResult;
use core\components\ExtendedActiveRecord;
use core\components\helpers\DateHelper;
use core\models\common\ModelType;

/**
 * @property int $id
 * @property string $creationDate
 * @property int $userId
 * @property int $modelId
 * @property int $modelTypeId
 * @property string|null $paymentDate
 * @property int|null $acquiringSystemId
 * @property float $paymentAmount
 * @property float|null $income
 */
class Invoice extends ExtendedActiveRecord implements CreatableInterface
{
    public static function tableName()
    {
        return 'payment.invoice';
    }

    public function rules()
    {
        return [
            [['creationDate', 'userId', 'modelId', 'modelTypeId', 'paymentAmount'], 'required'],
            [['creationDate', 'paymentDate'], 'string'],
            [['userId', 'modelId', 'modelTypeId', 'acquiringSystemId'], 'integer'],
            [['creationDate', 'paymentDate'], 'date', 'format' => 'php:Y-m-d H:i:s'],
            [['paymentAmount', 'income'], 'number']
        ];
    }

    public static function create(array $attributes): ExecutionResult
    {
        $model = new self([
            'creationDate' => DateHelper::now(),
            'userId' => $attributes['userId'],
            'modelId' => $attributes['modelId'],
            'modelTypeId' => $attributes['modelTypeId'],
            'paymentAmount' => $attributes['paymentAmount']
        ]);

        return new ExecutionResult($model->save(), $model->getFirstErrors());
    }

    public function pay(array $attributes): ExecutionResult
    {
        $acquiringSystemId = $attributes['acquiringSystemId'] ?? null;
        $income = $attributes['income'] ?? null;

        if (!$acquiringSystemId || !$income) {
            return new ExecutionResult(false, ['exception' => 'Отсутствуют входные данные для оплаты счёта']);
        }

        $this->setAttributes([
            'paymentDate' => DateHelper::now(),
            'acquiringSystemId' => $acquiringSystemId,
            'income' => $income
        ]);

        if (!$this->save()) {
            return new ExecutionResult(false, $this->getFirstErrors());
        }

        return $this->getBoundModel()->onInvoicePaid();
    }

    public function getBoundModel(): InvoiceBoundModelInterface
    {
        return ModelType::getModelClassById($this->getModelTypeId())::findOne($this->getModelId());
    }

    public function getModelTypeId(): int
    {
        return $this->modelTypeId;
    }

    public function getModelId(): int
    {
        return $this->modelId;
    }

    public function getPaymentDate(): ?string
    {
        return $this->paymentDate;
    }

    public function getAcquiringSystemId(): ?int
    {
        return $this->acquiringSystemId;
    }

    public function getPaymentAmount(): float
    {
        return $this->paymentAmount;
    }

    public function isPaid(): bool
    {
        return $this->getPaymentDate() && $this->getAcquiringSystemId();
    }
}