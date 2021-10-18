<?php

namespace core\components\behaviors;

use api\controllers\actions\ApiAction;
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

        return false;

        return !$model->hasErrors();
    }
}
