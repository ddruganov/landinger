<?php

namespace core\models\landing;

use core\components\CreatableInterface;
use core\components\ExecutionResult;

/**
 * @property int $id
 * @property string $name
 */
class LandingLinkGroup extends LandingEntity implements CreatableInterface
{
    public static function tableName()
    {
        return 'landing.landing_link_group';
    }

    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string'],
        ];
    }

    public static function create(array $attributes): ExecutionResult
    {
        $model = new self([
            'id' => $attributes['id'],
            'name' => $attributes['name']
        ]);

        return new ExecutionResult(
            $model->save(),
            $model->getFirstErrors(),
            $model->getAttributes(['id', 'name'])
        );
    }

    public function delete()
    {
        foreach ($this->getChildren() as $child) {
            if ($child->delete() === false) {
                $this->addErrors($child->getErrors());
                return false;
            }
        }

        return parent::delete();
    }

    /**
     * @return LandingEntity[]
     */
    public function getChildren(): array
    {
        return LandingEntity::findAll(['parent_id' => $this->id]);
    }
}
