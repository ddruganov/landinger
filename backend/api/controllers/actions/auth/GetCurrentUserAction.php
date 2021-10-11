<?php

namespace api\controllers\actions\auth;

use core\components\ExecutionResult;
use core\components\helpers\UserHelper;
use api\controllers\actions\ApiAction;

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
