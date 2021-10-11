<?php

namespace client\controllers;

use client\controllers\actions\site\ActionError;
use yii\web\Controller;

class SiteController extends Controller
{
    public function actions()
    {
        return [
            'error' => ActionError::class
        ];
    }
}
