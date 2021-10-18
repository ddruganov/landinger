<?php

namespace core\social_network;

interface SocialNetworkAuthInterface
{
    public static function getAlias(): string;
    public function getAuthLink(array $params = []): string;
    public function getClientData(array $params): ?SocialNetworkAuthClientData;
}
