<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "CarEngineAndModelCorrespondencesEN".
 *
 * @property int $ID_Mark
 * @property int $ID_Engine
 * @property int $ID_Model
 */
class CarEngineAndModelCorrespondencesEN extends ActiveRecord implements iLegacyImport
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'CarEngineAndModelCorrespondencesEN';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ID_Mark', 'ID_Engine', 'ID_Model'], 'required'],
            [['ID_Mark', 'ID_Engine', 'ID_Model'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ID_Mark'   => 'Марка',
            'ID_Engine' => 'Двигатель',
            'ID_Model'  => 'Модель',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIDMark()
    {
        return $this->hasOne(CarMarksEN::className(), ['id' => 'ID_Mark']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIDEngine()
    {
        return $this->hasOne(CarEngineModelsEN::className(), ['id' => 'ID_Engine', 'ID_Mark' => 'ID_Mark']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIDModel()
    {
        return $this->hasOne(CarModelsEN::className(), ['id' => 'ID_Model', 'ID_Mark' => 'ID_Mark']);
    }

    public function loadData($data)
    {
        $result = array_map(function ($el) {
            return [
                $el[0], $el[1], $el[2]
            ];
        }, $data);

        \Yii::$app->db->createCommand()
            ->batchInsert(
                self::tableName(),
                [
                    'ID_Mark', 'ID_Engine', 'ID_Model'
                ],
                $result
            )->execute();

        return '';
    }
}
