<?php

namespace app\models\user;

use app\components\ExecutionResult;
use app\components\ExtendedActiveRecord;
use app\components\helpers\CookieHelper;
use app\components\helpers\DateHelper;
use app\components\SaveableInterface;
use app\components\UserRegisterData;
use app\models\common\ModelType;
use app\models\token\TokenGroupGenerator;
use Yii;

/**
 * This is the model class for table "user.user".
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $creationDate
 */
class User extends ExtendedActiveRecord implements SaveableInterface
{
    public static function tableName()
    {
        return 'user.user';
    }

    public function rules()
    {
        return [
            [['name', 'email', 'password', 'creation_date'], 'required'],
            [['name', 'email', 'password', 'creation_date'], 'string'],
            [['password'], 'string', 'max' => 64],
        ];
    }

    #region SaveableInterface
    public static function saveWithAttributes(array $attributes): ExecutionResult
    {
        $model = isset($attributes['id']) ? self::findOne($attributes['id']) : new self();
        $model->setAttributes([
            'name' => $attributes['name'],
            'email' => $attributes['email']
        ]);
        $model->isNewRecord && $model->setAttributes([
            'creationDate' => DateHelper::now()
        ]);

        return new ExecutionResult($model->save(), $model->getFirstErrors());
    }
    #endregion

    public static function register(UserRegisterData $data): ExecutionResult
    {
        $user = new self();
        $user->setAttributes([
            'name' => $data->getName(),
            'email' => $data->getEmail(),
            'password' => Yii::$app->security->generatePasswordHash($data->getPassword()),
            'creationDate' => DateHelper::now()
        ]);

        return new ExecutionResult($user->save(), $user->getFirstErrors(), ['id' => $user->id]);
    }

    public function login(): bool
    {
        $tokens = (new TokenGroupGenerator())->issueTokenGroup($this);
        if (!$tokens) {
            return false;
        }

        CookieHelper::setCookie(TokenGroupGenerator::ACCESS_TOKEN_NAME, $tokens['access']['token'], $tokens['access']['expirationDate']);
        CookieHelper::setCookie(TokenGroupGenerator::REFRESH_TOKEN_NAME, $tokens['refresh']['token'], $tokens['refresh']['expirationDate']);

        return true;
    }
}
