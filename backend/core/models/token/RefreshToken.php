<?php

namespace core\models\token;

use core\components\ExtendedActiveRecord;
use core\components\helpers\DateHelper;

/**
 * This is the model class for table "token.refresh_token".
 *
 * @property int $id
 * @property int $userId
 * @property string $issueDate
 * @property string $expirationDate
 * @property string $value
 */
class RefreshToken extends ExtendedActiveRecord
{
    public static function tableName()
    {
        return 'token.refresh_token';
    }

    public function rules()
    {
        return [
            [['userId', 'issueDate', 'expirationDate', 'value'], 'required'],
            [['userId'], 'integer'],
            [['issueDate', 'expirationDate'], 'date', 'format' => 'php:Y-m-d H:i:s'],
            [['value'], 'string'],
        ];
    }

    public function void(): bool
    {
        $this->expirationDate = DateHelper::datetimeAsString('Y-m-d H:i:s', strtotime('-1 second'));
        return $this->save();
    }

    public function voidCurrentTokens(): void
    {
        $list =
            self::find()
            ->where([
                'and',
                ['user_id' => $this->userId],
                ['>', 'expiration_date', DateHelper::now()]
            ])
            ->all();

        array_walk($list, fn ($model) => $model->void());
    }

    public function isExpired(): bool
    {
        return $this->expirationDate < DateHelper::now();
    }
}
