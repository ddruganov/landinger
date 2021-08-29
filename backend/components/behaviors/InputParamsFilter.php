<?php

namespace app\components\behaviors;

use app\components\ErrorLog;
use app\controllers\actions\ApiAction;
use yii\base\ActionFilter;
use yii\base\DynamicModel;

class InputParamsFilter extends ActionFilter
{
    public array $rules;

    public function beforeAction($action)
    {
        if (!($action instanceof ApiAction)) {
            return true;
        }

        $model = DynamicModel::validateData($action->getData(), $this->rules);

        ErrorLog::log($model->getErrors());

        return false;

        return !$model->hasErrors();
    }
}
