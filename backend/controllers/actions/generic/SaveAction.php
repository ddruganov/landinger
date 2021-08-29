<?php

namespace app\controllers\actions\generic;

use app\components\ExecutionResult;
use app\components\SaveableInterface;
use app\controllers\actions\ApiAction;
use Exception;
use Throwable;
use Yii;

class SaveAction extends ApiAction
{
    public string $modelClass;

    public function beforeRun()
    {
        if (!is_a($this->modelClass, SaveableInterface::class, true)) {
            throw new Exception($this->modelClass . ' has to be an instance of SaveableInterface');
        }

        return parent::beforeRun();
    }

    public function run()
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            /** @var \app\components\ExecutionResult $saveRes */
            $saveRes = $this->modelClass::saveWithAttributes($this->getData());
            $success = $saveRes->isSuccessful();

            $success ? $transaction->commit() : $transaction->rollBack();

            return $this->apiResponse(new ExecutionResult($success, $saveRes->getErrors(), $saveRes->getData()));
        } catch (Throwable $t) {
            $transaction->rollBack();
            return $this->apiResponse(new ExecutionResult(false, ['common' => $t->getMessage()]));
        }
    }
}
