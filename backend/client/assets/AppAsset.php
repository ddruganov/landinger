<?php

namespace client\assets;

use yii\web\AssetBundle;

class AppAsset extends AssetBundle
{
    public $sourcePath = '@client/dist';
    public $css = [
        'css/main.css',
    ];
}
