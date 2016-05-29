<?php

use yii\db\Migration;

/**
 * Handles the creation for table `firm_table`.
 */
class m160529_065556_create_firm_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('firms', [
            'id' => $this->primaryKey(),
            'Name' => $this->char(75)->notNull(),
            'Address' => $this->text(),
            'Phone' => $this->char(200),
            'Comment' => $this->text(),
            'Enabled' => $this->boolean()->notNull(),
            'ActivityType' => $this->text()->notNull(),
            'OrganizationType' => $this->char(100),
            'District' => $this->char(200),
            'Fax' => $this->char(100),
            'Email' => $this->char(100),
            'URL' => $this->char(100),
            'OperatingMode' => $this->text(),
            'Identifier' => $this->char(100)->notNull(),
            'Priority' => $this->integer(11)->notNull(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('firms');
    }
}
