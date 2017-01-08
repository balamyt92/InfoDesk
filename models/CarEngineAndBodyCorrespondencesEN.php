<?php

namespace app\models;

/**
 * This is the model class for table "CarEngineAndBodyCorrespondencesEN".
 *
 * @property int $ID_Mark
 * @property int $ID_Model
 * @property int $ID_Body
 * @property int $ID_Engine
 */
class CarEngineAndBodyCorrespondencesEN extends \yii\db\ActiveRecord implements iLegacyImport
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
        return self::getDb()->transaction(
            function ($db) use ($data) {
                $msg = [];
                while ($data) {
                    $line = array_shift($data);
                    self::setIsNewRecord(true);
                    $this->ID_Mark = $line[0];
                    $this->ID_Model = $line[1];
                    $this->ID_Body = $line[2];
                    $this->ID_Engine = $line[3];
                    if (!$this->save()) {
                        array_push($msg, [$this->getFirstErrors(), $line]);
                    }
                }

                return $msg;
            }
        );
    }
}
