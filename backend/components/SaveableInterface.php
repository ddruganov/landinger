<?php

namespace app\components;

interface SaveableInterface
{
    public static function saveWithAttributes(array $attributes): ExecutionResult;
}
