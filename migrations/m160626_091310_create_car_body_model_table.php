<?php

use yii\db\Migration;

/**
 * Handles the creation for tables `CarBodyModelsEN` and `CarBodyModelGroupsEN`.
 */
class m160626_091310_create_car_body_model_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('CarBodyModelsEN', [
            'id' => $this->integer()->notNull(),
            'ID_Mark' => $this->integer()->notNull(),
            'ID_Model' => $this->integer()->notNull(),
            'Name' => $this->string()->notNull(),
            'ID_Type' => $this->integer()->notNull(),
        ]);

        $this->addPrimaryKey('body', 'CarBodyModelsEN', ['id', 'ID_Mark', 'ID_Model']);
        $this->alterColumn('CarBodyModelsEN', 'id', $this->integer().' NOT NULL AUTO_INCREMENT');


        $this->addForeignKey('body_to_mark', 'CarBodyModelsEN', 'ID_Mark',
            'CarMarksEN', 'id', 'RESTRICT', 'CASCADE');

        $this->addForeignKey('body_to_model', 'CarBodyModelsEN', 'ID_Model',
            'CarModelsEN', 'id', 'RESTRICT', 'CASCADE');


        $this->createTable('CarBodyModelGroupsEN', [
            'ID_BodyGroup' => $this->integer()->notNull(),
            'ID_BodyModel' => $this->integer()->notNull(),
            'ID_Mark' => $this->integer()->notNull(),
            'ID_Model' => $this->integer()->notNull(),
        ]);

        $this->addPrimaryKey('body_group', 'CarBodyModelGroupsEN',
                            ['ID_BodyGroup', 'ID_BodyModel', 'ID_Mark', 'ID_Model']);

        $this->addForeignKey('body_group_to_group', 'CarBodyModelGroupsEN', 'ID_BodyGroup',
                             'CarBodyModelsEN', 'id', 'RESTRICT', 'CASCADE');

        $this->addForeignKey('body_group_to_body_model', 'CarBodyModelGroupsEN', 'ID_BodyModel',
                             'CarBodyModelsEN', 'id', 'RESTRICT', 'CASCADE');

        $this->addForeignKey('body_group_to_mark', 'CarBodyModelGroupsEN', 'ID_Mark',
                             'CarMarksEN', 'id', 'RESTRICT', 'CASCADE');

        $this->addForeignKey('body_group_to_model', 'CarBodyModelGroupsEN', 'ID_Model',
                             'CarModelsEN', 'id', 'RESTRICT', 'CASCADE');

    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('CarBodyModelGroupsEN');
        $this->dropTable('CarBodyModelsEN');
    }
}
