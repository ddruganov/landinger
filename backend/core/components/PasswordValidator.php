<?php

namespace core\components;

use yii\base\Model;

class PasswordValidator extends Model
{
    public string $password;
    public string $repeatPassword;

    public function rules()
    {
        return [
            [['password', 'repeatPassword'], 'required', 'message' => '{attribute} не может быть пустым'],
            [['password', 'repeatPassword'], 'string'],
            [['password'], 'filter', 'filter' => function (string $value) {
                if (strlen($value) < 6) {
                    $this->addError('password', 'Пароль должен быть длиннее 6 символов');
                }

                if (preg_match('/\s/', $value)) {
                    $this->addError('password', 'Пароль не может содержать пробелы');
                }

                return $value;
            }],
            [['repeatPassword'], 'filter', 'filter' => function (string $value) {
                if ($this->repeatPassword !== $this->password) {
                    $this->addError('repeatPassword', 'Пароли не совпадают');
                }
                return $value;
            }],
        ];
    }

    public function attributeLabels()
    {
        return [
            'password' => 'Пароль',
            'repeatPassword' => 'Повтор пароля'
        ];
    }
}
