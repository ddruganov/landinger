<?php

namespace app\models\landing;

use app\components\CreatableInterface;
use app\components\ExecutionResult;
use app\components\ExtendedActiveRecord;
use app\components\helpers\DateHelper;
use app\models\common\ModelType;
use yii\db\Query;

/**
 * @property int $id
 * @property string $creationDate
 * @property int $creatorId
 * @property int $parentId
 * @property int $landingId
 * @property int $modelTypeId
 * @property int $weight
 */
class LandingEntity extends ExtendedActiveRecord implements CreatableInterface
{
    public array $children = [];

    public static function tableName()
    {
        return 'landing.landing_entity';
    }

    public function rules()
    {
        return [
            [['creation_date', 'creator_id', 'landing_id', 'weight'], 'required'],
            [['creation_date'], 'date', 'format' => 'php:Y-m-d H:i:s'],
            [['creator_id', 'landing_id', 'weight'], 'integer'],
            [['children'], 'filter', 'filter' => [$this, 'saveChildren']],
        ];
    }

    public function saveChildren(array $children)
    {
        foreach ($children as $child) {
            $model = self::findOne($child['id']);
            $model->setAttributes($child);
            !$model->save() && $this->addError('children', @reset($model->getFirstErrors()));
        }

        return [];
    }

    public static function create(array $attributes): ExecutionResult
    {
        $landingId = $attributes['landingId'];
        $modelTypeId = $attributes['modelTypeId'];
        $weight =  ((new Query())
            ->select(['max(weight)'])
            ->from(self::tableName())
            ->where(['landing_id' => $landingId])
            ->scalar() ?: 0) + 10;

        $model = new self([
            'creationDate' => DateHelper::now(),
            'creatorId' => $attributes['userId'],
            'parentId' => $attributes['parentId'] ?? null,
            'landingId' => $attributes['landingId'],
            'modelTypeId' => $modelTypeId,
            'weight' => $weight
        ]);

        if (!$model->save()) {
            return new ExecutionResult(false, $model->getFirstErrors());
        }

        return $model->getBoundModelClass()::create([
            'id' => $model->id,
            'name' => 'Новая ' . ($modelTypeId === ModelType::LANDING_LINK_GROUP ? 'группа' : 'ссылка')
        ]);
    }

    public function delete()
    {
        $boundModel = $this->getBoundModel();
        if ($boundModel->delete() === false) {
            $this->addErrors($boundModel->getErrors());
            return false;
        }

        return parent::delete();
    }

    public function getBoundModelClass(): string
    {
        return ModelType::getModelClassById($this->modelTypeId);
    }

    public function getBoundModel(): LandingLink|LandingLinkGroup
    {
        return $this->getBoundModelClass()::findOne($this->id);
    }
}
