<?php

namespace core\social_network;

use Yii;

class YandexAuth implements SocialNetworkAuthInterface
{
    public static function getAlias(): string
    {
        return 'yandex';
    }

    public function getAuthLink(array $params = []): string
    {
        $baseLink = 'https://oauth.yandex.ru/authorize?';

        $queryParams = [
            'response_type' => 'code',
            'client_id' => Yii::$app->params['socialNetworkApi']['yandex']['main']['id'],
        ];

        return $baseLink . http_build_query($queryParams);
    }

    public function getClientData(array $params): ?SocialNetworkAuthUserData
    {
        $code = $params['code'] ?? null;
        if (!$code) {
            return null;
        }

        $accessToken = $this->getAccessToken($code);
        if (!$accessToken) {
            return null;
        }

        $ch = curl_init('https://login.yandex.ru/info');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Authorization: OAuth ' . $accessToken
            ]
        ]);
        $json = curl_exec($ch);
        $data = json_decode($json, true);
        if (!$data) {
            return null;
        }

        return (new SocialNetworkAuthUserData())
            ->setSocialId(strval($data['id']))
            ->setEmail($data['default_email'] ?? null)
            ->setName($data['real_name'])
            ->setPhotoLink('https://avatars.yandex.net/get-yapic/' . $data['default_avatar_id'] . '/islands-200');
    }

    private function getAccessToken(string $code): ?string
    {
        $ch = curl_init('https://oauth.yandex.ru/token');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => [
                'grant_type' => 'authorization_code',
                'code' => $code,
                'client_id' => Yii::$app->params['socialNetworkApi']['yandex']['main']['id'],
                'client_secret' => Yii::$app->params['socialNetworkApi']['yandex']['main']['password'],
            ]
        ]);

        $json = curl_exec($ch);
        $decoded = json_decode($json, true);

        return $decoded['access_token'] ?? null;
    }
}
