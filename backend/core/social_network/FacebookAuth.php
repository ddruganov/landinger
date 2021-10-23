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
        $baseLink = 'https://www.facebook.com/v12.0/dialog/oauth?';

        $queryParams = [
            'client_id' => Yii::$app->params['socialNetworkApi']['facebook']['main']['client_id'],
            'redirect_uri' => Yii::$app->params['links']['admin']['auth']['social']['facebook'],
            'scope' => join(',', ['email']),
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

        $url = 'https://graph.facebook.com/v12.0/me?' . http_build_query([
            'fields' => join(',', ['id', 'name', 'email', 'picture.type(large)']),
            'access_token' => $accessToken
        ]);
        $json = file_get_contents($url);
        $data = json_decode($json, true);

        return (new SocialNetworkAuthUserData())
            ->setSocialId(strval($data['id']))
            ->setEmail($data['email'] ?? null)
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
