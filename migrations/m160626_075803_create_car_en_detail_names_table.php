<?php

use yii\db\Migration;

/**
 * Handles the creation for table `car_en_detail_names_table`.
 */
class m160626_075803_create_car_en_detail_names_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('CarENDetailNames', [
            'id' => $this->primaryKey(),
            'Name' => $this->string()->notNull(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('CarENDetailNames');
    }
}
