<?php

namespace core\models\landing;

use core\collectors\landing\LandingEntityCollector;
use core\components\CreatableInterface;
use core\components\ExecutionResult;
use core\components\ExtendedActiveRecord;
use core\components\helpers\DateHelper;
use core\components\SaveableInterface;
use core\models\common\ModelType;
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
class LandingEntity extends ExtendedActiveRecord implements CreatableInterface, SaveableInterface
{
    public array $children = [];

    public static function tableName()
    {
        return 'landing.landing_entity';
    }

    public function rules()
    {
        return [
            [['creationDate', 'creatorId', 'landingId', 'modelTypeId', 'weight'], 'required'],
            [['creationDate'], 'date', 'format' => 'php:Y-m-d H:i:s'],
            [['creatorId', 'landingId', 'modelTypeId', 'weight', 'parentId'], 'integer'],
            [['children'], 'filter', 'filter' => [$this, 'saveChildren']],
        ];
    }

    public function saveChildren(array $children)
    {
        foreach ($children as $child) {
            $model = self::findOne($child['id']);
            $model->saveAttributes($child);
            !$model->save() && $this->addError('children', @reset($model->getFirstErrors()));
        }

        return [];
    }

    public static function create(array $attributes): ExecutionResult
    {
        $landingId = $attributes['landingId'];
        $modelTypeId = $attributes['modelTypeId'];
        $weight = ((new Query())
            ->select(['max(weight)'])
            ->from(self::tableName())
            ->where(['landing_id' => $landingId])
            ->scalar() ?: 0) + 10;

        $model = new self([
            'creationDate' => DateHelper::now(),
            'creatorId' => $attributes['userId'],
            'parentId' => $attributes['parentId'] ?? null,
            'landingId' => $landingId,
            'modelTypeId' => $modelTypeId,
            'weight' => $weight
        ]);

        if (!$model->save()) {
            return new ExecutionResult(false, $model->getFirstErrors());
        }

        $boundModelCreateRes = $model->getBoundModelClass()::create([
            'id' => $model->id
        ]);
        if (!$boundModelCreateRes->isSuccessful()) {
            return $boundModelCreateRes;
        }

        $entityData = (new LandingEntityCollector())
            ->setParams([
                'landingId' => $landingId,
                'ids' => [$model->id]
            ])
            ->one();

        return new ExecutionResult(true, [], $entityData);
    }

    public function saveFromAttributes(array $attributes): ExecutionResult
    {
        unset($attributes['id']);
        $entityKeys = array_flip(['creationDate', 'creatorId', 'parentId', 'landingId', 'modelTypeId', 'weight']);

        $this->setAttributes(array_intersect_key($attributes, $entityKeys));

        if (!$this->save()) {
            return new ExecutionResult(false, $this->getFirstErrors());
        }

        return $this->getBoundModel()->saveFromAttributes(array_diff_key($attributes, $entityKeys));
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

    public function getBoundModel(): LandingLink|LandingLinkGroup|LandingImage
    {
        return $this->getBoundModelClass()::findOne($this->id);
    }
}
