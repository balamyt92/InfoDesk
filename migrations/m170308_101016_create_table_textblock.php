<?php

use yii\db\Migration;

class m170308_101016_create_table_textblock extends Migration
{
    public function up()
    {
        $this->createTable('text_block', [
            'id'    => $this->primaryKey(),
            'name'  => $this->char(100)->unique(),
            'text'  => $this->text(),
        ]);
        $this->insert('text_block', [
            'name' => 'label_firms',
            'text' => 'Поиск по фирмам'
        ]);
        $this->insert('text_block', [
            'name' => 'label_parts',
            'text' => 'Поиск запчастей'
        ]);
        $this->insert('text_block', [
            'name' => 'label_services',
            'text' => 'Поиск по услугам и сервисам'
        ]);
        $this->insert('text_block', [
            'name' => 'inform_message',
            'text' => 'default message'
        ]);
    }

    public function down()
    {
        $this->dropTable('text_block');
        return true;
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
