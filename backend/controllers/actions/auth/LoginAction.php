<?php

namespace app\controllers\actions\auth;

use app\controllers\actions\ApiAction;;
use app\components\ErrorLog;
use app\components\ExecutionResult;
use app\models\token\TokenGroupGenerator;
use app\validators\LoginValidator;
use Yii;

class LoginAction extends ApiAction
{
    public function run()
    {
        $loginValidator = new LoginValidator($this->getData());
        if (!$loginValidator->validate()) {
            return $this->apiResponse(new ExecutionResult(false, ['exception' => 'Поля формы заполнены неверно'], ['errors' => $loginValidator->getFirstErrors()]));
        }

        $user = $loginValidator->getUser();

        $tokens = (new TokenGroupGenerator())->issueTokenGroup($user);
        if (!$tokens) {
            return $this->apiResponse(new ExecutionResult(false, ['exception' => 'Ошибка выдачи токенов']));
        }

        return $this->apiResponse(new ExecutionResult($user->login()));
    }
}
