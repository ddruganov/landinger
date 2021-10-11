<?php

namespace client\assets\landing;

use yii\web\AssetBundle;

class EntityDisplayAsset extends AssetBundle
{
    public $sourcePath = '@client/src';
    public $js = [
        'js/widgets/landing/EntityDisplayWidget.js',
    ];
}
