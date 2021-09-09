<?php

namespace app\components;

interface CreatableInterface
{
    public static function create(array $attributes): ExecutionResult;
}
