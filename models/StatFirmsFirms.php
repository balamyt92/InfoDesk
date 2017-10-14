<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Exception;

/**
 * This is the model class for table "stat_firms_firms".
 *
 * @property int $id_query
 * @property int $id_firm
 * @property int $position
 * @property int $opened
 */
class StatFirmsFirms extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'stat_firms_firms';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_query', 'id_firm'], 'required'],
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
     *
     * @throws Exception
     */
    public function firmStatistic($firm_list, $query_id)
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
