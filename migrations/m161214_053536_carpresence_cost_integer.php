<?php

use yii\db\Migration;

class m161214_053536_carpresence_cost_integer extends Migration
{
    public function up()
    {
        $this->alterColumn('CarPresenceEN', 'Cost', 'BIGINT');
    }

    public function down()
    {
        echo "m161214_053536_carpresence_cost_integer cannot be reverted.\n";

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
