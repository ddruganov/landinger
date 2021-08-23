<?php

namespace app\components\helpers;

class ArrayHelper
{
    public static function removeFalseyRecursive(?array $input): array
    {
        if (!$input) {
            return [];
        }

        foreach ($input as $key => $value) {
            if (is_array($value)) {
                $input[$key] = self::removeFalseyRecursive($value);
            }
        }

        return array_filter($input, fn ($value) => $value !== null);
    }
}
