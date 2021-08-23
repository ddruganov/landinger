<?php

namespace app\models\landing;

use app\components\ExtendedActiveRecord;

class Landing extends ExtendedActiveRecord
{
    public function rules()
    {
        return [
            [['name', 'alias', 'creation_date', 'creator_id'], 'required'],
            [['name', 'alias'], 'string'],
            [['creation_date'], 'date', 'format' => 'php:Y-m-d H:i:s'],
            [['creator_id'], 'integer']
        ];
    }
}
