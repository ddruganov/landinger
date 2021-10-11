<?php

namespace client\assets\landing;

use yii\web\AssetBundle;

class EntityDisplayAsset extends AssetBundle
{
    public $sourcePath = '@client/dist';
    public $js = [
        'js/widgets/landing/EntityDisplayWidget.js',
    ];
}
