<?php

namespace client\controllers;

use client\controllers\actions\site\ActionError;
use client\controllers\actions\site\ActionIndex;
use yii\web\Controller;

class SiteController extends Controller
{
    public function actions()
    {
        return [
            'index' => ActionIndex::class,
            'error' => ActionError::class
        ];
    }
}
