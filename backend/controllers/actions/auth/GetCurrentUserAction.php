<?php

namespace app\controllers\actions\auth;

use app\components\ExecutionResult;
use app\components\helpers\UserHelper;
use app\controllers\actions\ApiAction;

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
