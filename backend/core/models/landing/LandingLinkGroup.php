<?php

namespace core\models\landing;

use core\components\CreatableInterface;
use core\components\ErrorLog;
use core\components\ExecutionResult;
use core\components\ExtendedActiveRecord;

/**
 * @property int $id
 * @property string $name
 */
class LandingLinkGroup extends ExtendedActiveRecord implements CreatableInterface
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
        ErrorLog::log($this->id);
        ErrorLog::log($this->getChildren());
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
        return [];
        // return LandingEntity::findAll(['parent_id' => $this->id]);
    }
}
