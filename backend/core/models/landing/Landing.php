<?php

namespace core\models\landing;

use core\collectors\landing\LandingAllCollector;
use core\components\CreatableInterface;
use core\components\ExecutionResult;
use core\components\ExtendedActiveRecord;
use core\components\helpers\DateHelper;
use core\components\SaveableInterface;
use core\models\service\Image;
use core\models\user\behaviors\UserLandingBehavior;
use core\models\user\User;

/**
 * @property int $id
 * @property string $creationDate
 * @property int $creatorId
 * @property string $name
 * @property string $alias
 * @property int $imageId
 */
class Landing extends ExtendedActiveRecord implements CreatableInterface, SaveableInterface
{
    public static function tableName()
    {
        return 'landing.landing';
    }

    public function rules()
    {
        return [
            [['creationDate', 'creatorId', 'name', 'alias'], 'required'],
            [['name', 'alias'], 'string'],
            [['creationDate'], 'date', 'format' => 'php:Y-m-d H:i:s'],
            [['creatorId', 'imageId'], 'integer'],
        ];
    }

    public static function create(array $attributes): ExecutionResult
    {
        $user = User::findOne($attributes['userId']);
        $user->attachBehavior('UserLandingBehavior', new UserLandingBehavior());
        if (!$user->canCreateLanding()) {
            return new ExecutionResult(false, ['exception' => 'В бета-версии можно создать только один лендинг']);
        }

        $model = new self([
            'name' => 'Новый лендинг',
            'alias' => md5(microtime() . rand()),
            'creationDate' => DateHelper::now(),
            'creatorId' => $attributes['userId'],
        ]);

        if (!$model->save()) {
            return new ExecutionResult(false, $model->getFirstErrors());
        }

        $backgroundCreateRes = LandingBackground::create([
            'id' => $model->id
        ]);
        if (!$backgroundCreateRes->isSuccessful()) {
            return $backgroundCreateRes;
        }

        return new ExecutionResult(
            true,
            [],
            (new LandingAllCollector())
                ->setParam('ids', [$model->id])
                ->one()
        );
    }

    public function saveFromAttributes(array $attributes): ExecutionResult
    {
        $background = $attributes['background'];
        $entities = $attributes['entities'];
        unset($attributes['background'], $attributes['entities']);

        $this->setAttributes([
            'name' => $attributes['name'],
            'alias' => $attributes['alias'],
            'imageId' => $attributes['image']['id']
        ]);
        if (!$this->save()) {
            return new ExecutionResult(false, $this->getFirstErrors());
        }

        $backgroundSaveRes = $this->getBackground()->saveFromAttributes($background);
        if (!$backgroundSaveRes->isSuccessful()) {
            return $backgroundSaveRes;
        }

        foreach ($entities as $entity) {
            $entitySaveRes = LandingEntity::findOne($entity['id'])->saveFromAttributes($entity);
            if (!$entitySaveRes->isSuccessful()) {
                return $entitySaveRes;
            }
        }

        return new ExecutionResult(true);
    }

    public function delete()
    {
        foreach ($this->getEntities() as $entity) {
            if ($entity->delete() === false) {
                $this->addErrors($entity->getFirstErrors());
                return false;
            }
        }

        $background = LandingBackground::findOne($this->id);
        if ($background->delete() === false) {
            $this->addErrors($background->getFirstErrors());
            return false;
        }

        return parent::delete();
    }

    public function getBackground(): LandingBackground
    {
        return LandingBackground::findOne($this->id);
    }

    /**
     * @return LandingEntity[]
     */
    public function getEntities(): array
    {
        return LandingEntity::findAll(['landing_id' => $this->id]);
    }

    public function getImage(): Image
    {
        return Image::findOne($this->imageId) ?? new Image();
    }
}
