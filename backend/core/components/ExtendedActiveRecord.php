<?php

namespace core\components;

use yii\db\ActiveRecord;
use yii\db\ExpressionInterface;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;

class ExtendedActiveRecord extends ActiveRecord
{
    public function __get($name)
    {
        return parent::__get(Inflector::underscore($name));
    }

    public function __set($name, $value)
    {
        return parent::__set(Inflector::underscore($name), $value);
    }

    protected static function findByCondition($condition)
    {
        if (ArrayHelper::isAssociative($condition) && !$condition instanceof ExpressionInterface) {
            foreach ($condition as $key => $value) {
                $converted = Inflector::underscore($key);
                if (!isset($condition[$converted])) {
                    $condition[$converted] = $value;
                    unset($condition[$key]);
                }
            }
        }

        return parent::findByCondition($condition);
    }
}
