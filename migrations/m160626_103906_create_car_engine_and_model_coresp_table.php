<?php

use yii\db\Migration;

/**
 * Handles the creation for table `CarEngineAndModelCorrespondencesEN` and `CarEngineAndBodyCorrespondencesEN`.
 */
class m160626_103906_create_car_engine_and_model_coresp_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->createTable('CarEngineAndModelCorrespondencesEN', [
            'ID_Mark'   => $this->integer()->notNull(),
            'ID_Engine' => $this->integer()->notNull(),
            'ID_Model'  => $this->integer()->notNull(),
        ]);

        $this->addPrimaryKey('eng-model-cor', 'CarEngineAndModelCorrespondencesEN',
                            ['ID_Mark', 'ID_Engine', 'ID_Model']);

        // TODO: добавить внешние ключи если понадобяться

        $this->createTable('CarEngineAndBodyCorrespondencesEN', [
            'ID_Mark'   => $this->integer()->notNull(),
            'ID_Model'  => $this->integer()->notNull(),
            'ID_Body'   => $this->integer()->notNull(),
            'ID_Engine' => $this->integer()->notNull(),
        ]);

        $this->addPrimaryKey('eng-body-cor', 'CarEngineAndBodyCorrespondencesEN',
            ['ID_Mark', 'ID_Model', 'ID_Body', 'ID_Engine']);

        // TODO: добавить внешние ключи если понадобяться
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropTable('CarEngineAndModelCorrespondencesEN');
        $this->dropTable('CarEngineAndBodyCorrespondencesEN');
    }
}
