<?php

namespace app\collectors\landing;

use app\collectors\AbstractDataCollector;
use app\models\landing\LandingBackground;

class LandingCommonCollector extends AbstractDataCollector
{
    public function get(): array
    {
        return [
            'background' => [
                'types' => [
                    [
                        'id' => LandingBackground::COLOR_TYPE,
                        'name' => 'Цвет',
                        'default' => '#ddd'
                    ],
                    [
                        'id' => LandingBackground::GRADIENT_TYPE,
                        'name' => 'Градиент',
                        'default' => 'linear-gradient(0deg, rgb(0, 0, 0) 0%, rgb(1, 122, 200) 100%)'
                    ],
                    [
                        'id' => LandingBackground::IMAGE_TYPE,
                        'name' => 'Изображение',
                        'default' => 'url(https://picsum.photos/100/100) no-repeat'
                    ],
                ]
            ]
        ];
    }
}
