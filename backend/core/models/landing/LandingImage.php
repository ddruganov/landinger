<?php

namespace core\models\landing;

use core\components\CreatableInterface;
use core\components\ExecutionResult;
use core\components\ExtendedActiveRecord;

/**
 * @property int $id
 * @property string $url
 */
class LandingImage extends ExtendedActiveRecord implements CreatableInterface
{
    public static function tableName()
    {
        return 'landing.landing_image';
    }

    public function rules()
    {
        return [
            [['url'], 'required'],
            [['url'], 'string'],
        ];
    }

    public static function create(array $attributes): ExecutionResult
    {
        $model = new self([
            'id' => $attributes['id'],
            'url' => 'https://picsum.photos/200'
        ]);

        return new ExecutionResult(
            $model->save(),
            $model->getFirstErrors()
        );
    }
}
