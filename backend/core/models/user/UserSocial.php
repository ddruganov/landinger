<?php

namespace core\models\user;

use core\components\CreatableInterface;
use core\components\ExecutionResult;
use core\components\ExtendedActiveRecord;
use core\components\helpers\DateHelper;

/**
 * This is the model class for table "user.user_social".
 *
 * @property int $id
 * @property string $userId
 * @property string $creationDate
 * @property string $typeId
 * @property string $value
 */
class UserSocial extends ExtendedActiveRecord implements CreatableInterface
{
    public static function tableName()
    {
        return 'user.user_social';
    }

    public function rules(): array
    {
        return [
            [['userId', 'typeId', 'value', 'creationDate'], 'required'],
            [['userId', 'typeId'], 'integer'],
            [['value', 'creationDate'], 'string']
        ];
    }

    public static function create(array $attributes): ExecutionResult
    {
        $user = new self([
            'userId' => $attributes['userId'],
            'creationDate' => DateHelper::now(),
            'typeId' => $attributes['typeId'],
            'value' => $attributes['value']
        ]);

        return new ExecutionResult($user->save(), $user->getFirstErrors(), ['id' => $user->id]);
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
