<?php

use yii\db\Migration;

/**
 * Handles the creation for table `CarModelsEN`.
 */
class m160626_085329_create_car_models_en_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('ModelTypes', [
            'id' => $this->primaryKey(),
            'Name' => $this->string()->notNull(),
        ]);

        $this->insert('ModelTypes', [
            'id' => '1',
            'Name' => 'модель',
        ]);
        $this->insert('ModelTypes', [
            'id' => '2',
            'Name' => 'группа',
        ]);

        $this->createTable('CarModelsEN', [
            'id' => $this->primaryKey(),
            'ID_Mark' => $this->integer()->notNull(),
            'Name' => $this->string()->notNull(),
            'ID_Type' => $this->integer()->notNull(),
        ]);

        $this->addForeignKey('model_types', 'CarModelsEN', 'ID_Type',
                             'ModelTypes', 'id', 'RESTRICT', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('CarModelsEN');
        $this->dropTable('ModelTypes');
    }
}
