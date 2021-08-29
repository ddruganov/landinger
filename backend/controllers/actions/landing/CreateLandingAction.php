<?php

namespace app\controllers\actions\landing;

use app\collectors\landing\LandingAllCollector;
use app\components\ExecutionResult;
use app\components\helpers\DateHelper;
use app\components\helpers\UserHelper;
use app\controllers\actions\ApiAction;
use app\models\landing\Landing;
use Exception;
use Throwable;

class CreateLandingAction extends ApiAction
{
    public function run()
    {
        try {
            $createResult = Landing::saveWithAttributes([
                'creationDate' => DateHelper::now(),
                'creatorId' => UserHelper::id(),
                'name' => 'Новый лендинг',
                'alias' => md5(microtime() . rand())
            ]);

            if (!$createResult->isSuccessful()) {
                throw new Exception(@reset($createResult->getErrors()));
            }

            $landingData = @reset((new LandingAllCollector())
                ->setIds([$createResult->getData('id')])
                ->get());

            return $this->apiResponse(new ExecutionResult(true, [], $landingData));
        } catch (Throwable $t) {
            return $this->apiResponse(new ExecutionResult(false, ['common' => $t->getMessage()]));
        }
    }
}
