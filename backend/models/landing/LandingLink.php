<?php

namespace app\models\landing;

use app\components\CreatableInterface;
use app\components\ExecutionResult;
use app\components\ExtendedActiveRecord;
use app\components\helpers\DateHelper;

class LandingLink extends ExtendedActiveRecord implements CreatableInterface
{
    public static function tableName()
    {
        return 'landing.landing_link';
    }

    public function rules()
    {
        return [
            [['name', 'creation_date', 'creator_id', 'landing_id'], 'required'],
            [['name', 'value'], 'string'],
            [['creation_date'], 'date', 'format' => 'php:Y-m-d H:i:s'],
            [['creator_id', 'landing_id'], 'integer']
        ];
    }

    public static function create(array $attributes): ExecutionResult
    {
        $model = new self([
            'name' => 'Новая ссылка',
            'creationDate' => DateHelper::now(),
            'creatorId' => $attributes['userId'],
            'landingId' => $attributes['landingId'],
        ]);

        return new ExecutionResult(
            $model->save(),
            $model->getFirstErrors(),
            $model->getAttributes(['id', 'name', 'value'])
        );
    }
}
