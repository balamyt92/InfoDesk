<?php

use yii\db\Migration;

/**
 * Handles the creation for table `stat_service_firms`.
 */
class m160929_040853_create_stat_service_firms_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('stat_service_firms', [
            'id_query' => $this->integer(),
            'id_firm'  => $this->integer(),
            'position' => $this->integer(),
            'opened'   => $this->boolean(),
        ]);
        $this->addPrimaryKey('stat-service-firm-pk', 'stat_service_firms', ['id_query', 'id_firm']);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('stat_service_firms');
    }
}
