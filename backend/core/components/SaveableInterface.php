<?php

namespace core\components;

interface SaveableInterface
{
    public function saveFromAttributes(array $attributes): ExecutionResult;
}
