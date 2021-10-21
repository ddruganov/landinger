<?php

namespace api\controllers\actions\auth;

use api\controllers\actions\ApiAction;
use core\components\ExecutionResult;
use core\components\Telegram;
use core\models\user\User;
use core\social_network\SocialNetworkAuthFactory;
use Exception;
use Throwable;
use Yii;

class ActionSocial extends ApiAction
{
    public function run()
    {
        $socialNetwork = SocialNetworkAuthFactory::get($this->getData('alias'));

        if (!$socialNetwork) {
            return $this->apiResponse(new ExecutionResult(false, ['exception' => 'Неизвестная социальная сеть']));
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $user_data = $socialNetwork->getClientData($this->getData());
            if (!$user_data) {
                throw new Exception('Ошибка получения данных о клиенте');
            }

            $user = User::findOne(['email' => $user_data->getEmail()]);
            if (!$user) {
                $userCreateRes = User::create([
                    'email' => $user_data->getEmail(),
                    'name' => $user_data->getName(),
                ]);
                if (!$userCreateRes->isSuccessful()) {
                    throw new Exception('Ошибка регистрации через соцсеть');
                }
                $user = User::findOne($userCreateRes->getData('id'));
            }

            $user->login();

            $transaction->commit();
            return $this->apiResponse(new ExecutionResult(true));
        } catch (Throwable $t) {
            (new Telegram())
                ->setTitle('Ошибка авторизации через ' . $this->getData('alias'))
                ->setMessage($t->getMessage())
                ->setTrace($t->getTraceAsString())
                ->send();

            $transaction->rollBack();
            return $this->apiResponse(new ExecutionResult(false, ['exception' => $t->getMessage()]));
        }
    }
}
