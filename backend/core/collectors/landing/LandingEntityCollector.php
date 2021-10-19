<?php

namespace core\collectors\landing;

use core\collectors\AbstractDataCollector;
use core\components\TreeBuilder;
use core\models\landing\LandingEntity;
use core\models\landing\LandingImage;
use core\models\landing\LandingLink;
use core\models\landing\LandingLinkGroup;
use yii\db\Query;

class LandingEntityCollector extends AbstractDataCollector
{
    public function get(): array
    {
        $query = (new Query())
            ->select([
                'le.id',
                'modelTypeId' => 'le.model_type_id',
                'parentId' => 'le.parent_id',
                'name' => 'coalesce(llg.name, ll.name)',
                'll.value',
                'li.url',
                'le.weight'
            ])
            ->from(['le' => LandingEntity::tableName()])
            ->leftJoin(['llg' => LandingLinkGroup::tableName()], 'llg.id = le.id')
            ->leftJoin(['ll' => LandingLink::tableName()], 'll.id = le.id')
            ->leftJoin(['li' => LandingImage::tableName()], 'li.id = le.id')
            ->where(['le.landing_id' => $this->getParam('landingId')])
            ->orderBy(['le.weight' => SORT_ASC]);

        if ($ids = $this->getParam('ids')) {
            $query->andWhere([
                'in', 'le.id', $ids
            ]);
        }

        return TreeBuilder::run($query->all());
    }

    public function one(): array
    {
        return @reset($this->get());
    }
}
