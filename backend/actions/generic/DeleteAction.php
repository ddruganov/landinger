<?php

namespace app\actions\generic;

use app\actions\ApiAction;
use app\components\ExecutionResult;

class DeleteAction extends ApiAction
{
    public string $modelClass;
    public string $idField = 'id';

    public function run()
    {
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        if (!isset($data[$this->idField])) {
            return $this->apiResponse(new ExecutionResult(false, ['common' => 'Отсутствуют входные данные']));
        }

        $ar = $this->modelClass::findOne($data[$this->idField]);
        if (!$ar) {
            return $this->apiResponse(new ExecutionResult(false, ['common' => 'Модель не найдена']));
        }

        return $this->apiResponse(new ExecutionResult($ar->delete() !== false, $ar->getFirstErrors()));
    }
}
