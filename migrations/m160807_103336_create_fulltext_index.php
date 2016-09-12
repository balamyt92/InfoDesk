<?php

use yii\db\Migration;

class m160807_103336_create_fulltext_index extends Migration
{
    public function up()
    {
        $this->execute('ALTER TABLE `CarPresenceEN` 
                        ADD FULLTEXT INDEX `full_text_index` (`Comment` ASC, `Catalog_Number` ASC)');
    }

    public function down()
    {
        echo "m160807_103336_create_fulltext_index cannot be reverted.\n";
        $this->execute('ALTER TABLE `CarPresenceEN` DROP INDEX full_text_index');
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
