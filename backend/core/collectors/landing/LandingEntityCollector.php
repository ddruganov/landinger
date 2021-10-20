<?php

namespace core\collectors\landing;

use core\collectors\AbstractDataCollector;
use core\components\TreeBuilder;
use core\models\common\ModelType;
use core\models\landing\LandingEntity;
use core\models\landing\LandingEntityInterface;

class LandingEntityCollector extends AbstractDataCollector
{
    public function get(): array
    {
        $query = LandingEntity::find()
            ->where([
                'landing_id' => $this->getParam('landingId')
            ])
            ->orderBy(['weight' => SORT_ASC]);

        if ($ids = $this->getParam('ids')) {
            $query->andWhere([
                'in', 'id', $ids
            ]);
        }

        $data = array_map(fn (LandingEntityInterface $landingEntityInterface) => $landingEntityInterface->getData(), $query->all());

        if ($this->getParam('excludeEmpty')) {
            foreach ($data as $key => $value) {
                switch ($value['modelTypeId']) {
                    case ModelType::LANDING_LINK_GROUP:
                        if (!$value['children']) {
                            unset($data[$key]);
                        }
                        break;
                    case ModelType::LANDING_LINK:
                        if (!$value['value']) {
                            unset($data[$key]);
                        }
                        break;
                    case ModelType::LANDING_IMAGE:
                        if (!$value['image']['id']) {
                            unset($data[$key]);
                        }
                        break;
                }
            }
        }

        return TreeBuilder::run($data);
    }

    public function one(): array
    {
        return @reset($this->get());
    }
}
