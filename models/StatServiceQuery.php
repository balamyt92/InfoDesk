<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\db\Exception;

/**
 * This is the model class for table "stat_service_query".
 *
 * @property int $id
 * @property string $date_time
 * @property int $id_operator
 * @property int $id_service
 */
class StatServiceQuery extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'stat_service_query';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['date_time'], 'safe'],
            [['id_operator', 'id_service'], 'integer'],
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
            'id_service'  => 'Id Service',
        ];
    }

    /**
     * Функция записывает в базу данных запрос
     *
     * @param int $service_id
     * @param int $operator_id
     *
     * @throws Exception
     */
    public function serviceStatistic($service_id, $operator_id)
    {
        $this->id_service = $service_id;
        $this->id_operator = $operator_id;

        if ($this->validate()) {
            $this->save();
        } else {
            throw new Exception("stat_service_query : Не прошла запись в базу статистики - service {$service_id}, operator {$operator_id}");
        }
    }
}
