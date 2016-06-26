<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "CarEngineModelsEN".
 *
 * @property integer $id
 * @property integer $ID_Mark
 * @property string $Name
 * @property integer $ID_Type
 *
 * @property CarEngineModelGroupsEN[] $carEngineModelGroupsENs
 * @property CarEngineModelGroupsEN[] $carEngineModelGroupsENs0
 * @property CarMarksEN $iDMark
 */
class CarEngineModelsEN extends \yii\db\ActiveRecord implements iLegacyImport
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'CarEngineModelsEN';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ID_Mark', 'Name', 'ID_Type'], 'required'],
            [['ID_Mark', 'ID_Type'], 'integer'],
            [['Name'], 'string', 'max' => 255],
            [['ID_Mark'], 'exist', 'skipOnError' => true, 'targetClass' => CarMarksEN::className(), 'targetAttribute' => ['ID_Mark' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ID_Mark' => 'Id  Mark',
            'Name' => 'Name',
            'ID_Type' => 'Id  Type',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCarEngineModelGroupsENs()
    {
        return $this->hasMany(CarEngineModelGroupsEN::className(), ['ID_EngineModel' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCarEngineModelGroupsENs0()
    {
        return $this->hasMany(CarEngineModelGroupsEN::className(), ['ID_EngineGroup' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIDMark()
    {
        return $this->hasOne(CarMarksEN::className(), ['id' => 'ID_Mark']);
    }

    public function loadData($data)
    {
        return self::getDb()->transaction(
            function ($db) use ($data) {
                $msg = array();
                while($data) {
                    $engine = array_shift($data);
                    self::setIsNewRecord(true);
                    $this->id = $engine[0];
                    $this->ID_Mark = $engine[1];
                    $this->Name = $engine[2];
                    $this->ID_Type = $engine[4];
                    if(!$this->save()) {
                        array_push($msg, [$this->getFirstErrors(), $engine]);
                    }
                }
                return $msg;
            }
        );
    }
}
