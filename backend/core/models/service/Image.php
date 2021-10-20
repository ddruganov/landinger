<?php

namespace core\models\service;

use core\components\ExtendedActiveRecord;
use Yii;

/**
 * @property int $id
 * @property string $creationDate
 * @property int $creatorId
 * @property int $size
 * @property string $hash
 * @property string $extension
 */
class Image extends ExtendedActiveRecord
{
    public static function tableName()
    {
        return 'service.image';
    }

    public function rules()
    {
        return [
            [['creationDate', 'creatorId', 'size', 'hash', 'extension'], 'required'],
            [['creationDate', 'hash', 'extension'], 'string'],
            [['creatorId', 'size'], 'integer'],
            [['creationDate'], 'date', 'format' => 'php:Y/m/d'],
        ];
    }

    public function getRelativePath()
    {
        return '/' . $this->creationDate . '/' . $this->hash . '.' . $this->extension;
    }

    public function getUrl(): string
    {
        if (!$this->id) {
            return Yii::$app->params['links']['service']['defaultImage'];
        }

        return Yii::$app->params['links']['service']['uploadFolder'] . $this->getRelativePath();
    }
}
