<?php

namespace app\controllers\actions\auth;

use app\controllers\actions\ApiAction;;
use app\components\ExecutionResult;
use app\components\PasswordValidator;
use app\components\UserRegisterData;
use app\email\EmailQueueHandler;
use app\email\types\user\RegisterEmail;
use app\models\token\TokenGroupGenerator;
use app\models\user\User;
use Throwable;
use Yii;

class RegisterAction extends ApiAction
{
    public function run()
    {
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        $errors = [];

        $name = $data['name'];
        if (!$name) {
            $errors['name'] = 'Поле обязательно для заполнения';
        }

        $email = $data['email'];
        if (!$email) {
            $errors['email'] = 'Поле обязательно для заполнения';
        }

        $user = User::findOne(['email' => $email]);
        if ($user) {
            $errors['email'] = 'Пользователь с таким email уже существует';
        }

        $agreedToTerms = $data['agreedToTerms'];
        if (!$agreedToTerms) {
            $errors['agreedToTerms'] = 'Необходимо принять правила пользования сервисом';
        }

        $invitationHash = isset($data['hash']) ? $data['hash'] : null;

        $password = $data['password'];
        $repeatPassword = $data['repeatPassword'];
        $passwordValidator = new PasswordValidator([
            'password' => $password,
            'repeatPassword' => $repeatPassword,
        ]);
        if (!$passwordValidator->validate()) {
            $errors = array_merge($errors, $passwordValidator->getFirstErrors());
        }

        if ($errors) {
            return $this->apiResponse(new ExecutionResult(false, [], ['errors' => $errors]));
        }

        $transaction = Yii::$app->db->beginTransaction();
        $user = null;
        try {
            $res = User::register(new UserRegisterData($name, $email, $password, $invitationHash));
            $user = User::findOne($res->getData('id'));
            $res->isSuccessful() ? $transaction->commit() : $transaction->rollBack();
        } catch (Throwable $t) {
            $transaction->rollBack();
            return $this->apiResponse(new ExecutionResult(false, ['common' => $t->getMessage()]));
        }

        if (!$user) {
            return $this->apiResponse(new ExecutionResult(false, ['common' => 'Ошибка завершения регистрации']));
        }

        (new TokenGroupGenerator())->issueTokenGroup($user);

        (new EmailQueueHandler())
            ->setReceivers([$user])
            ->addEmail(new RegisterEmail($user))
            ->run();

        return $this->apiResponse(new ExecutionResult(true));
    }
}
