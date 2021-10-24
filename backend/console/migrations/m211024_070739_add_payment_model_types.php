<?php

use core\models\common\ModelType;
use core\models\payment\Invoice;
use core\models\payment\PaidService;
use core\models\payment\Resource;
use core\models\payment\ResourceType;
use core\models\payment\Service;
use core\models\payment\ServiceDuration;
use core\models\payment\ServiceResource;
use yii\db\Migration;

/**
 * Class m211024_070739_add_payment_model_types
 */
class m211024_070739_add_payment_model_types extends Migration
{
    public function up()
    {
        // add new model types
        try {
            (new ModelType([
                'id' => ModelType::INVOICE,
                'tableName' => Invoice::tableName(),
                'class' => Invoice::class,
                'name' => 'Счёт',
                'tableAlias' => 'i'
            ]))->save();
            (new ModelType([
                'id' => ModelType::SERVICE,
                'tableName' => Service::tableName(),
                'class' => Service::class,
                'name' => 'Услуга',
                'tableAlias' => 's'
            ]))->save();
            (new ModelType([
                'id' => ModelType::SERVICE_DURATION,
                'tableName' => ServiceDuration::tableName(),
                'class' => ServiceDuration::class,
                'name' => 'Продолжительность услуги',
                'tableAlias' => 'sd'
            ]))->save();
            (new ModelType([
                'id' => ModelType::RESOURCE_TYPE,
                'tableName' => ResourceType::tableName(),
                'class' => ResourceType::class,
                'name' => 'Тип ресурса',
                'tableAlias' => 'rt'
            ]))->save();
            (new ModelType([
                'id' => ModelType::RESOURCE,
                'tableName' => Resource::tableName(),
                'class' => Resource::class,
                'name' => 'Ресурс',
                'tableAlias' => 'r'
            ]))->save();
            (new ModelType([
                'id' => ModelType::SERVICE_RESOURCE,
                'tableName' => ServiceResource::tableName(),
                'class' => ServiceResource::class,
                'name' => '-',
                'tableAlias' => 'sr'
            ]))->save();
            (new ModelType([
                'id' => ModelType::PAID_SERVICE,
                'tableName' => PaidService::tableName(),
                'class' => PaidService::class,
                'name' => 'Оплаченная услуга',
                'tableAlias' => 'ps'
            ]))->save();
        } catch (Throwable $t) {
        }
    }

    public function down()
    {
        try {
            ModelType::deleteAll([
                'in', 'id', [
                    ModelType::INVOICE,
                    ModelType::SERVICE,
                    ModelType::SERVICE_DURATION,
                    ModelType::RESOURCE_TYPE,
                    ModelType::RESOURCE,
                    ModelType::SERVICE_RESOURCE,
                    ModelType::PAID_SERVICE
                ]
            ]);
        } catch (Throwable $t) {
        }

        return true;
    }
}
