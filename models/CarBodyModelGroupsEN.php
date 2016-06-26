<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "CarBodyModelGroupsEN".
 *
 * @property integer $ID_BodyGroup
 * @property integer $ID_BodyModel
 * @property integer $ID_Mark
 * @property integer $ID_Model
 *
 * @property CarBodyModelsEN $iDBodyModel
 * @property CarBodyModelsEN $iDBodyGroup
 * @property CarMarksEN $iDMark
 * @property CarModelsEN $iDModel
 */
class CarBodyModelGroupsEN extends \yii\db\ActiveRecord implements iLegacyImport
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'CarBodyModelGroupsEN';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ID_BodyGroup', 'ID_BodyModel', 'ID_Mark', 'ID_Model'], 'required'],
            [['ID_BodyGroup', 'ID_BodyModel', 'ID_Mark', 'ID_Model'], 'integer'],
            [['ID_BodyModel'], 'exist', 'skipOnError' => true, 'targetClass' => CarBodyModelsEN::className(), 'targetAttribute' => ['ID_BodyModel' => 'id']],
            [['ID_BodyGroup'], 'exist', 'skipOnError' => true, 'targetClass' => CarBodyModelsEN::className(), 'targetAttribute' => ['ID_BodyGroup' => 'id']],
            [['ID_Mark'], 'exist', 'skipOnError' => true, 'targetClass' => CarMarksEN::className(), 'targetAttribute' => ['ID_Mark' => 'id']],
            [['ID_Model'], 'exist', 'skipOnError' => true, 'targetClass' => CarModelsEN::className(), 'targetAttribute' => ['ID_Model' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID_BodyGroup' => 'Id  Body Group',
            'ID_BodyModel' => 'Id  Body Model',
            'ID_Mark' => 'Id  Mark',
            'ID_Model' => 'Id  Model',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIDBodyModel()
    {
        return $this->hasOne(CarBodyModelsEN::className(), ['id' => 'ID_BodyModel']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIDBodyGroup()
    {
        return $this->hasOne(CarBodyModelsEN::className(), ['id' => 'ID_BodyGroup']);
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
    public function getIDModel()
    {
        return $this->hasOne(CarModelsEN::className(), ['id' => 'ID_Model']);
    }

    public function loadData($data)
    {
        return self::getDb()->transaction(
            function ($db) use ($data) {
                $msg = array();
                while($data) {
                    $body = array_shift($data);
                    self::setIsNewRecord(true);
                    $this->ID_BodyGroup = $body[0];
                    $this->ID_BodyModel = $body[1];
                    $this->ID_Mark = $body[2];
                    $this->ID_Model = $body[3];
                    if(!$this->save()) {
                        array_push($msg, [$this->getFirstErrors(), $body]);
                    }
                }
                return $msg;
            }
        );
    }
}
