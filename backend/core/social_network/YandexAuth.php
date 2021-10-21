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
        $base_link = 'https://oauth.yandex.ru/authorize?';

        $query_params = [
            'response_type' => 'code',
            'client_id' => Yii::$app->params['socialNetworkApi']['yandex']['main']['id'],
        ];

        return $base_link . http_build_query($query_params);
    }

    public function getClientData(array $params): ?SocialNetworkAuthClientData
    {
        $code = $params['code'] ?? null;
        if (!$code) {
            return null;
        }

        $access_token = $this->getAccessToken($code);
        if (!$access_token) {
            return null;
        }

        $ch = curl_init('https://login.yandex.ru/info');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Authorization: OAuth ' . $access_token
            ]
        ]);
        $json = curl_exec($ch);
        $data = json_decode($json, true);
        if (!$data) {
            return null;
        }

        return (new SocialNetworkAuthClientData())
            ->setSocialId(strval($data['id']))
            ->setEmail($data['default_email'])
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
                'client_id' => Yii::$app->params['socialNetworkApi']['yandex']['main']['client_id'],
                'client_secret' => Yii::$app->params['socialNetworkApi']['yandex']['main']['client_secret'],
            ]
        ]);

        $json = curl_exec($ch);
        $decoded = json_decode($json, true);

        return $decoded['access_token'] ?? null;
    }
}
