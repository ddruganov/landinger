<?php

namespace core\social_network;

use Yii;

class FacebookAuth implements SocialNetworkAuthInterface
{
    public static function getAlias(): string
    {
        return 'facebook';
    }

    public function getAuthLink(array $params = []): string
    {
        $base_link = 'https://www.facebook.com/v12.0/dialog/oauth?';

        $query_params = [
            'client_id' => Yii::$app->params['socialNetworkApi']['facebook']['main']['client_id'],
            'redirect_uri' => Yii::$app->params['links']['admin']['auth']['social']['facebook'],
            'scope' => join(',', ['email']),
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

        $url = 'https://graph.facebook.com/v12.0/me?' . http_build_query([
            'fields' => join(',', ['id', 'name', 'email', 'picture.type(large)']),
            'access_token' => $access_token
        ]);
        $json = file_get_contents($url);
        $data = json_decode($json, true);

        return (new SocialNetworkAuthClientData())
            ->setSocialId(strval($data['id']))
            ->setEmail($data['email'])
            ->setName($data['name'])
            ->setPhotoLink($data['picture']['data']['url']);
    }

    private function getAccessToken(string $code): ?string
    {
        $url = 'https://graph.facebook.com/v12.0/oauth/access_token?' . http_build_query([
            'client_id' => Yii::$app->params['socialNetworkApi']['facebook']['main']['client_id'],
            'client_secret' => Yii::$app->params['socialNetworkApi']['facebook']['main']['client_secret'],
            'redirect_uri' => Yii::$app->params['links']['admin']['auth']['social']['facebook'],
            'code' => $code,
        ]);

        $json = file_get_contents($url);
        $data = json_decode($json, true);

        return $data['access_token'] ?? null;
    }
}
