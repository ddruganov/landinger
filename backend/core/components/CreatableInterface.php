<?php

namespace core\components;

interface CreatableInterface
{
    public static function create(array $attributes): ExecutionResult;
}
