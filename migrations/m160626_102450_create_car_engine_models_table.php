<?php

use yii\db\Migration;

/**
 * Handles the creation for tables `CarEngineModelsEN` `CarEngineModelGroupsEN`.
 */
class m160626_102450_create_car_engine_models_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('CarEngineModelsEN', [
            'id' => $this->integer()->notNull(),
            'ID_Mark' => $this->integer()->notNull(),
            'Name' => $this->string()->notNull(),
            'ID_Type' => $this->integer()->notNull(),
        ]);

        $this->addPrimaryKey('engine', 'CarEngineModelsEN', ['id', 'ID_Mark']);
        $this->alterColumn('CarEngineModelsEN', 'id', $this->integer().' NOT NULL AUTO_INCREMENT');

        $this->addForeignKey('engine_to_mark', 'CarEngineModelsEN', 'ID_Mark',
            'CarMarksEN', 'id', 'RESTRICT', 'CASCADE');

        $this->createTable('CarEngineModelGroupsEN', [
            'ID_EngineGroup' => $this->integer()->notNull(),
            'ID_EngineModel' => $this->integer()->notNull(),
            'ID_Mark' => $this->integer()->notNull(),
        ]);

        $this->addPrimaryKey('engine_group', 'CarEngineModelGroupsEN',
            ['ID_EngineGroup', 'ID_EngineModel', 'ID_Mark']);

        $this->addForeignKey('engine_group_to_group', 'CarEngineModelGroupsEN', 'ID_EngineGroup',
            'CarEngineModelsEN', 'id', 'RESTRICT', 'CASCADE');

        $this->addForeignKey('engine_group_to_body_model', 'CarEngineModelGroupsEN', 'ID_EngineModel',
            'CarEngineModelsEN', 'id', 'RESTRICT', 'CASCADE');

        $this->addForeignKey('engine_group_to_mark', 'CarEngineModelGroupsEN', 'ID_Mark',
            'CarMarksEN', 'id', 'RESTRICT', 'CASCADE');
        
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('CarEngineModelGroupsEN');
        $this->dropTable('CarEngineModelsEN');
    }
}
