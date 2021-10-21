<?php

namespace api\controllers\actions\generic;

use core\components\CreatableInterface;
use core\components\ExecutionResult;
use core\components\helpers\UserHelper;
use api\controllers\actions\ApiAction;
use core\components\Telegram;
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

            /** @var \core\components\ExecutionResult $saveRes */
            $saveRes = $this->modelClass::create($attributes);
            $success = $saveRes->isSuccessful();

            $success ? $transaction->commit() : $transaction->rollBack();

            return $this->apiResponse(new ExecutionResult($success, $saveRes->getErrors(), $saveRes->getData()));
        } catch (Throwable $t) {
            (new Telegram())
                ->setTitle('Ошибка создания ' . $this->modelClass)
                ->setMessage($t->getMessage())
                ->setTrace($t->getTraceAsString())
                ->send();

            $transaction->rollBack();
            return $this->apiResponse(new ExecutionResult(false, ['exception' => $t->getMessage()]));
        }
    }
}
