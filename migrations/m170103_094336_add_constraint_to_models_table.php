<?php

use yii\db\Migration;

class m170103_094336_add_constraint_to_models_table extends Migration
{
    public function up()
    {
        $this->execute('SET foreign_key_checks = 0;');
        $this->addForeignKey('model_to_mark', 'CarModelsEN', 'ID_Mark',
                             'CarMarksEN', 'id', 'RESTRICT', 'RESTRICT');
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        echo "m170103_094336_add_constraint_to_models_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
