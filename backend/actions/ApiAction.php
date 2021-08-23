<?php

namespace app\actions;

use app\components\ExecutionResult;
use app\components\helpers\UserHelper;
use Yii;
use yii\base\Action;

class ApiAction extends Action
{
    private const SAFE_ACTIONS = [
        'register',
        'login',
        'logout',
    ];

    public function beforeRun()
    {
        !in_array($this->id, self::SAFE_ACTIONS) && $this->checkUser();

        $json = file_get_contents('php://input');
        $json_data = json_decode($json, true) ?: [];
        $post_data = Yii::$app->request->post() ?: [];
        $get_data = Yii::$app->request->get() ?: [];
        $this->request_data = array_merge($json_data, $post_data, $get_data);

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

    protected function getData(?string $name = null, $default_value = null)
    {
        if (!$name) {
            return $this->request_data;
        }

        $nested_params = explode('.', $name);
        $value = $this->request_data;
        foreach ($nested_params as $param) {
            $value = $value[$param] ?? null;
            if (is_null($value)) {
                break;
            }
        }
        return $value ?? $default_value;
    }

    protected function apiResponse(ExecutionResult $result)
    {
        return $this->controller->asJson($result->asApiResponse());
    }
}
