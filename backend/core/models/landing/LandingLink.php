<?php

namespace core\models\landing;

use core\components\CreatableInterface;
use core\components\ExecutionResult;

/**
 * @property int $id
 * @property string $name
 * @property string $value
 */
class LandingLink extends LandingEntity implements CreatableInterface
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
            [['value'], 'filter', 'filter' => function (string $value) {

                if (!parse_url($value, PHP_URL_SCHEME)) {
                    $this->addError('value', 'Ссылка должна начинаться с http:// или https://');
                }

                return $value;
            }]
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
