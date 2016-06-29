<?php

use yii\db\Migration;

/**
 * Handles the creation for table `CarPresenceEN`.
 */
class m160626_110154_create_presence_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->createTable('CarPresenceEN', [
            'ID_Mark'        => $this->integer()->notNull(),
            'ID_Model'       => $this->integer()->notNull(),
            'ID_Name'        => $this->integer()->notNull(),
            'ID_Firm'        => $this->integer()->notNull(),
            'CarYear'        => $this->string(20),
            'ID_Body'        => $this->integer()->notNull(),
            'ID_Engine'      => $this->integer()->notNull(),
            'Comment'        => $this->text(),
            'Hash_Comment'   => $this->string(),
            'TechNumber'     => $this->text(),
            'Catalog_Number' => $this->string(),
            'Cost'           => $this->decimal(19, 4),
        ]);

        $this->addPrimaryKey('presence_pk', 'CarPresenceEN',
            ['ID_Mark', 'ID_Model', 'ID_Name', 'ID_Firm', 'CarYear',
             'ID_Body', 'ID_Engine', 'Catalog_Number', 'TechNumber(100)', 'Hash_Comment(255)', ]);

        $this->addForeignKey('presence_to_mark', 'CarPresenceEN', 'ID_Mark',
                             'CarMarksEN', 'id', 'RESTRICT', 'NO ACTION');
        $this->addForeignKey('presence_to_model', 'CarPresenceEN', 'ID_Model',
            'CarModelsEN', 'id', 'RESTRICT', 'NO ACTION');
        $this->addForeignKey('presence_to_name', 'CarPresenceEN', 'ID_Name',
            'CarENDetailNames', 'id', 'RESTRICT', 'NO ACTION');
        $this->addForeignKey('presence_to_firm', 'CarPresenceEN', 'ID_Firm',
            'Firms', 'id', 'RESTRICT', 'NO ACTION');
        $this->addForeignKey('presence_to_body', 'CarPresenceEN', 'ID_Body',
            'CarBodyModelsEN', 'id', 'RESTRICT', 'NO ACTION');
        $this->addForeignKey('presence_to_engine', 'CarPresenceEN', 'ID_Engine',
            'CarEngineModelsEN', 'id', 'RESTRICT', 'NO ACTION');
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropTable('CarPresenceEN');
    }
}
