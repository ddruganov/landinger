<?php

namespace core\social_network;

class SocialNetworkAuthFactory
{
    public static function get(string $alias): ?SocialNetworkAuthInterface
    {
        switch ($alias) {
            case VkAuth::getAlias():
                return new VkAuth();
            case YandexAuth::getAlias():
                return new YandexAuth();
            case OdnoklassnikiAuth::getAlias():
                return new OdnoklassnikiAuth();
            case GoogleAuth::getAlias():
                return new GoogleAuth();
            case FacebookAuth::getAlias():
                return new FacebookAuth();
            default:
                return null;
        }
    }

    public static function all(): array
    {
        return [
            VkAuth::getAlias() => new VkAuth(),
            // YandexAuth::getAlias() =>  new YandexAuth(),
            // OdnoklassnikiAuth::getAlias() => new OdnoklassnikiAuth(),
            // GoogleAuth::getAlias() => new GoogleAuth(),
            // FacebookAuth::getAlias() => new FacebookAuth()
        ];
    }
}
