<?php

namespace client\controllers;

use client\controllers\actions\landing\ActionView;
use yii\web\Controller;

class LandingController extends Controller
{
    public function actions()
    {
        return [
            'view' => ActionView::class,
        ];
    }
}
