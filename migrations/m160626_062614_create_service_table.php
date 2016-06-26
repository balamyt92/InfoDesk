<?php

use yii\db\Migration;

/**
 * Handles the creation for table `service_table`.
 */
class m160626_062614_create_service_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('services', [
            'id' => $this->primaryKey(),
            'Name' => $this->string()->notNull(),
            'ID_Parent' => $this->integer(),
        ]);
        $this->addForeignKey("service_to_service", "services", "ID_Parent",
                             "services", "id", "RESTRICT", "RESTRICT");
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('services');
    }
}
