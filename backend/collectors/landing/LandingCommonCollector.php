<?php

namespace app\collectors\landing;

use app\collectors\AbstractDataCollector;

class LandingCommonCollector extends AbstractDataCollector
{
    public function get(): array
    {
        return [
            'backgroundPresets' => [
                'color' => '#dddddd',
                'gradient' => 'linear-gradient(0deg, rgb(0, 0, 0) 0%, rgb(1, 122, 200) 100%)',
                'image' => 'url(https://picsum.photos/100/100) no-repeat'
            ]
        ];
    }
}
