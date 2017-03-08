<?php

use yii\db\Migration;

class m161214_132412_alter_pk_carpresenceen extends Migration
{
    public function up()
    {
        $dns = explode('=', Yii::$app->getDb()->dsn);
        $dbname = end($dns);
        echo "Dump table CarPresenceEN in base {$dbname}".PHP_EOL;
        exec('mysqldump --user=root --host=localhost --no-create-info '.$dbname.' CarPresenceEN > '.__DIR__.'/tmp.sql');
        $this->dropTable('CarPresenceEN');
        $this->createTable('CarPresenceEN', [
            'ID_Mark'        => $this->integer()->notNull(),
            'ID_Model'       => $this->integer()->notNull(),
            'ID_Name'        => $this->integer()->notNull(),
            'ID_Firm'        => $this->integer()->notNull(),
            'CarYear'        => $this->string(20)->defaultValue('нет'),
            'ID_Body'        => $this->integer()->notNull(),
            'ID_Engine'      => $this->integer()->notNull(),
            'Comment'        => $this->text(),
            'Hash_Comment'   => $this->string(),
            'TechNumber'     => $this->text(),
            'Catalog_Number' => $this->string()->defaultValue('нет'),
            'Cost'           => $this->bigInteger()->defaultValue(0),
        ]);

        $this->addPrimaryKey('presence_pk', 'CarPresenceEN',
            ['ID_Mark', 'ID_Model', 'ID_Name', 'ID_Firm', 'CarYear',
                'ID_Body', 'ID_Engine', 'Catalog_Number', 'TechNumber(100)',
                'Hash_Comment(255)', 'Cost', ]);

        $this->addForeignKey('presence_to_mark', 'CarPresenceEN', 'ID_Mark',
            'CarMarksEN', 'id', 'RESTRICT', 'NO ACTION');
        $this->addForeignKey('presence_to_model', 'CarPresenceEN', 'ID_Model',
            'CarModelsEN', 'id', 'RESTRICT', 'NO ACTION');
        $this->addForeignKey('presence_to_name', 'CarPresenceEN', 'ID_Name',
            'CarENDetailNames', 'id', 'RESTRICT', 'NO ACTION');
        $this->addForeignKey('presence_to_firm', 'CarPresenceEN', 'ID_Firm',
            'Firms', 'id', 'RESTRICT', 'NO ACTION');
        $this->addForeignKey('presence_to_body', 'CarPresenceEN', 'ID_Body',
            'CarBodyModelsEN', 'id', 'RESTRICT', 'NO ACTION');
        $this->addForeignKey('presence_to_engine', 'CarPresenceEN', 'ID_Engine',
            'CarEngineModelsEN', 'id', 'RESTRICT', 'NO ACTION');

        echo 'Load data to base'.PHP_EOL;
        exec('mysql -uroot '.$dbname.' < '.__DIR__.'/tmp.sql');
        unlink(__DIR__.'/tmp.sql');

        $this->execute('ALTER TABLE `CarPresenceEN` ADD FULLTEXT INDEX `full_text_index` (`Comment` ASC, `Catalog_Number` ASC)');
    }

    public function down()
    {
        echo "m161214_132412_alter_pk_carpresenceen cannot be reverted.\n";

        //return false;
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
