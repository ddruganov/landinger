<?php

namespace core\models\landing;

use core\components\CreatableInterface;
use core\components\ExecutionResult;
use core\components\ExtendedActiveRecord;
use core\components\SaveableInterface;

/**
 * @property int $id
 * @property string $name
 * @property string $value
 */
class LandingLink extends ExtendedActiveRecord implements CreatableInterface, SaveableInterface
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
            'name' => 'Новая ссылка',
            'value' => 'https://example.com'
        ]);

        return new ExecutionResult(
            $model->save(),
            $model->getFirstErrors()
        );
    }

    public function saveFromAttributes(array $attributes): ExecutionResult
    {
        $this->setAttributes([
            'name' => $attributes['name'],
            'value' => $attributes['value'],
        ]);

        return new ExecutionResult($this->save(), $this->getFirstErrors());
    }
}
