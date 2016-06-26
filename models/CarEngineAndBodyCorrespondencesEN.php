<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "CarEngineAndBodyCorrespondencesEN".
 *
 * @property integer $ID_Mark
 * @property integer $ID_Model
 * @property integer $ID_Body
 * @property integer $ID_Engine
 */
class CarEngineAndBodyCorrespondencesEN extends \yii\db\ActiveRecord implements iLegacyImport
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'CarEngineAndBodyCorrespondencesEN';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ID_Mark', 'ID_Model', 'ID_Body', 'ID_Engine'], 'required'],
            [['ID_Mark', 'ID_Model', 'ID_Body', 'ID_Engine'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID_Mark' => 'Id  Mark',
            'ID_Model' => 'Id  Model',
            'ID_Body' => 'Id  Body',
            'ID_Engine' => 'Id  Engine',
        ];
    }

    public function loadData($data)
    {
        return self::getDb()->transaction(
            function ($db) use ($data) {
                $msg = array();
                while($data) {
                    $line = array_shift($data);
                    self::setIsNewRecord(true);
                    $this->ID_Mark = $line[0];
                    $this->ID_Model = $line[1];
                    $this->ID_Body = $line[2];
                    $this->ID_Engine = $line[3];
                    if(!$this->save()) {
                        array_push($msg, [$this->getFirstErrors(), $line]);
                    }
                }
                return $msg;
            }
        );
    }
}
