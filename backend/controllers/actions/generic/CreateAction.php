<?php

namespace app\controllers\actions\generic;

use app\components\CreatableInterface;
use app\components\ExecutionResult;
use app\components\helpers\UserHelper;
use app\controllers\actions\ApiAction;
use Exception;
use Throwable;
use Yii;

class CreateAction extends ApiAction
{
    public string $modelClass;

    public function beforeRun()
    {
        if (!is_a($this->modelClass, CreatableInterface::class, true)) {
            throw new Exception($this->modelClass . ' has to be an instance of CreatableInterface');
        }

        return parent::beforeRun();
    }

    public function run()
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $attributes = array_merge($this->getData(), [
                'userId' => UserHelper::id()
            ]);

            /** @var \app\components\ExecutionResult $saveRes */
            $saveRes = $this->modelClass::create($attributes);
            $success = $saveRes->isSuccessful();

            $success ? $transaction->commit() : $transaction->rollBack();

            return $this->apiResponse(new ExecutionResult($success, $saveRes->getErrors(), $saveRes->getData()));
        } catch (Throwable $t) {
            $transaction->rollBack();
            return $this->apiResponse(new ExecutionResult(false, ['exception' => $t->getMessage()]));
        }
    }
}
