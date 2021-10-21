<?php

namespace core\social_network;

use Yii;

class GoogleAuth implements SocialNetworkAuthInterface
{
    public static function getAlias(): string
    {
        return 'google';
    }

    public function getAuthLink(array $params = []): string
    {
        $base_link = 'https://accounts.google.com/o/oauth2/v2/auth?';

        $query_params = [
            'response_type' => 'code',
            'scope' => 'https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile',
            'include_granted_scopes' => 'false',
            'redirect_uri' => Yii::$app->params['links']['admin']['auth']['social']['google'],
            'client_id' => Yii::$app->params['socialNetworkApi']['google']['main']['client_id'],
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

        $json = file_get_contents('https://www.googleapis.com/oauth2/v1/userinfo?access_token=' . $access_token);
        $data = json_decode($json, true);

        return (new SocialNetworkAuthClientData())
            ->setSocialId(strval($data['id']))
            ->setEmail($data['email'] ?? null)
            ->setName($data['name'] ?? null)
            ->setPhotoLink($data['picture'] ?? null);
    }

    private function getAccessToken(string $code): ?string
    {
        $ch = curl_init('https://oauth2.googleapis.com/token');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => [
                'client_id' => Yii::$app->params['socialNetworkApi']['google']['main']['client_id'],
                'client_secret' => Yii::$app->params['socialNetworkApi']['google']['main']['client_secret'],
                'code' => $code,
                'grant_type' => 'authorization_code',
                'redirect_uri' => Yii::$app->params['links']['admin']['auth']['social']['google']
            ]
        ]);

        $json = curl_exec($ch);
        $decoded = json_decode($json, true);

        return $decoded['access_token'] ?? null;
    }
}
