<?php

use yii\db\Migration;

/**
 * Handles the creation for table `stat_firms_firms`.
 */
class m160929_040910_create_stat_firms_firms_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->createTable('stat_firms_firms', [
            'id_query' => $this->integer(),
            'id_firm'  => $this->integer(),
            'position' => $this->integer(),
            'opened'   => $this->boolean(),
        ]);
        $this->addPrimaryKey('stat-firm-firm-pk', 'stat_firms_firms', ['id_query', 'id_firm']);
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropTable('stat_firms_firms');
    }
}
