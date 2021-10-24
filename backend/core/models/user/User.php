<?php

namespace core\models\user;

use core\components\CreatableInterface;
use core\components\ExecutionResult;
use core\components\ExtendedActiveRecord;
use core\components\helpers\CookieHelper;
use core\components\helpers\DateHelper;
use core\components\SaveableInterface;
use core\models\service\Image;
use core\models\token\TokenGroupGenerator;
use Yii;

/**
 * This is the model class for table "user.user".
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $creationDate
 * @property int $imageId
 */
class User extends ExtendedActiveRecord implements CreatableInterface, SaveableInterface
{
    public static function tableName()
    {
        return 'user.user';
    }

    public function rules()
    {
        return [
            [['name', 'email', 'creationDate', 'password'], 'required'],
            [['name', 'email', 'creationDate', 'password'], 'string'],
            [['creationDate'], 'date', 'format' => 'php:Y-m-d H:i:s'],
            [['imageId'], 'integer']
        ];
    }

    public static function create(array $attributes): ExecutionResult
    {
        $user = new self([
            'name' => $attributes['name'],
            'email' => $attributes['email'],
            'password' => Yii::$app->getSecurity()->generatePasswordHash(md5(microtime())),
            'creationDate' => DateHelper::now()
        ]);

        if (!$user->save()) {
            return new ExecutionResult(false, $user->getFirstErrors());
        }

        // bind free paid service

        return new ExecutionResult(true, [], ['id' => $user->id]);
    }

    public function saveFromAttributes(array $attributes): ExecutionResult
    {
        $this->setAttributes([
            'imageId' => $attributes['image']['id']
        ]);

        return new ExecutionResult($this->save(), $this->getFirstErrors());
    }

    public function login(?array $tokens = null): bool
    {
        $tokens ??= (new TokenGroupGenerator())->issueTokenGroup($this);
        if (!$tokens) {
            return false;
        }

        CookieHelper::setCookie(TokenGroupGenerator::ACCESS_TOKEN_NAME, $tokens['access']['token'], $tokens['access']['expirationDate']);
        CookieHelper::setCookie(TokenGroupGenerator::REFRESH_TOKEN_NAME, $tokens['refresh']['token'], $tokens['refresh']['expirationDate']);

        return true;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getImage(): Image
    {
        return Image::findOne($this->imageId) ?? new Image();
    }
}
