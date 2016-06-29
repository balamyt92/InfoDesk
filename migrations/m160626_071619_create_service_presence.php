<?php

use yii\db\Migration;

/**
 * Handles the creation for table `service_presence`.
 */
class m160626_071619_create_service_presence extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->createTable('ServicePresence', [
            'ID_Service' => $this->integer()->notNull(),
            'ID_Firm'    => $this->integer()->notNull(),
            'Comment'    => $this->text(),
            'CarList'    => $this->text(),
            'Coast'      => $this->string(),
        ]);
        $this->addPrimaryKey('service_presence', 'ServicePresence',
            ['ID_Service', 'ID_Firm', 'Comment(255)']);

        $this->addForeignKey('firm_to_service', 'ServicePresence', 'ID_Firm',
                             'Firms', 'id', 'RESTRICT', 'NO ACTION');
        $this->addForeignKey('services_to_presence', 'ServicePresence', 'ID_Service',
                             'Services', 'id', 'RESTRICT', 'NO ACTION');
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropTable('ServicePresence');
    }
}
