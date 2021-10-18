<?php

namespace api\controllers\actions\auth;

use core\models\user\User;
use core\social_network\SocialNetworkAuthFactory;
use Exception;
use Throwable;
use Yii;
use yii\base\Action;

class ActionSocial extends Action
{
    public function run(string $alias)
    {
        $socialNetwork = SocialNetworkAuthFactory::get($alias);

        if (!$socialNetwork) {
            return $this->controller->redirect(Yii::$app->params['links']['admin']['login']);
        }

        $user_data = null;
        try {
            $user_data = $socialNetwork->getClientData(Yii::$app->request->get());
            if (!$user_data) {
                throw new Exception('Ошибка получения данных о клиенте');
            }
        } catch (Throwable $t) {
            return $this->controller->redirect(Yii::$app->params['links']['admin']['login']);
        }

        $user = User::findOne(['email' => $user_data->getEmail()]);
        if (!$user) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $userCreateRes = User::create([
                    'email' => $user_data->getEmail(),
                    'name' => $user_data->getName(),
                ]);
                if (!$userCreateRes->isSuccessful()) {
                    throw new Exception('Ошибка регистрации через соцсеть');
                }
                $user = User::findOne($userCreateRes->getData('id'));
                $transaction->commit();
            } catch (Throwable $t) {
                $transaction->rollBack();
                return $this->controller->redirect(Yii::$app->params['links']['admin']['login']);
            }
        }

        $user->login();

        return $this->controller->redirect(Yii::$app->params['links']['admin']['home']);
    }
}
