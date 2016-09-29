<?php

namespace app\models;

/**
 * This is the model class for table "stat_firms_query".
 *
 * @property int $id
 * @property string $date_time
 * @property int $id_operator
 * @property string $search
 */
class StatFirmsQuery extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'stat_firms_query';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['date_time'], 'safe'],
            [['id_operator'], 'integer'],
            [['search'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'          => 'ID',
            'date_time'   => 'Date Time',
            'id_operator' => 'Id Operator',
            'search'      => 'Search',
        ];
    }

    /**
     * Функция записывает в базу данных запрос
     *
     * @param string $search
     * @param int    $operator_id
     *
     * @return bool
     */
    public function firmStatistic($search, $operator_id)
    {
        $this->search = $search;
        $this->id_operator = $operator_id;

        if ($this->validate()) {
            $this->save();

            return true;
        } else {
            \Yii::error('stat_firms_query : Не прошла запись в базу статистики');

            return false;
        }
    }
}
