<?php

namespace core\models\landing;

use core\components\CreatableInterface;
use core\components\ExecutionResult;
use core\components\ExtendedActiveRecord;
use core\components\helpers\DateHelper;

/**
 * @property int $id
 * @property int $landingId
 * @property string $datetime
 * @property int $count
 */
class LandingVisit extends ExtendedActiveRecord implements CreatableInterface
{
    public static function tableName()
    {
        return 'landing.landing_visit';
    }

    public function rules()
    {
        return [
            [['landingId', 'datetime', 'count'], 'required'],
            [['landingId', 'count'], 'integer'],
            [['datetime'], 'string'],
            [['datetime'], 'date', 'format' => 'php:Y-m-d H:i:s'],
        ];
    }

    public static function create(array $attributes): ExecutionResult
    {
        $searchAttributes = [
            'landingId' => $attributes['landingId'],
            'datetime' => DateHelper::now('Y-m-d H:i:00')
        ];

        $model = self::findOne($searchAttributes) ?? new self($searchAttributes);
        $model->increment();

        return new ExecutionResult(
            $model->save(),
            $model->getFirstErrors(),
        );
    }

    public function increment(): static
    {
        $this->count ??= 0;
        $this->count++;
        return $this;
    }
}
