<?php

namespace core\models\landing;

use core\components\CreatableInterface;
use core\components\ExecutionResult;
use core\components\ExtendedActiveRecord;
use core\components\SaveableInterface;

/**
 * @property int $id
 * @property string $content
 */
class LandingText extends ExtendedActiveRecord implements CreatableInterface, SaveableInterface, LandingEntityInterface
{
    public static function tableName()
    {
        return 'landing.landing_text';
    }

    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id'], 'integer'],
            [['content'], 'string'],
        ];
    }

    public static function create(array $attributes): ExecutionResult
    {
        $model = new self([
            'id' => $attributes['id'],
        ]);

        return new ExecutionResult(
            $model->save(),
            $model->getFirstErrors()
        );
    }

    public function saveFromAttributes(array $attributes): ExecutionResult
    {
        $this->setAttributes([
            'content' => $attributes['content'],
        ]);

        return new ExecutionResult($this->save(), $this->getFirstErrors());
    }

    public function getData(): array
    {
        return [
            'content' => $this->content,
        ];
    }
}
