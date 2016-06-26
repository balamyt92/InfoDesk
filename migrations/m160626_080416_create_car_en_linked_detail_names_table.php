<?php

use yii\db\Migration;

/**
 * Handles the creation for table `car_en_linked_detail_names_table`.
 */
class m160626_080416_create_car_en_linked_detail_names_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('CarENLinkedDetailNames', [
            'ID_GroupDetail' => $this->integer()->notNull(),
            'ID_LinkedDetail' => $this->integer()->notNull(),
        ]);

        $this->addPrimaryKey('detail-linked', 'CarENLinkedDetailNames',
                            ['ID_GroupDetail', 'ID_LinkedDetail']);

        $this->addForeignKey('linkedDetail1', 'CarENLinkedDetailNames', 'ID_LinkedDetail',
                             'CarENDetailNames', 'id', 'RESTRICT', 'NO ACTION');
        $this->addForeignKey('linkedDetail2', 'CarENLinkedDetailNames', 'ID_GroupDetail',
                             'CarENDetailNames', 'id', 'RESTRICT', 'NO ACTION');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('CarENLinkedDetailNames');
    }
}
