<?php

namespace app\controllers\actions\generic;

use app\collectors\AbstractDataCollector;
use app\components\ExecutionResult;
use app\components\helpers\UserHelper;
use app\controllers\actions\ApiAction;
use Throwable;
use Yii;

class CollectorAction extends ApiAction
{
    public string $collectorClass;
    private AbstractDataCollector $collector;

    public function beforeRun()
    {
        $this->collector = new $this->collectorClass;
        return parent::beforeRun();
    }

    public function run()
    {
        $params = null;
        switch ($this->collector->getDataSource()) {
            case AbstractDataCollector::DATA_SOURCE_GET:
                $params = Yii::$app->request->get();
                break;
            case AbstractDataCollector::DATA_SOURCE_JSON:
                $json = file_get_contents('php://input');
                $params = json_decode($json, true);
                break;
        }

        $params['userId'] ??= UserHelper::id();

        try {
            $this->collector->setParams($params);

            return $this->apiResponse(new ExecutionResult(true, [], $this->collector->get()));
        } catch (Throwable $t) {
            return $this->apiResponse(new ExecutionResult(false, ['exception' => $t->getMessage()]));
        }
    }
}
