<?php

namespace core\models\landing;

use core\components\CreatableInterface;
use core\components\ExecutionResult;
use core\components\ExtendedActiveRecord;
use core\components\SaveableInterface;

/**
 * @property int $id
 * @property string $url
 */
class LandingImage extends ExtendedActiveRecord implements CreatableInterface, SaveableInterface
{
    public const DEFAULT_URL = 'https://picsum.photos/200';

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
            'url' => self::DEFAULT_URL
        ]);

        return new ExecutionResult(
            $model->save(),
            $model->getFirstErrors()
        );
    }

    public function saveFromAttributes(array $attributes): ExecutionResult
    {
        $this->setAttributes([
            'url' => $attributes['url'],
        ]);

        return new ExecutionResult($this->save(), $this->getFirstErrors());
    }
}
