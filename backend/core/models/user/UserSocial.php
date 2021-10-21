<?php

namespace core\models\user;

use core\components\ExtendedActiveRecord;

/**
 * This is the model class for table "user.user_social".
 *
 * @property int $id
 * @property string $userId
 * @property string $creationDate
 * @property string $typeId
 * @property string $value
 */
class UserSocial extends ExtendedActiveRecord
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

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }
}
