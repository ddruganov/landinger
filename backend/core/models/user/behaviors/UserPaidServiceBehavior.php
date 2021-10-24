<?php

namespace core\models\user\behaviors;

use core\components\ErrorLog;
use core\components\helpers\DateHelper;
use core\models\payment\PaidService;
use core\models\payment\Resource;
use core\models\payment\ResourceType;
use yii\base\Behavior;

class UserPaidServiceBehavior extends Behavior
{
    /**
     * @return \core\models\payment\PaidService[]
     */
    public function getPaidServices(): array
    {
        return PaidService::find()
            ->where([
                'user_id' => $this->owner->getId(),
            ])
            ->andWhere([
                '>', 'expiration_date', DateHelper::now()
            ])
            ->all();
    }

    public function hasAppAccess(): bool
    {
        return $this->getAllowedResourceAmount(ResourceType::APP_ACCESS) > 0;
    }

    public function canCreateLanding(): bool
    {
        $landingCount = count($this->owner->getLandings());
        $allowedLandingCount = $this->getAllowedResourceAmount(ResourceType::LANDING);

        return ($landingCount + 1) <= $allowedLandingCount;
    }

    public function getAllowedResourceAmount(int $resourceTypeId): int
    {
        $allowedResourceAmount = 0;

        foreach ($this->getPaidServices() as $paidService) {
            $service = $paidService->getServiceDuration()->getService();
            $resources = $service->getResources($resourceTypeId);

            $allowedResourceAmount += array_reduce(
                $resources,
                fn (int $carry, Resource $resource) => $carry += $resource->getAmount(),
                0
            );
        }

        return $allowedResourceAmount;
    }
}
