<?php

namespace app\controllers\actions\auth;

use app\actions\ApiAction;
use app\components\ErrorLog;
use app\components\ExecutionResult;
use app\models\token\TokenGroupGenerator;
use app\validators\LoginValidator;
use Throwable;

class LoginAction extends ApiAction
{
    public function run()
    {
        try {
            $loginValidator = new LoginValidator($this->getData());
            if (!$loginValidator->validate()) {
                return $this->apiResponse(new ExecutionResult(false, ['common' => 'Поля формы заполнены неверно'], ['errors' => $loginValidator->getFirstErrors()]));
            }

            $user = $loginValidator->getUser();

            $tokens = (new TokenGroupGenerator())->issueTokenGroup($user);
            if (!$tokens) {
                return $this->apiResponse(new ExecutionResult(false, ['common' => 'Ошибка выдачи токенов']));
            }

            return $this->apiResponse(new ExecutionResult($user->login()));
        } catch (Throwable $t) {
            ErrorLog::log($t->getMessage(), $t->getTraceAsString());
        }
    }
}
