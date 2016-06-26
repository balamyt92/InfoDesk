<?php

use yii\db\Migration;

/**
 * Handles the creation for table `car_marks_en_table`.
 */
class m160626_081816_create_car_marks_en_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('MarkTypes', [
            'id' => $this->primaryKey(),
            'Name' => $this->string()->notNull(),
        ]);

        $this->insert('MarkTypes', [
            'id' => '1',
            'Name' => 'марка',
        ]);
        $this->insert('MarkTypes', [
            'id' => '2',
            'Name' => 'группа',
        ]);

        $this->createTable('CarMarksEN', [
            'id' => $this->primaryKey(),
            'Name' => $this->string()->notNull(),
            'ID_Type' => $this->integer()->notNull(),
        ]);

        $this->addForeignKey('mark-group', 'CarMarksEN', 'ID_Type',
                             'MarkTypes', 'id', 'RESTRICT', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('CarMarksEN');
        $this->dropTable('MarkTypes');
    }
}
