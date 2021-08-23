<?php

namespace api\controllers\actions\auth;

use app\actions\ApiAction;
use app\components\ExecutionResult;
use app\models\token\TokenHelper;
use app\models\user\User;

class LogoutAction extends ApiAction
{
    public function run()
    {
        if ($accessToken = (new TokenHelper(new User()))->getAccessTokenFromCookies()) {
            $accessToken->onLogout();
            return $this->apiResponse(new ExecutionResult(true));
        }

        return $this->apiResponse(new ExecutionResult(false, ['common' => 'Неизвестная  ошибка']));
    }
}
