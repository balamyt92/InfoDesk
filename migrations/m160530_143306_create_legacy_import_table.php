<?php

use yii\db\Migration;

/**
 * Handles the creation for table `legacy_import_table`.
 */
class m160530_143306_create_legacy_import_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('legacy_import_table', [
            'id' => $this->primaryKey(),
            'status' => $this->integer(),
            'message' => $this->string(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('legacy_import_table');
    }
}
