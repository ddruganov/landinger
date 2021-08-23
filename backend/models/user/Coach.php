<?php

namespace app\models\user;

use app\components\ExecutionResult;
use app\components\ExtendedActiveRecord;
use app\components\helpers\DateHelper;
use app\components\SaveableInterface;
use app\components\toggle\ToggleableInterface;

/**
 * This is the model class for table "user.coach".
 *
 * @property int $id
 * @property int $organizationUserId
 * @property int $activityId
 * @property float $payRate
 * @property bool $active
 * @property bool $deleted
 * @property string $creationDate
 */
class Coach extends ExtendedActiveRecord implements SaveableInterface, ToggleableInterface
{
    public static function tableName()
    {
        return 'user.coach';
    }

    public function rules()
    {
        return [
            [['organization_user_id', 'activity_id', 'pay_rate', 'active', 'deleted', 'creation_date'], 'required'],
            [['organization_user_id', 'activity_id'], 'integer'],
            [['pay_rate'], 'number'],
            [['active', 'deleted'], 'boolean'],
            [['creation_date'], 'string']
        ];
    }

    public function delete()
    {
        $this->deleted = true;
        return $this->save();
    }

    public function toggle(): ToggleableInterface
    {
        $this->active = !$this->active;
        return $this;
    }

    #region SaveableInterface
    public static function saveWithAttributes(array $attributes): ExecutionResult
    {
        $model = null;
        if (isset($attributes['id'])) {
            $model = self::findOne($attributes['id']);
        }
        $model ??= new self();

        $model->setAttributes($attributes);
        $model->isNewRecord && $model->setAttributes([
            'active' => true,
            'deleted' => false,
            'creationDate' => DateHelper::now()
        ]);

        return new ExecutionResult($model->save(), $model->getFirstErrors());
    }
    #endregion
}
