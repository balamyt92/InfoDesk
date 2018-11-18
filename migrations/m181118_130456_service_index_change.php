<?php

use yii\db\Migration;

class m181118_130456_service_index_change extends Migration
{
    public function safeUp()
    {

    }

    public function safeDown()
    {
        $this->dropForeignKey('firm_to_service', 'ServicePresence');
        $this->dropForeignKey('services_to_presence', 'ServicePresence');
        $this->dropPrimaryKey('service_presence', 'ServicePresence');
        $this->addPrimaryKey('service_presence', 'ServicePresence',
            ['ID_Service', 'ID_Firm', 'Comment(255)', 'CarList(255)', 'Coast(255)']);

        $this->addForeignKey('firm_to_service', 'ServicePresence', 'ID_Firm',
            'Firms', 'id', 'RESTRICT', 'NO ACTION');
        $this->addForeignKey('services_to_presence', 'ServicePresence', 'ID_Service',
            'Services', 'id', 'RESTRICT', 'NO ACTION');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181118_130456_service_index_change cannot be reverted.\n";

        return false;
    }
    */
}
