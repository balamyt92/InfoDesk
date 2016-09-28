<?php

use yii\db\Migration;

/**
 * Handles the creation for table `stat_parts_firms`.
 */
class m160928_082838_create_stat_parts_firms_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('stat_parts_firms', [
            'id_query' => $this->integer(),
            'id_firm' => $this->integer(),
            'position' => $this->integer(),
            'opened' => $this->boolean(),
        ]);

        $this->addPrimaryKey('stat-part-firm-pk', 'stat_parts_firms', ['id_query', 'id_firm']);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('stat_parts_firms');
    }
}
