<?php

namespace app\components;

class TreeBuilder
{
    public static function run(array $elements, $parent_id = null)
    {
        $branch = [];

        foreach ($elements as $element) {
            if ($element['parentId'] !== $parent_id) {
                continue;
            }

            $children = self::run($elements, $element['id']);
            $element['children'] = $children;
            $branch[$element['id']] = $element;
        }

        return array_values($branch);
    }
}
