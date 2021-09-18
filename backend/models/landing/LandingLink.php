<?php

namespace app\models\landing;

use app\components\CreatableInterface;
use app\components\ExecutionResult;
use app\components\ExtendedActiveRecord;
use app\components\helpers\DateHelper;
use yii\db\Query;

/**
 * @property int $id
 * @property string $name
 * @property string $creationDate
 * @property int $creatorId
 * @property int $landingId
 * @property int $weight
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
            [['name', 'creation_date', 'creator_id', 'landing_id', 'weight'], 'required'],
            [['name', 'value'], 'string'],
            [['creation_date'], 'date', 'format' => 'php:Y-m-d H:i:s'],
            [['creator_id', 'landing_id', 'weight'], 'integer']
        ];
    }

    public static function create(array $attributes): ExecutionResult
    {
        $landingId = $attributes['landingId'];
        $weight =  ((new Query())
            ->select(['max(weight)'])
            ->from(self::tableName())
            ->where(['landing_id' => $landingId])
            ->scalar() ?: 0) + 10;

        $model = new self([
            'name' => 'Новая ссылка',
            'creationDate' => DateHelper::now(),
            'creatorId' => $attributes['userId'],
            'landingId' => $attributes['landingId'],
            'weight' => $weight
        ]);

        return new ExecutionResult(
            $model->save(),
            $model->getFirstErrors(),
            $model->getAttributes(['id', 'name', 'value'])
        );
    }
}
