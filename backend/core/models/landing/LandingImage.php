<?php

namespace core\models\landing;

use core\components\CreatableInterface;
use core\components\ExecutionResult;
use core\components\ExtendedActiveRecord;
use core\components\SaveableInterface;
use core\models\service\Image;

/**
 * @property int $id
 * @property int $imageId
 */
class LandingImage extends ExtendedActiveRecord implements CreatableInterface, SaveableInterface, LandingEntityInterface
{
    public static function tableName()
    {
        return 'landing.landing_image';
    }

    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id', 'imageId'], 'integer'],
        ];
    }

    public function delete()
    {
        if (parent::delete() === false) {
            return false;
        }

        $image = Image::findOne($this->imageId);
        if ($image && $image->delete() === false) {
            $this->addErrors($image->getErrors());
            return false;
        }

        return true;
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
            'imageId' => $attributes['image']['id']
        ]);

        return new ExecutionResult($this->save(), $this->getFirstErrors());
    }

    public function getImage(): Image
    {
        return Image::findOne($this->imageId) ?? new Image();
    }

    public function getData(): array
    {
        $image = $this->getImage();

        return [
            'image' => [
                'id' => $image->id,
                'url' => $image->getUrl()
            ]
        ];
    }
}
