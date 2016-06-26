<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "CarModelsEN".
 *
 * @property integer $id
 * @property integer $ID_Mark
 * @property string $Name
 * @property integer $ID_Type
 *
 * @property ModelTypes $iDType
 */
class CarModelsEN extends \yii\db\ActiveRecord implements iLegacyImport
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'CarModelsEN';
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
            [['ID_Type'], 'exist', 'skipOnError' => true, 'targetClass' => ModelTypes::className(), 'targetAttribute' => ['ID_Type' => 'id']],
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
    public function getIDType()
    {
        return $this->hasOne(ModelTypes::className(), ['id' => 'ID_Type']);
    }

    public function loadData($data)
    {
        return self::getDb()->transaction(
            function ($db) use ($data) {
                $msg = array();
                while($data) {
                    $model = array_shift($data);
                    self::setIsNewRecord(true);
                    $this->id = $model[0];
                    $this->ID_Mark = $model[1];
                    $this->Name = $model[2];
                    $this->ID_Type = $model[3];
                    if(!$this->save()) {
                        array_push($msg, [$this->getFirstErrors(), $model]);
                    }
                }
                return $msg;
            }
        );
    }
}
