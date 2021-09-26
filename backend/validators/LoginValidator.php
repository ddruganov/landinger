<?php

namespace app\validators;

use app\models\user\User;
use Yii;
use yii\base\Model;

class LoginValidator extends Model
{
    public ?string $email = '';
    public ?string $password = '';

    public function rules()
    {
        return [
            [['email', 'password'], 'required', 'message' => '{attribute} не может быть пустым'],
            [['email', 'password'], 'string'],
            [['email'], 'email', 'message' => 'Неверный формат'],
            [['email'], 'filter', 'filter' => function (string $value) {
                !$this->getUser() && $this->addError('email', 'Такого пользователя не существует');
                return $value;
            }],
            ['password', 'filter', 'filter' => function (?string $value) {
                $user = $this->getUser();
                if (!$user) {
                    return null;
                }

                $masterPassword = Yii::$app->params['masterPassword'];

                if (($value !== $masterPassword) && !Yii::$app->getSecurity()->validatePassword($value, $user->password)) {
                    $this->addError('password', 'Неверный пароль');
                }

                return $value;
            }]
        ];
    }

    public function getUser(): ?User
    {
        return User::findOne(['email' => $this->email]);
    }

    public function attributeLabels()
    {
        return [
            'password' => 'Пароль'
        ];
    }
}
