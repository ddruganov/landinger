<?php

namespace app\actions\generic;

use app\actions\ApiAction;
use app\components\ExecutionResult;
use app\components\helpers\UserHelper;
use app\components\SaveableInterface;
use InvalidArgumentException;
use Throwable;
use Yii;

class SaveAction extends ApiAction
{
    public string $modelClass;

    public function beforeRun()
    {
        if (!is_a($this->modelClass, SaveableInterface::class, true)) {
            throw new InvalidArgumentException($this->modelClass . ' must implement SaveableInterface');
        }
        return true;
    }

    public function run()
    {
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        $data['organizationId'] = $data['organizationId'] ?? UserHelper::getOrganizationId();

        $transaction = Yii::$app->db->beginTransaction();
        try {
            /** @var \app\components\ExecutionResult */
            $result = $this->modelClass::saveWithAttributes($data);
            $result->isSuccessful() ? $transaction->commit() : $transaction->rollBack();
            return $this->apiResponse($result);
        } catch (Throwable $t) {
            $transaction->rollBack();
            return $this->apiResponse(new ExecutionResult(false, ['common' => $t->getMessage()]));
        }
    }
}
