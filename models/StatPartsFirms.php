<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "stat_parts_firms".
 *
 * @property int $id_query
 * @property int $id_firm
 * @property int $position
 * @property int $opened
 */
class StatPartsFirms extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'stat_parts_firms';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_query', 'id_firm', 'position', 'opened'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_query' => 'Id Query',
            'id_firm'  => 'Id Firm',
            'position' => 'Position',
            'opened'   => 'Opened',
        ];
    }

    /**
     * Запись списка фирм запроса для статистики.
     *
     * @param array $firm_list
     * @param int $query_id
     */
    public function partStatistic($firm_list, $query_id)
    {
        $rows = [];

        foreach ($firm_list as $key => $value) {
            $rows[] = [$query_id, $value, $key, 0];
        }

        Yii::$app->db->createCommand()
            ->batchInsert(
                self::tableName(),
                ['id_query', 'id_firm', 'position', 'opened'],
                $rows
            )->execute();

    }
}
