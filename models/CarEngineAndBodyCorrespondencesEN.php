<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "CarEngineAndBodyCorrespondencesEN".
 *
 * @property int $ID_Mark
 * @property int $ID_Model
 * @property int $ID_Body
 * @property int $ID_Engine
 */
class CarEngineAndBodyCorrespondencesEN extends ActiveRecord implements iLegacyImport
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'CarEngineAndBodyCorrespondencesEN';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ID_Mark', 'ID_Model', 'ID_Body', 'ID_Engine'], 'required'],
            [['ID_Mark', 'ID_Model', 'ID_Body', 'ID_Engine'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ID_Mark'   => 'Марка',
            'ID_Model'  => 'Модель',
            'ID_Body'   => 'Кузов',
            'ID_Engine' => 'Двигатель',
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIDBody()
    {
        return $this->hasOne(CarBodyModelsEN::className(), ['id' => 'ID_Body', 'ID_Mark' => 'ID_Mark']);
    }

    public function loadData($data)
    {
        $result = array_map(function ($el) {
            return [
                $el[0], $el[1], $el[2], $el[3]
            ];
        }, $data);

        \Yii::$app->db->createCommand()
            ->batchInsert(
                self::tableName(),
                [
                    'ID_Mark', 'ID_Model', 'ID_Body', 'ID_Engine'
                ],
                $result
            )->execute();

        return '';
    }
}
