<?php

namespace api\controllers\actions\auth;

use api\controllers\actions\ApiAction;
use core\components\ExecutionResult;
use core\validators\LoginValidator;

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
