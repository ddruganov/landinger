<?php

namespace app\models\landing;

use app\components\ExecutionResult;
use app\components\ExtendedActiveRecord;
use app\components\SaveableInterface;

class Landing extends ExtendedActiveRecord implements SaveableInterface
{
    public function rules()
    {
        return [
            [['name', 'alias', 'creation_date', 'creator_id'], 'required'],
            [['name', 'alias'], 'string'],
            [['creation_date'], 'date', 'format' => 'php:Y-m-d H:i:s'],
            [['creator_id'], 'integer'],

            [['links'], 'filter', 'filter' => function (?array $links) {

                if (!$links) {
                    return;
                }

                foreach ($links as $linkAttributes) {
                    $res = LandingLink::saveWithAttributes($linkAttributes);
                    if (!$res->isSuccessful()) {
                        $this->addError('links', $res->getErrors());
                    }
                }

                return null;
            }]
        ];
    }

    public static function saveWithAttributes(array $attributes): ExecutionResult
    {
        $model = null;
        if ($id = $attributes['id'] ?? null) {
            $model = self::find($id);
        }
        $model ??= new self();

        $model->setAttributes($attributes);

        return new ExecutionResult(true);
    }
}
