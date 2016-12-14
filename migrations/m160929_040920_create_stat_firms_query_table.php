<?php

use yii\db\Migration;

/**
 * Handles the creation for table `stat_firms_query`.
 */
class m160929_040920_create_stat_firms_query_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->createTable('stat_firms_query', [
            'id'          => $this->primaryKey(),
            'date_time'   => 'timestamp(14) default current_timestamp',
            'id_operator' => $this->integer(4),
            'search'      => $this->char(255),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropTable('stat_firms_query');
    }
}
