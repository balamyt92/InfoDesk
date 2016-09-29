<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "stat_service_query".
 *
 * @property integer $id
 * @property string $date_time
 * @property integer $id_operator
 * @property integer $id_service
 */
class StatServiceQuery extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'stat_service_query';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['date_time'], 'safe'],
            [['id_operator', 'id_service'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'date_time' => 'Date Time',
            'id_operator' => 'Id Operator',
            'id_service' => 'Id Service',
        ];
    }

    /**
     * Функция записывает в базу данных запрос
     * 
     * @param  int $service_id  
     * @param  int $operator_id 
     * @return bool             
     */
    public function serviceStatistic($service_id, $operator_id)
    {
        $this->id_service  = $service_id;
        $this->id_operator = $operator_id;

        if ($this->validate()) {
            $this->save();

            return true;
        } else {
            \Yii::error('stat_service_query : Не прошла запись в базу статистики');

            return false;
        }
    }
}
