<?php

namespace app\models\landing;

use app\collectors\landing\LandingAllCollector;
use app\components\CreatableInterface;
use app\components\ExecutionResult;
use app\components\ExtendedActiveRecord;
use app\components\helpers\DateHelper;

/**
 * @property int $id
 * @property string $creationDate
 * @property int $creatorId
 * @property string $name
 * @property string $alias
 */
class Landing extends ExtendedActiveRecord implements CreatableInterface
{
    public array $entities = [];
    public array $background = [];

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
            [['entities'], 'filter', 'filter' => [$this, 'saveEntities']],
            [['background'], 'filter', 'filter' => [$this, 'saveBackground']]
        ];
    }

    public function saveEntities(array $entities)
    {
        foreach ($entities as $entity) {
            $model = LandingEntity::findOne($entity['id']);
            $model->setAttributes($entity);
            !$model->save() && $this->addError('entities', @reset($model->getFirstErrors()));
        }

        return [];
    }

    public function saveBackground()
    {
        $background = LandingBackground::findOne($this->id) ?? new LandingBackground(['id' => $this->id]);
        $background->setAttributes([
            'value' => $this->background['value'],
            'params' => $this->background['params'] ?? ''
        ]);
        if (!$background->save()) {
            $this->addError('background', @reset($background->getFirstErrors()));
        }

        return [];
    }

    public static function create(array $attributes): ExecutionResult
    {
        $model = new self([
            'name' => 'Новый лендинг',
            'alias' => md5(microtime() . rand()),
            'creationDate' => DateHelper::now(),
            'creatorId' => $attributes['userId'],
            'background' => [
                'value' => LandingBackground::DEFAULT_VALUE,
                'params' => LandingBackground::DEFAULT_PARAMS
            ]
        ]);

        return new ExecutionResult(
            $model->save(),
            $model->getFirstErrors(),
            (new LandingAllCollector())
                ->setParam('ids', [$model->id])
                ->one()
        );
    }

    public function delete()
    {
        foreach ($this->getEntities() as $entity) {
            if ($entity->delete() === false) {
                $this->addErrors($entity->getFirstErrors());
                return false;
            }
        }


        return parent::delete();
    }

    /**
     * @return LandingEntity[]
     */
    public function getEntities(): array
    {
        return LandingEntity::findAll(['landing_id' => $this->id]);
    }
}
