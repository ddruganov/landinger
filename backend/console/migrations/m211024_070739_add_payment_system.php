<?php

use yii\db\Migration;

require 'm211024_070739_add_payment_model_types.php';
require 'm211024_070739_add_payment_models.php';

/**
 * Class m211024_070739_add_payment_system
 */
class m211024_070739_add_payment_system extends Migration
{
    public function up()
    {
        (new m211024_070739_add_payment_model_types())->up();
        (new m211024_070739_add_payment_models())->up();
    }

    public function down()
    {
        (new m211024_070739_add_payment_model_types())->down();
        (new m211024_070739_add_payment_models())->down();

        return true;
    }
}
