<?php

namespace app\controllers\actions\landing;

use app\actions\ApiAction;
use app\collectors\landing\LandingAllCollector;
use app\components\ExecutionResult;
use app\components\helpers\UserHelper;
use Throwable;

class GetAllLandingsAction extends ApiAction
{
    public function run()
    {
        try {
            $landingCollector = (new LandingAllCollector())->setParams(['creatorId' => UserHelper::id()]);
            return $this->apiResponse(new ExecutionResult(true, [], $landingCollector->get()));
        } catch (Throwable $t) {
            return $this->apiResponse(new ExecutionResult(false, ['common' => $t->getMessage()]));
        }
    }
}
