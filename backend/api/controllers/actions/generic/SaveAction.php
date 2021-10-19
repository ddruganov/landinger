<?php

namespace api\controllers\actions\generic;

use core\components\ExecutionResult;
use api\controllers\actions\ApiAction;
use core\components\SaveableInterface;
use Throwable;
use Yii;

class SaveAction extends ApiAction
{
    public string $modelClass;
    private SaveableInterface $model;

    public function run()
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $this->model = $this->modelClass::findOne($this->getData('id'));

            $res = $this->model->saveFromAttributes($this->getData());

            $res->isSuccessful() ? $transaction->commit() : $transaction->rollBack();

            return $this->apiResponse($res);
        } catch (Throwable $t) {
            $transaction->rollBack();
            return $this->apiResponse(new ExecutionResult(false, ['exception' => $t->getMessage()]));
        }
    }
}
