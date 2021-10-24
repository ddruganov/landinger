<?php

namespace core\models\payment;

use core\components\ExtendedActiveRecord;
use core\models\common\ModelType;
use core\models\user\User;

/**
 * @property int $id
 * @property string $expirationDate
 * @property int $userId
 * @property int $serviceDurationId
 */
class PaidService extends ExtendedActiveRecord
{
    public static function tableName()
    {
        return 'payment.paid_service';
    }

    public function rules()
    {
        return [
            [['expirationDate', ' userId', 'serviceDurationId'], 'required'],
            [['expirationDate'], 'string'],
            [['userId', 'serviceDurationId'], 'integer'],
        ];
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUser(): User
    {
        return User::findOne($this->userId);
    }

    public function getInvoice(): Invoice
    {
        return Invoice::findOne([
            'modelId' => $this->getId(),
            'modelTypeId' => ModelType::PAID_SERVICE
        ]);
    }
}
