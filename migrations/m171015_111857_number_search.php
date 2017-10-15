<?php

use app\models\CarPresenceEN;

use yii\db\Migration;

class m171015_111857_number_search extends Migration
{
    public function safeUp()
    {
        $this->addColumn(CarPresenceEN::tableName(), 'search', 'text');
    }

    public function safeDown()
    {
        echo "m171015_111857_number_search cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171015_111857_number_search cannot be reverted.\n";

        return false;
    }
    */
}
