<?php

use yii\db\Migration;

class m161213_134747_service_add_update_column extends Migration
{
    public function up()
    {
        $this->addColumn('ServicePresence', 'update_at', 'timestamp(14) DEFAULT CURRENT_TIMESTAMP');
    }

    public function down()
    {
        echo "m161213_134747_service_add_update_column cannot be reverted.\n";
        $this->dropColumn('ServicePresence', 'update_at');
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
