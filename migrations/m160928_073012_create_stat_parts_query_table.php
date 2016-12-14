<?php

use yii\db\Migration;

/**
 * Handles the creation for table `stat_parts_query`.
 */
class m160928_073012_create_stat_parts_query_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->createTable('stat_parts_query', [
            'id'          => $this->primaryKey(),
            'date_time'   => 'timestamp(14) default current_timestamp',
            'id_operator' => $this->integer(4),
            'detail_id'   => $this->integer(8),
            'mark_id'     => $this->integer(8),
            'model_id'    => $this->integer(8),
            'body_id'     => $this->integer(8),
            'engine_id'   => $this->integer(8),
            'number'      => $this->char(255),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropTable('stat_parts_query');
    }
}
