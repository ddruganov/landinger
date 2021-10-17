<?php

namespace core\components;

use yii\db\ActiveRecord;
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
}
