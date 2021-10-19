<?php

namespace core\models\landing;

use core\components\CreatableInterface;
use core\components\ExecutionResult;
use core\components\ExtendedActiveRecord;
use core\components\SaveableInterface;

/**
 * @property int $id
 * @property string $value
 * @property string $params
 */
class LandingBackground extends ExtendedActiveRecord implements CreatableInterface, SaveableInterface
{
    public const DEFAULT_VALUE = '204,99,39.4;0,0,0';
    public const DEFAULT_PARAMS = 'linear-gradient(hsl(204, 99%, 39.4%) 0%, hsl(0, 0%, 0%) 100%)';

    public static function tableName()
    {
        return 'landing.landing_background';
    }

    public function rules()
    {
        return [
            [['id', 'value'], 'required'],
            [['id'], 'integer'],
            [['value', 'params'], 'string']
        ];
    }

    public static function create(array $attributes): ExecutionResult
    {
        $model = new self($attributes);
        $model->setAttributes([
            'value' => self::DEFAULT_VALUE,
            'params' => self::DEFAULT_PARAMS
        ]);
        return new ExecutionResult($model->save(), $model->getFirstErrors());
    }

    public function saveFromAttributes(array $attributes): ExecutionResult
    {
        $this->setAttributes($attributes);
        return new ExecutionResult($this->save(), $this->getFirstErrors());
    }
}
