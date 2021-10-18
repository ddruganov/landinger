<?php

namespace core\collectors\auth;

use core\collectors\AbstractDataCollector;
use core\social_network\SocialNetworkAuthFactory;

class SocialLinkCollector extends AbstractDataCollector
{
    public function get(): array
    {
        $output = [];

        foreach (SocialNetworkAuthFactory::all() as $alias => $auth) {
            $output[] = [
                'alias' => $alias,
                'value' => $auth->getAuthLink()
            ];
        }

        return $output;
    }
}
