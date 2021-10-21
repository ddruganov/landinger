<?php

namespace core\social_network;

use Yii;

class VkAuth implements SocialNetworkAuthInterface
{
    public static function getAlias(): string
    {
        return 'vk';
    }

    public function getAuthLink(array $params = []): string
    {
        return 'https://oauth.vk.com/authorize?' . http_build_query([
            'client_id' => Yii::$app->params['socialNetworkApi']['vk']['main']['id'],
            'display' => 'page',
            'redirect_uri' => Yii::$app->params['links']['admin']['auth']['social']['vk'],
            'scope' => 'email',
            'response_type' => 'code',
            'v' => Yii::$app->params['socialNetworkApi']['vk']['main']['v'],
        ]);
    }

    public function getClientData(array $params): ?SocialNetworkAuthClientData
    {
        $code = $params['code'] ?? null;
        if (!$code) {
            return null;
        }

        $accessTokenData = $this->getAccessTokenData($code);
        if (!$accessTokenData) {
            return null;
        }

        $url = 'https://api.vk.com/method/users.get?' . http_build_query([
            'access_token' => $accessTokenData['access_token'],
            'fields' => join(',', ['photo_200_orig']),
            'v' => Yii::$app->params['socialNetworkApi']['vk']['main']['v']
        ]);

        $json = file_get_contents($url);
        $decoded = json_decode($json, true);
        $data = $decoded['response'] ?? [];
        $data = @reset($data);
        if (!$data) {
            return null;
        }

        return (new SocialNetworkAuthClientData())
            ->setSocialId(strval($data['id']))
            ->setEmail($accessTokenData['email'])
            ->setName(join(' ', [$data['first_name'], $data['last_name']]))
            ->setPhotoLink($data['photo_200_orig']);
    }

    private function getAccessTokenData(string $code): ?array
    {
        $url = 'https://oauth.vk.com/access_token?' . http_build_query([
            'client_id' => Yii::$app->params['socialNetworkApi']['vk']['main']['id'],
            'client_secret' => Yii::$app->params['socialNetworkApi']['vk']['main']['secret'],
            'redirect_uri' => Yii::$app->params['links']['admin']['auth']['social']['vk'],
            'code' => $code
        ]);

        $json = file_get_contents($url);
        return json_decode($json, true);
    }
}
