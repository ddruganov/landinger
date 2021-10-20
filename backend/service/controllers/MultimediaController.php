<?php

namespace service\controllers;

use service\controllers\actions\multimedia\ActionUploadImage;
use yii\web\Controller;

class MultimediaController extends Controller
{
    public function actions()
    {
        return [
            'uploadImage' => ActionUploadImage::class
        ];
    }
}
