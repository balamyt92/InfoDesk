<?php

namespace app\models;

/**
 * This is the model class for table "stat_parts_query".
 *
 * @property int $id
 * @property string $date_time
 * @property int $id_operator
 * @property int $detail_id
 * @property int $mark_id
 * @property int $model_id
 * @property int $body_id
 * @property int $engine_id
 * @property string $number
 */
class StatPartsQuery extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'stat_parts_query';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['date_time'], 'safe'],
            [['id_operator', 'detail_id', 'mark_id', 'model_id', 'body_id', 'engine_id'], 'integer'],
            [['number'], 'string', 'max' => 255],
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
            'detail_id'   => 'Detail ID',
            'mark_id'     => 'Mark ID',
            'model_id'    => 'Model ID',
            'body_id'     => 'Body ID',
            'engine_id'   => 'Engine ID',
            'number'      => 'Number',
        ];
    }

    /**
     * Функция записывает в базу данных запрос
     *
     * @param int    $detail_id
     * @param int    $mark_id
     * @param int    $model_id
     * @param int    $body_id
     * @param int    $engine_id
     * @param string $number
     * @param int    $operator_id
     *
     * @return bool
     */
    public function partStatistic($detail_id, $mark_id, $model_id, $body_id, $engine_id, $number, $operator_id)
    {
        $this->id_operator = $operator_id;
        $this->detail_id = $detail_id;
        $this->mark_id = $mark_id;
        $this->model_id = $model_id;
        $this->body_id = $body_id;
        $this->engine_id = $engine_id;
        $this->number = $number;

        if ($this->validate()) {
            $this->save();

            return true;
        } else {
            \Yii::error('stat_parts_query : Не прошла запись в базу статистики');

            return false;
        }
    }
}
