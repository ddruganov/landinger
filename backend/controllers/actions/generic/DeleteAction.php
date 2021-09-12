<?php

namespace app\controllers\actions\generic;

use app\components\ExecutionResult;
use app\controllers\actions\ApiAction;
use Exception;
use Throwable;
use Yii;
use yii\db\ActiveRecord;

class DeleteAction extends ApiAction
{
    public string $modelClass;

    public function beforeRun()
    {
        if (!is_a($this->modelClass, ActiveRecord::class, true)) {
            throw new Exception($this->modelClass . ' has to be an instance of ActiveRecord');
        }

        return parent::beforeRun();
    }

    public function run()
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $model = $this->modelClass::findOne($this->getData('id'));
            if (!$model) {
                throw new Exception('Модель не найдена');
            }

            if ($model->delete() === false) {
                throw new Exception(@reset($model->getFirstErrors()));
            }

            $transaction->commit();
            return $this->apiResponse(new ExecutionResult(true));
        } catch (Throwable $t) {
            $transaction->rollBack();
            return $this->apiResponse(new ExecutionResult(false, ['exception' => $t->getMessage()]));
        }
    }
}
