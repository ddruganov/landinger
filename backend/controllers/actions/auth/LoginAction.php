<?php

namespace app\controllers\actions\auth;

use app\controllers\actions\ApiAction;
use app\components\ExecutionResult;
use app\validators\LoginValidator;

class LoginAction extends ApiAction
{
    public function run()
    {
        $loginValidator = new LoginValidator($this->getData());
        if (!$loginValidator->validate()) {
            return $this->apiResponse(new ExecutionResult(false, ['exception' => 'Поля формы заполнены неверно'], ['errors' => $loginValidator->getFirstErrors()]));
        }

        return $this->apiResponse(new ExecutionResult($loginValidator->getUser()->login()));
    }
}
