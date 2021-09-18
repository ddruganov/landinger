<?php

namespace app\models\landing;

use app\collectors\landing\LandingAllCollector;
use app\components\CreatableInterface;
use app\components\ExecutionResult;
use app\components\ExtendedActiveRecord;
use app\components\helpers\DateHelper;
use app\components\helpers\UserHelper;
use yii\db\Query;

/**
 * @property int $id
 * @property string $creationDate
 * @property int $creatorId
 * @property string $name
 * @property string $alias
 * @property int $backgroundId
 */
class Landing extends ExtendedActiveRecord implements CreatableInterface
{
    public array $links = [];

    public static function tableName()
    {
        return 'landing.landing';
    }

    public function rules()
    {
        return [
            [['creation_date', 'creator_id', 'name', 'alias', 'background_id'], 'required'],
            [['name', 'alias'], 'string'],
            [['creation_date'], 'date', 'format' => 'php:Y-m-d H:i:s'],
            [['creator_id', 'background_id'], 'integer'],
            [['links'], 'filter', 'filter' => function (array $value) {
                $current_model_ids = (new Query())
                    ->select(['id'])
                    ->from(LandingLink::tableName())
                    ->where(['landing_id' => $this->id])
                    ->column();

                foreach ($value as $link) {
                    $model = in_array($link['id'], $current_model_ids) ? LandingLink::findOne($link['id']) : new LandingLink([
                        'creator_id' => UserHelper::id(),
                        'creation_date' => DateHelper::now(),
                        'landing_id' => $this->id
                    ]);
                    $model->setAttributes($link);
                    if (!$model->save()) {
                        $this->addError('links', @reset($model->getFirstErrors()));
                    }
                }

                return [];
            }]
        ];
    }

    public static function create(array $attributes): ExecutionResult
    {
        $model = new self([
            'name' => 'Новый лендинг',
            'alias' => md5(microtime() . rand()),
            'creationDate' => DateHelper::now(),
            'creatorId' => $attributes['userId'],
            'backgroundId' => LandingBackground::NEUTRAL
        ]);

        return new ExecutionResult(
            $model->save(),
            $model->getFirstErrors(),
            @reset((new LandingAllCollector())
                ->setIds([$model->id])
                ->get())
        );
    }

    public function delete()
    {
        foreach ($this->getLinks() as $link) {
            if ($link->delete() === false) {
                $this->addErrors($link->getFirstErrors());
                return false;
            }
        }


        return parent::delete();
    }

    /**
     * @return LandingLink[]
     */
    public function getLinks(): array
    {
        return LandingLink::findAll(['landing_id' => $this->id]);
    }
}
