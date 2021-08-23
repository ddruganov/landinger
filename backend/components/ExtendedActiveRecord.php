<?php

namespace app\components;

use app\components\typedField\ImageTypedField;
use app\components\typedField\TypedFieldDescriptor;
use ReflectionClass;
use ReflectionProperty;
use yii\db\ActiveRecord;
use yii\helpers\Inflector;

class ExtendedActiveRecord extends ActiveRecord
{
    /** @var TypedFieldDescriptor[] */
    private array $_properties = [];

    public function __construct($config = [])
    {
        parent::__construct($config);

        $this->initializeTypedProperties(false);
        $this->on(ActiveRecord::EVENT_AFTER_FIND, fn () => $this->initializeTypedProperties(true));
    }

    public function save($runValidation = true, $attributeNames = null)
    {
        return $this->saveTypedProperties() && parent::save($runValidation, $attributeNames);
    }

    public function delete()
    {
        // returning `deleteTypedProperties && delete()` will not do the trick
        // since delete can return 0 instead of false
        if (!$this->deleteTypedProperties()) {
            return false;
        }

        return parent::delete();
    }

    public function setAttributes($values, $safeOnly = true)
    {
        // convert upper case to lower case
        foreach ($values as $key => $value) {
            $converted = Inflector::camel2id($key, '_');
            if ($key === $converted) {
                continue;
            }
            $values[$converted] = $value;
            unset($values[$key]);
        }

        // set data to typed fields
        foreach ($this->_properties as $property) {
            $propertyName = $property->getName();
            if (!isset($values[$propertyName])) {
                $values[$propertyName] = $this->{$propertyName}->getData();
            }

            $this->{$propertyName}->setData($values[$propertyName]);

            unset($values[$propertyName]);
        }

        parent::setAttributes($values);
    }

    public function getAttributes($names = null, $except = [])
    {
        foreach ($names as $index => $name) {
            $converted = Inflector::camel2id($name, '_');
            if ($name === $converted) {
                continue;
            }
            $names[$index] = $converted;
            unset($names[$index]);
        }

        $data = parent::getAttributes($names, $except);
        foreach ($data as $key => $value) {
            $converted = Inflector::variablize($key);
            if ($key === $converted) {
                continue;
            }
            $data[$converted] = $value;
            unset($data[$key]);
        }

        return $data;
    }

    #region Private methods
    public function saveTypedProperties(): bool
    {
        // go through each typed property
        foreach ($this->_properties as $property) {
            /** @var ExecutionResult */
            $res = $this->{$property->getName()}->saveData();
            if (!$res->isSuccessful()) {
                $this->addErrors($res->getErrors());
                return false;
            }
        }

        return true;
    }

    public function deleteTypedProperties(): bool
    {
        // go through each typed property
        foreach ($this->_properties as $property) {
            /** @var ExecutionResult */
            $res = $this->{$property->getName()}->deleteData();
            if (!$res->isSuccessful()) {
                $this->addErrors($res->getErrors());
                return false;
            }
        }

        return true;
    }

    private function initializeTypedProperties(bool $fetchData): void
    {
        if (!$this->_properties) {
            foreach ((new ReflectionClass($this))->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
                $propertyType = $property->getType()->getName();
                $propertyName = $property->getName();

                if (!in_array($propertyType, [ImageTypedField::class])) {
                    continue;
                }

                $this->_properties[] = new TypedFieldDescriptor($propertyType, $propertyName);
                $this->{$propertyName} = new $propertyType($this, $propertyName);
            }
        }

        if (!$fetchData) {
            return;
        }

        foreach ($this->_properties as $property) {
            $this->{$property->getName()}->init();
        }
    }
    #endregion

    #region Magic methods
    public function __get($name)
    {
        $converted = Inflector::camel2id($name, '_');
        if (in_array($converted, $this->attributes())) {
            $name = $converted;
        }

        return parent::__get($name);
    }

    public function __set($name, $value)
    {
        $converted = Inflector::camel2id($name, '_');
        if (in_array($converted, $this->attributes())) {
            $name = $converted;
        }

        return parent::__set($name, $value);
    }
    #endregion
}
