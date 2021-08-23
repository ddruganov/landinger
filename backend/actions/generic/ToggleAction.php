<?php

namespace app\actions\generic;

use app\actions\ApiAction;
use app\components\ExecutionResult;

class ToggleAction extends ApiAction
{
    public string $modelClass;

    public function run()
    {
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        if (!isset($data['id'])) {
            return $this->apiResponse(new ExecutionResult(false, ['common' => 'Отсутствуют входные данные']));
        }

        $ar = $this->modelClass::findOne($data['id']);
        if (!$ar) {
            return $this->apiResponse(new ExecutionResult(false, ['common' => 'Модель не найдена']));
        }

        return $this->apiResponse(new ExecutionResult($ar->toggle()->save(), $ar->getFirstErrors(), ['value' => $ar->active]));
    }
}
