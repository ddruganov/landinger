<?php

namespace api\controllers\actions\auth;

use api\controllers\actions\ApiAction;;
use core\components\ExecutionResult;
use core\models\token\TokenHelper;
use core\models\user\User;

class LogoutAction extends ApiAction
{
    public function run()
    {
        if ($accessToken = (new TokenHelper(new User()))->getAccessTokenFromCookies()) {
            $accessToken->onLogout();
            return $this->apiResponse(new ExecutionResult(true));
        }

        return $this->apiResponse(new ExecutionResult(false, ['exception' => 'Неизвестная  ошибка']));
    }
}
