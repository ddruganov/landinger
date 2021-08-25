<?php

namespace app\models\landing;

use app\components\ExecutionResult;
use app\components\ExtendedActiveRecord;
use app\components\helpers\DateHelper;
use app\components\SaveableInterface;

/**
 * @var int $id
 * @var string $creation_date
 * @var int $creator_id
 * @var string $name
 * @var string $alias
 */
class Landing extends ExtendedActiveRecord implements SaveableInterface
{
    public static function tableName()
    {
        return 'landing.landing';
    }

    public function rules()
    {
        return [
            [['creation_date', 'creator_id', 'name', 'alias'], 'required'],
            [['name', 'alias'], 'string'],
            [['creation_date'], 'date', 'format' => 'php:Y-m-d H:i:s'],
            [['creator_id'], 'integer'],
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

        return new ExecutionResult($model->save(), $model->getFirstErrors(), ['id' => $model->id]);
    }
}
