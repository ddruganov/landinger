<?php

namespace core\models\user\behaviors;

use core\models\landing\Landing;
use yii\base\Behavior;

class UserLandingBehavior extends Behavior
{
    public const MAX_LANDINGS_PER_USER = 1;

    public function getLandings(): array
    {
        return Landing::findAll([
            'creatorId' => $this->owner->id
        ]);
    }

    public function canCreateLanding(): bool
    {
        if ($this->owner->getId() === SUPERUSER_ID) {
            return true;
        }

        return count($this->getLandings()) < self::MAX_LANDINGS_PER_USER;
    }
}
