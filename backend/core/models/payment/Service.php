<?php

namespace core\models\payment;

use core\components\ExtendedActiveRecord;

/**
 * @property int $id
 * @property string $name
 * @property int $weight
 */
class Service extends ExtendedActiveRecord
{
    public const DEMO_ACCESS = 1;
    public const BASE_ACCESS = 2;
    public const PLUS_ONE_LANDING = 3;

    public static function tableName()
    {
        return 'payment.service';
    }

    public function rules()
    {
        return [
            [['id', 'name', 'weight'], 'required'],
            [['id', 'weight'], 'integer'],
            [['name'], 'string'],
        ];
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return \core\models\payment\Resource[]
     */
    public function getResources(?int $resourceTypeId = null): array
    {
        $query = Resource::find()
            ->alias('r')
            ->innerJoin(['sr' => ServiceResource::tableName()], 'sr.resource_id = r.id')
            ->where([
                'sr.service_id' => $this->getId()
            ]);

        $resourceTypeId && $query->andWhere([
            'r.type_id' => $resourceTypeId
        ]);

        return $query->all();
    }
}
