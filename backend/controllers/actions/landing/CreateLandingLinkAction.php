<?php

namespace app\controllers\actions\landing;

use app\components\behaviors\InputParamsFilter;
use app\components\ExecutionResult;
use app\components\helpers\DateHelper;
use app\components\helpers\UserHelper;
use app\controllers\actions\ApiAction;
use app\models\landing\LandingLink;
use Exception;
use Throwable;

class CreateLandingLinkAction extends ApiAction
{
    public function behaviors()
    {
        return [
            'InputParamsFilter' => [
                'class' => InputParamsFilter::class,
                'rules' => [
                    [['landingId'], 'required'],
                    [['landingId'], 'integer'],
                ]
            ]
        ];
    }

    public function run()
    {
        try {
            $createResult = LandingLink::saveWithAttributes([
                'creationDate' => DateHelper::now(),
                'creatorId' => UserHelper::id(),
                'landingId' => $this->getData('landingId'),
                'name' => 'Новая ссылка'
            ]);

            if (!$createResult->isSuccessful()) {
                throw new Exception(@reset($createResult->getErrors()));
            }

            $data = LandingLink::findOne($createResult->getData('id'))->getAttributes(['id', 'name', 'value']);

            return $this->apiResponse(new ExecutionResult(true, [], $data));
        } catch (Throwable $t) {
            return $this->apiResponse(new ExecutionResult(false, ['common' => $t->getMessage()]));
        }
    }
}
