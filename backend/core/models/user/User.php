<?php

namespace core\models\user;

use core\components\CreatableInterface;
use core\components\ExecutionResult;
use core\components\ExtendedActiveRecord;
use core\components\helpers\CookieHelper;
use core\components\helpers\DateHelper;
use core\components\SaveableInterface;
use core\models\landing\Landing;
use core\models\payment\PaidService;
use core\models\payment\Service;
use core\models\payment\ServiceDuration;
use core\models\service\Image;
use core\models\token\TokenGroupGenerator;
use Yii;

/**
 * This is the model class for table "user.user".
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $creationDate
 * @property int $imageId
 */
class User extends ExtendedActiveRecord implements CreatableInterface, SaveableInterface
{
    public static function tableName()
    {
        return 'user.user';
    }

    public function rules()
    {
        return [
            [['name', 'email', 'creationDate', 'password'], 'required'],
            [['name', 'email', 'creationDate', 'password'], 'string'],
            [['creationDate'], 'date', 'format' => 'php:Y-m-d H:i:s'],
            [['imageId'], 'integer']
        ];
    }

    public static function create(array $attributes): ExecutionResult
    {
        $model = new self([
            'name' => $attributes['name'],
            'email' => $attributes['email'],
            'password' => Yii::$app->getSecurity()->generatePasswordHash(md5(microtime())),
            'creationDate' => DateHelper::now()
        ]);

        if (!$model->save()) {
            return new ExecutionResult(false, $model->getFirstErrors());
        }

        // bind free paid service
        $serviceDuration = ServiceDuration::findOne([
            'serviceId' => Service::DEMO_ACCESS,
            'duration' => ServiceDuration::TWO_WEEKS
        ]);
        $paidServiceCreateRes = PaidService::create([
            'userId' => $model->getId(),
            'serviceDurationId' => $serviceDuration->getId()
        ]);
        $paidService = PaidService::findOne($paidServiceCreateRes->getData('id'));
        $invoice = $paidService->getInvoice();
        $invoicePayRes = $invoice->pay([
            'acquiringSystemId' => 1,
            'income' => 0
        ]);

        return $invoicePayRes->appendData(['id' => $model->getId()]);
    }

    public function saveFromAttributes(array $attributes): ExecutionResult
    {
        $this->setAttributes([
            'imageId' => $attributes['image']['id']
        ]);

        return new ExecutionResult($this->save(), $this->getFirstErrors());
    }

    public function login(?array $tokens = null): bool
    {
        $tokens ??= (new TokenGroupGenerator())->issueTokenGroup($this);
        if (!$tokens) {
            return false;
        }

        CookieHelper::setCookie(TokenGroupGenerator::ACCESS_TOKEN_NAME, $tokens['access']['token'], $tokens['access']['expirationDate']);
        CookieHelper::setCookie(TokenGroupGenerator::REFRESH_TOKEN_NAME, $tokens['refresh']['token'], $tokens['refresh']['expirationDate']);

        return true;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getImage(): Image
    {
        return Image::findOne($this->imageId) ?? new Image();
    }

    /**
     * @return \core\models\landing\Landing[]
     */
    public function getLandings(): array
    {
        return Landing::findAll([
            'creatorId' => $this->owner->id
        ]);
    }
}
