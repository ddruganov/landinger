<?php

namespace core\social_network;

use Yii;

class OdnoklassnikiAuth implements SocialNetworkAuthInterface
{
    public static function getAlias(): string
    {
        return 'odnoklassniki';
    }

    public function getAuthLink(array $params = []): string
    {
        $base_link = 'https://connect.ok.ru/oauth/authorize?';

        $query_params = [
            'client_id' => Yii::$app->params['socialNetworkApi']['odnoklassniki']['main']['id'],
            'scope' => join(';', ['VALUABLE_ACCESS', 'GET_EMAIL', 'PIC190X190']),
            'response_type' => 'code',
            'redirect_uri' => Yii::$app->params['links']['store']['auth']['social']['ok']
        ];

        return $base_link . http_build_query($query_params);
    }

    public function getClientData(array $params): ?SocialNetworkAuthClientData
    {
        $code = $params['code'] ?? null;
        if (!$code) {
            return null;
        }

        $access_token = $this->getAccessToken($params['code']);
        if (!$access_token) {
            return null;
        }

        return $this->_getClientData($access_token);
    }

    private function getAccessToken(string $code): ?string
    {
        $get_params = [
            'client_id' => Yii::$app->params['socialNetworkApi']['odnoklassniki']['main']['id'],
            'client_secret' => Yii::$app->params['socialNetworkApi']['odnoklassniki']['main']['secret_key'],
            'redirect_uri' => Yii::$app->params['links']['store']['auth']['social']['ok'],
            'grant_type' => 'authorization_code',
            'code' => $code
        ];
        $ch = curl_init('https://api.ok.ru/oauth/token.do?' . http_build_query($get_params));
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true
        ]);
        $res = curl_exec($ch);
        $decoded = json_decode($res, true) ?? [];
        return $decoded['access_token'] ?? null;
    }

    private function _getClientData(string $access_token): ?SocialNetworkAuthClientData
    {
        $secret_key = md5($access_token . Yii::$app->params['socialNetworkApi']['odnoklassniki']['main']['secret_key']);
        $params = [
            'application_key' => Yii::$app->params['socialNetworkApi']['odnoklassniki']['main']['public_key'],
            'method' => 'users.getCurrentUser'
        ];

        $params['sig'] = $this->computeSignature($params, $secret_key);
        $params['access_token'] = $access_token;

        $json = file_get_contents('https://api.ok.ru/fb.do?' . http_build_query($params));

        $data = json_decode($json, true) ?: [];
        if (!$data) {
            return null;
        }

        return (new SocialNetworkAuthClientData())
            ->setSocialId(strval($data['uid']))
            ->setEmail($data['email'] ?? ('ok' . $data['uid'] . '@edeone.com'))
            ->setName($data['name'])
            ->setPhotoLink($data['pic_3']);
    }

    private function computeSignature(array $params, string $secret_key): string
    {
        $params_as_string = array_reduce(
            array_keys($params),
            function ($carry, $key) use ($params) {
                return $carry . $key . '=' . $params[$key];
            }
        );

        return md5($params_as_string . $secret_key);
    }
}
