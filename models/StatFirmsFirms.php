<?php

namespace app\models;

/**
 * This is the model class for table "stat_firms_firms".
 *
 * @property int $id_query
 * @property int $id_firm
 * @property int $position
 * @property int $opened
 */
class StatFirmsFirms extends \yii\db\ActiveRecord
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
     * @param int   $query_id
     *
     * @return
     */
    public function firmStatistic($firm_list, $query_id)
    {
        foreach ($firm_list as $key => $value) {
            $this->setIsNewRecord(true);
            $this->id_query = $query_id;
            $this->id_firm = $value;
            $this->position = $key;
            $this->opened = 0;
            if ($this->validate()) {
                $this->save();
            } else {
                \Yii::error('stat_firms_firms : Не прошла запись в базу статистики');
            }
        }
    }
}
