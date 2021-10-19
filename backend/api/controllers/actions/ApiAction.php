<?php

namespace api\controllers\actions;

use core\components\ExecutionResult;
use core\components\helpers\UserHelper;
use Yii;
use yii\base\Action;

class ApiAction extends Action
{
    private const SAFE_ACTIONS = [
        'get-social-links',
        'social',
        'logout',
    ];

    private array $requestData = [];

    public function beforeRun()
    {
        !in_array($this->id, self::SAFE_ACTIONS) && $this->checkUser();

        $json = file_get_contents('php://input');
        $json_data = json_decode($json, true) ?: [];
        $get_data = Yii::$app->request->get() ?: [];

        $this->requestData = array_merge($json_data, $get_data);

        return parent::beforeRun();
    }

    private function checkUser()
    {
        $userId = UserHelper::id();

        if (!$userId) {
            echo json_encode([
                'success' => false,
                'code' => 401 // unauthorized
            ]);
            Yii::$app->end();
        }
    }

    protected function getData(?string $name = null, $default = null)
    {
        if (!$name) {
            return $this->requestData;
        }

        $nested = explode('.', $name);
        $value = $this->requestData;
        foreach ($nested as $param) {
            $value = $value[$param] ?? null;
            if (is_null($value)) {
                break;
            }
        }
        return $value ?? $default;
    }

    protected function apiResponse(ExecutionResult $result)
    {
        return $this->controller->asJson($result->asApiResponse());
    }
}
