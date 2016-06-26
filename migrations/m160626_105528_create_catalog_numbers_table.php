<?php

use yii\db\Migration;

/**
 * Handles the creation for table `CatalogNumbersEN`.
 */
class m160626_105528_create_catalog_numbers_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('CatalogNumbersEN', [
            'Catalog_Number' => $this->string()->notNull(),
            'ID_Mark' => $this->integer()->notNull(),
            'ID_Name' => $this->integer()->notNull(),
        ]);

        $this->addPrimaryKey('catalog_numb', 'CatalogNumbersEN',
                            ['Catalog_Number','ID_Mark','ID_Name']);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('CatalogNumbersEN');
    }
}
