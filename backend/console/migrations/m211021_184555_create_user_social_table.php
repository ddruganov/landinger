<?php

use core\models\user\UserSocialType;
use core\social_network\FacebookAuth;
use core\social_network\GoogleAuth;
use core\social_network\VkAuth;
use core\social_network\YandexAuth;
use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_social}}`.
 */
class m211021_184555_create_user_social_table extends Migration
{
    public function up()
    {
        try {
            $this->execute('
            CREATE TABLE IF NOT EXISTS "user".user_social_type
            (
                id smallint NOT NULL,
                name character varying COLLATE pg_catalog."default" NOT NULL,
                CONSTRAINT user_social_type_pkey PRIMARY KEY (id)
            )
        ');
        } catch (Throwable $t) {
        }

        try {
            $this->execute("
                insert into \"user\".user_social_type (id, name)
                values
                (" . UserSocialType::VK . ", '" . VkAuth::getAlias() . "'),
                (" . UserSocialType::YANDEX . ", '" . YandexAuth::getAlias() . "'),
                (" . UserSocialType::GOOGLE . ", '" . GoogleAuth::getAlias() . "'),
                (" . UserSocialType::FACEBOOK . ", '" . FacebookAuth::getAlias() . "'),
        ");
        } catch (Throwable $t) {
        }

        try {
            $this->execute('
                CREATE TABLE IF NOT EXISTS "user".user_social
                (
                    id serial NOT NULL,
                    user_id integer NOT NULL,
                    creation_date timestamp without time zone NOT NULL,
                    type_id smallint NOT NULL,
                    value character varying COLLATE pg_catalog."default" NOT NULL,
                    CONSTRAINT user_social_pkey PRIMARY KEY (id),
                    CONSTRAINT fk_type FOREIGN KEY (type_id)
                        REFERENCES "user".user_social_type (id) MATCH SIMPLE
                        ON UPDATE NO ACTION
                        ON DELETE NO ACTION
                        NOT VALID,
                    CONSTRAINT fk_user FOREIGN KEY (user_id)
                        REFERENCES "user"."user" (id) MATCH SIMPLE
                        ON UPDATE NO ACTION
                        ON DELETE NO ACTION
                        NOT VALID
                )
            ');
        } catch (Throwable $t) {
        }
    }

    public function down()
    {
        try {
            $this->execute('
                drop table "user".user_social;
            ');
        } catch (Throwable $t) {
        }

        try {
            $this->execute('
                drop table "user".user_social_type;
            ');
        } catch (Throwable $t) {
        }

        return true;
    }
}
