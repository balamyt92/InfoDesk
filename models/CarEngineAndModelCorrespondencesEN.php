<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "CarEngineAndModelCorrespondencesEN".
 *
 * @property integer $ID_Mark
 * @property integer $ID_Engine
 * @property integer $ID_Model
 */
class CarEngineAndModelCorrespondencesEN extends \yii\db\ActiveRecord implements iLegacyImport
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'CarEngineAndModelCorrespondencesEN';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ID_Mark', 'ID_Engine', 'ID_Model'], 'required'],
            [['ID_Mark', 'ID_Engine', 'ID_Model'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID_Mark' => 'Id  Mark',
            'ID_Engine' => 'Id  Engine',
            'ID_Model' => 'Id  Model',
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
                    $this->ID_Engine = $line[1];
                    $this->ID_Model = $line[2];
                    if(!$this->save()) {
                        array_push($msg, [$this->getFirstErrors(), $line]);
                    }
                }
                return $msg;
            }
        );
    }
}
