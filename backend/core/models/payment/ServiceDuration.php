<?php

namespace core\models\payment;

use core\components\ExtendedActiveRecord;

/**
 * @property int $id
 * @property int $serviceId
 * @property int $duration
 * @property int $price
 */
class ServiceDuration extends ExtendedActiveRecord
{
    public const TWO_WEEKS = 14;
    public const MONTH = 30;
    public const THREE_MONTHS = 3 * self::MONTH;

    public static function tableName()
    {
        return 'payment.service_duration';
    }

    public function rules()
    {
        return [
            [['serviceId', 'duration', 'price'], 'required'],
            [['serviceId', 'duration', 'price'], 'integer'],
        ];
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getServiceId(): int
    {
        return $this->serviceId;
    }

    public function getDuration(): int
    {
        return $this->duration;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function getService(): Service
    {
        return Service::findOne($this->getServiceId());
    }

    /**
     * @return ServiceDuration[]
     */
    public static function findSimilar(int $serviceDurationId): array
    {
        $serviceDuration = self::findOne($serviceDurationId);

        return self::findAll([
            'serviceId' => $serviceDuration->getServiceId()
        ]);
    }
}
