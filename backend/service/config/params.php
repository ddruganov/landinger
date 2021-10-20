<?php

use yii\helpers\ArrayHelper;

$base_params = require __DIR__ . '/../../config/params.php';

return ArrayHelper::merge($base_params, [
    'multimedia' => [
        'image' => [
            'upload' => [
                'maxFileSize' => 5242880
            ]
        ]
    ]
]);
