<?php

namespace app\controllers\actions\auth;

use app\actions\ApiAction;
use app\components\ExecutionResult;
use app\components\helpers\UserHelper;

class GetCurrentUserAction extends ApiAction
{
    public function run()
    {
        $user = UserHelper::get();

        return $this->apiResponse(new ExecutionResult(true, [], [
            'user' => $user->getAttributes(['id', 'name']),
        ]));
    }
}
