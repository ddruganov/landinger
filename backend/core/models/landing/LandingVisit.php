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
 * @property string $ip
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
            [['landing_id', 'datetime'], 'required'],
            [['landing_id'], 'integer'],
            [['datetime', 'ip'], 'string'],
            [['datetime'], 'date', 'format' => 'php:Y-m-d H:i:s'],
            [['ip'], 'ip']
        ];
    }

    public static function create(array $attributes): ExecutionResult
    {
        $model = new self([
            'landingId' => $attributes['landingId'],
            'datetime' => DateHelper::now(),
            'ip' => $attributes['ip']
        ]);

        return new ExecutionResult(
            $model->save(),
            $model->getFirstErrors(),
        );
    }
}
