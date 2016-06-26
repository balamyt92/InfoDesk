<?php

use yii\db\Migration;

/**
 * Handles the creation for table `CarModelGroupsEN`.
 */
class m160626_090339_create_car_model_group_en_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('CarModelGroupsEN', [
            'ID_Group' => $this->integer()->notNull(),
            'ID_Model' => $this->integer()->notNull(),
            'ID_Mark' => $this->integer()->notNull(),
        ]);

        $this->addPrimaryKey('model_group', 'CarModelGroupsEN', ['ID_Group', 'ID_Model', 'ID_Mark']);

        $this->addForeignKey('model_group_to_group', 'CarModelGroupsEN', 'ID_Group',
                             'CarModelsEN', 'id', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('model_group_to_model', 'CarModelGroupsEN', 'ID_Model',
                             'CarModelsEN', 'id', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('model_group_to_mark', 'CarModelGroupsEN', 'ID_Mark',
                             'CarMarksEN', 'id', 'RESTRICT', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('CarModelGroupsEN');
    }
}
