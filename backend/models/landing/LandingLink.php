<?php

namespace app\models\landing;

use app\components\ExecutionResult;
use app\components\ExtendedActiveRecord;
use app\components\SaveableInterface;

class LandingLink extends ExtendedActiveRecord implements SaveableInterface
{
    public function rules()
    {
        return [
            [['name', 'value', 'creation_date', 'creator_id'], 'required'],
            [['name', 'value'], 'string'],
            [['creation_date'], 'date', 'format' => 'php:Y-m-d H:i:s'],
            [['creator_id'], 'integer']
        ];
    }

    public static function saveWithAttributes(array $attributes): ExecutionResult
    {
        $model = null;
        if ($id = $attributes['id'] ?? null) {
            $model = self::find($id);
        }
        $model ??= new self();

        $model->setAttributes($attributes);

        return new ExecutionResult(true);
    }
}
