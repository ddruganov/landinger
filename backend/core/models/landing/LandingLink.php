<?php

namespace core\models\landing;

use core\components\CreatableInterface;
use core\components\ExecutionResult;
use core\components\ExtendedActiveRecord;

/**
 * @property int $id
 * @property string $name
 * @property string $value
 */
class LandingLink extends ExtendedActiveRecord implements CreatableInterface
{
    public static function tableName()
    {
        return 'landing.landing_link';
    }

    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name', 'value'], 'string'],
        ];
    }

    public static function create(array $attributes): ExecutionResult
    {
        $model = new self([
            'id' => $attributes['id'],
            'name' => $attributes['name']
        ]);

        return new ExecutionResult(
            $model->save(),
            $model->getFirstErrors(),
            $model->getAttributes(['id', 'name', 'value'])
        );
    }
}
