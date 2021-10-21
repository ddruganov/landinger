<?php

namespace api\controllers\actions\auth;

use api\controllers\actions\ApiAction;
use core\components\ExecutionResult;
use core\components\Telegram;
use core\models\user\behaviors\UserSocialBehavior;
use core\models\user\User;
use core\models\user\UserSocial;
use core\models\user\UserSocialType;
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

        $socialType = UserSocialType::fromAlias($socialNetwork->getAlias());
        if (!$socialType) {
            return $this->apiResponse(new ExecutionResult(false, ['exception' => 'Неизвестная социальная сеть']));
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $userData = $socialNetwork->getClientData($this->getData());
            if (!$userData) {
                throw new Exception('Ошибка получения данных о клиенте');
            }

            $email = $userData->getEmail();
            (new Telegram())->setTitle('social auth')->setMessage('email: ' . $email)->send();
            if ($email) {
                $user = User::findOne(['email' => $email]);
                if (!$user) {
                    $userCreateRes = User::create([
                        'email' => $email,
                        'name' => $userData->getName(),
                    ]);
                    if (!$userCreateRes->isSuccessful()) {
                        throw new Exception('Ошибка регистрации через соцсеть');
                    }
                    $user = User::findOne($userCreateRes->getData('id'));
                }
            } else {
                $userSocial = UserSocial::findOne([
                    'typeId' => $socialType->getId(),
                    'value' => $userData->getSocialId()
                ]);
                if ($userSocial) {
                    $user = User::findOne($userSocial->getUserId());
                } else {
                    $email = 'user' . substr(md5(microtime()), 0, 8) . '@linktome.site';
                    $userCreateRes = User::create([
                        'email' => $email,
                        'name' => $userData->getName(),
                    ]);
                    if (!$userCreateRes->isSuccessful()) {
                        throw new Exception('Ошибка регистрации через соцсеть');
                    }
                    $user = User::findOne($userCreateRes->getData('id'));
                }
            }

            $user->attachBehavior('UserSocialBehavior', new UserSocialBehavior());
            $socialValue = $user->getSocialValue($socialType->getId());
            (new Telegram())->setTitle('user social current')->setMessage(strval($socialValue))->send();
            if (!$socialValue) {
                $user->saveSocialValue($socialType->getId(), $userData->getSocialId());
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
