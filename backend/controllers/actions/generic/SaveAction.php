<?php

namespace app\controllers\actions\generic;

use app\components\ExecutionResult;
use app\controllers\actions\ApiAction;
use Throwable;
use Yii;
use yii\db\ActiveRecord;

class SaveAction extends ApiAction
{
    public string $modelClass;
    private ActiveRecord $model;

    public function run()
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $this->model = $this->modelClass::findOne($this->getData('id'));

            $this->model->setAttributes($this->getData());

            $success = $this->model->save();

            $success ? $transaction->commit() : $transaction->rollBack();

            return $this->apiResponse(new ExecutionResult($success, $this->model->getFirstErrors()));
        } catch (Throwable $t) {
            $transaction->rollBack();
            return $this->apiResponse(new ExecutionResult(false, ['exception' => $t->getMessage()]));
        }
    }
}
