<?php

use yii\db\Migration;

/**
 * Handles the creation for table `stat_service_query`.
 */
class m160929_040832_create_stat_service_query_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('stat_service_query', [
            'id'          => $this->primaryKey(),
            'date_time'   => $this->timestamp(14),
            'id_operator' => $this->integer(4),
            'id_service'   => $this->integer(8),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('stat_service_query');
    }
}
