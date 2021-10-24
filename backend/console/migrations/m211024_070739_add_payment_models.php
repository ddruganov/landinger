<?php

use core\components\ErrorLog;
use core\models\payment\Invoice;
use core\models\payment\PaidService;
use core\models\payment\Resource;
use core\models\payment\ResourceType;
use core\models\payment\Service;
use core\models\payment\ServiceDuration;
use core\models\payment\ServiceResource;
use yii\db\Migration;

/**
 * Class m211024_070739_add_payment_models
 */
class m211024_070739_add_payment_models extends Migration
{
    public function up()
    {
        try {
            $this->execute('
                create schema payment;
            ');
        } catch (Throwable $t) {
        }

        try {
            $this->execute('
                CREATE TABLE IF NOT EXISTS ' . Invoice::tableName() . '
                (
                    id serial NOT NULL,
                    creation_date timestamp without time zone NOT NULL,
                    model_id integer not null,
                    model_type_id integer not null,
                    payment_date timestamp without time zone,
                    acquiring_system_id integer not null,
                    payment_amount numeric(8, 4) not null,
                    income numeric(8, 4),
                    CONSTRAINT invoice_pkey PRIMARY KEY (id)
                )
            ');
        } catch (Throwable $t) {
        }

        try {
            $this->execute('
                CREATE TABLE IF NOT EXISTS ' . Service::tableName() . '
                (
                    id integer NOT NULL,
                    name character varying COLLATE pg_catalog."default" NOT NULL
                )
            ');
        } catch (Throwable $t) {
            ErrorLog::log($t->getMessage());
        }

        try {
            $this->execute('
                CREATE TABLE IF NOT EXISTS ' . ServiceDuration::tableName() . '
                (
                    id serial NOT NULL,
                    service_id integer NOT NULL,
                    duration integer NOT NULL,
                    price integer NOT NULL
                )
            ');
        } catch (Throwable $t) {
        }

        try {
            $this->execute('
                CREATE TABLE IF NOT EXISTS ' . ResourceType::tableName() . '
                (
                    id integer NOT NULL,
                    name character varying COLLATE pg_catalog."default" NOT NULL
                )
            ');
        } catch (Throwable $t) {
        }

        try {
            $this->execute('
                CREATE TABLE IF NOT EXISTS ' . Resource::tableName() . '
                (
                    id integer NOT NULL,
                    type_id integer NOT NULL,
                    amount integer NOT NULL
                )
            ');
        } catch (Throwable $t) {
        }

        try {
            $this->execute('
                CREATE TABLE IF NOT EXISTS ' . ServiceResource::tableName() . '
                (
                    id serial NOT NULL,
                    service_id integer NOT NULL,
                    resource_id integer NOT NULL
                )
            ');
        } catch (Throwable $t) {
        }

        try {
            $this->execute('
                CREATE TABLE IF NOT EXISTS ' . PaidService::tableName() . '
                (
                    id serial NOT NULL,
                    expiration_date timestamp without time zone NOT NULL,
                    user_id integer NOT NULL,
                    service_duration_id integer NOT NULL
                )
            ');
        } catch (Throwable $t) {
        }

        // create services
        try {
            (new Service([
                'id' => Service::DEMO_ACCESS,
                'name' => 'Бесплатный период'
            ]))->save();
            (new Service([
                'id' => Service::BASE_ACCESS,
                'name' => 'Базовый доступ'
            ]))->save();
        } catch (Throwable $t) {
        }

        // populate service duration
        try {
            (new ServiceDuration([
                'serviceId' => Service::DEMO_ACCESS,
                'duration' => ServiceDuration::TWO_WEEKS,
                'price' => 0
            ]))->save();
            (new ServiceDuration([
                'serviceId' => Service::BASE_ACCESS,
                'duration' => ServiceDuration::MONTH,
                'price' => 50,
            ]))->save();
            (new ServiceDuration([
                'serviceId' => Service::BASE_ACCESS,
                'duration' => ServiceDuration::THREE_MONTHS,
                'price' => 130,
            ]))->save();
        } catch (Throwable $t) {
        }

        // create resource types
        try {
            (new ResourceType([
                'id' => ResourceType::APP_ACCESS,
                'name' => 'Доступ к приложению'
            ]))->save();
            (new ResourceType([
                'id' => ResourceType::LANDING,
                'name' => 'Создание лендинга'
            ]))->save();
        } catch (Throwable $t) {
        }

        // create resources
        try {
            (new Resource([
                'id' => Resource::APP_ACCESS,
                'typeId' => ResourceType::APP_ACCESS,
                'amount' => 1
            ]))->save();
            (new Resource([
                'id' => Resource::THREE_LANDINGS,
                'typeId' => ResourceType::LANDING,
                'amount' => 3
            ]))->save();
        } catch (Throwable $t) {
        }

        // bind resources to services
        try {
            (new ServiceResource([
                'serviceId' => Service::DEMO_ACCESS,
                'resourceId' => Resource::APP_ACCESS
            ]))->save();
            (new ServiceResource([
                'serviceId' => Service::DEMO_ACCESS,
                'resourceId' => Resource::THREE_LANDINGS
            ]))->save();
            (new ServiceResource([
                'serviceId' => Service::BASE_ACCESS,
                'resourceId' => Resource::APP_ACCESS
            ]))->save();
            (new ServiceResource([
                'serviceId' => Service::BASE_ACCESS,
                'resourceId' => Resource::THREE_LANDINGS
            ]))->save();
        } catch (Throwable $t) {
        }
    }

    public function down()
    {
        try {
            $this->dropTable(Invoice::tableName());
        } catch (Throwable $t) {
        }

        try {
            $this->dropTable(Service::tableName());
        } catch (Throwable $t) {
        }

        try {
            $this->dropTable(ServiceDuration::tableName());
        } catch (Throwable $t) {
        }

        try {
            $this->dropTable(ResourceType::tableName());
        } catch (Throwable $t) {
        }

        try {
            $this->dropTable(Resource::tableName());
        } catch (Throwable $t) {
        }

        try {
            $this->dropTable(ServiceResource::tableName());
        } catch (Throwable $t) {
        }

        try {
            $this->dropTable(PaidService::tableName());
        } catch (Throwable $t) {
        }

        try {
            $this->execute('drop schema payment');
        } catch (Throwable $t) {
        }

        return true;
    }
}
