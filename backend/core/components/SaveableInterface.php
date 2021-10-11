<?php

namespace core\components;

interface SaveableInterface
{
    public function saveAttributes(array $attributes): ExecutionResult;
}
