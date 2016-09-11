<?php

use yii\db\Migration;

class m160911_145106_alter_coast_format extends Migration
{
    public function up()
    {
        $this->alterColumn('CarPresenceEN', 'Cost', 'decimal(19, 2)');
    }

    public function down()
    {
        echo "m160911_145106_alter_coast_format cannot be reverted.\n";

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
