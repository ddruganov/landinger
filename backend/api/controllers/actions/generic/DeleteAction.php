<?php

namespace api\controllers\actions\generic;

use core\components\ExecutionResult;
use api\controllers\actions\ApiAction;
use core\components\Telegram;
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
            (new Telegram())
                ->setTitle('Ошибка удаления ' . $this->modelClass . ' #' . $this->getData('id'))
                ->setMessage($t->getMessage())
                ->setTrace($t->getTraceAsString())
                ->send();

            $transaction->rollBack();
            return $this->apiResponse(new ExecutionResult(false, ['exception' => $t->getMessage()]));
        }
    }
}
