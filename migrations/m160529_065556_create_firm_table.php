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
            'Name' => $this->char(200)->notNull(),
            'Address' => $this->text(),
            'Phone' => $this->char(200)->defaultValue("нет"),
            'Comment' => $this->text(),
            'Enabled' => $this->boolean()->defaultValue(1),
            'ActivityType' => $this->text(),
            'OrganizationType' => $this->char(100)->defaultValue(""),
            'District' => $this->char(200)->defaultValue(""),
            'Fax' => $this->char(100)->defaultValue(""),
            'Email' => $this->char(100)->defaultValue(""),
            'URL' => $this->char(100)->defaultValue(""),
            'OperatingMode' => $this->text(),
            'Identifier' => $this->char(100)->defaultValue(""),
            'Priority' => $this->integer(11)->defaultValue(100),
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
