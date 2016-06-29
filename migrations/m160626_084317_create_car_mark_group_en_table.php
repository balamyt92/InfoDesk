<?php

use yii\db\Migration;

/**
 * Handles the creation for table `car_mark_group_en_table`.
 */
class m160626_084317_create_car_mark_group_en_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->createTable('CarMarkGroupsEN', [
            'ID_Group' => $this->integer()->notNull(),
            'ID_Mark'  => $this->integer()->notNull(),
        ]);

        $this->addPrimaryKey('group_mark', 'CarMarkGroupsEN', ['ID_Group', 'ID_Mark']);
        $this->addForeignKey('group_to_mark', 'CarMarkGroupsEN', 'ID_Group',
                             'CarMarksEN', 'id', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('mark_to_mark', 'CarMarkGroupsEN', 'ID_Mark',
                             'CarMarksEN', 'id', 'RESTRICT', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropTable('CarMarkGroupsEN');
    }
}
