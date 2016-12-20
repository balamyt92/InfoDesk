<?php

namespace app\models;

/**
 * This is the model class for table "CarEngineModelsEN".
 *
 * @property int $id
 * @property int $ID_Mark
 * @property string $Name
 * @property CarEngineModelGroupsEN[] $carEngineModelGroupsENs
 * @property CarEngineModelGroupsEN[] $carEngineModelGroupsENs0
 * @property CarMarksEN $iDMark
 * @property ModelTypes $ID_Type
 */
class CarEngineModelsEN extends \yii\db\ActiveRecord implements iLegacyImport
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'CarEngineModelsEN';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ID_Mark', 'Name', 'ID_Type'], 'required'],
            [['ID_Mark', 'ID_Type'], 'integer'],
            [['Name'], 'string', 'max' => 255],
            [['ID_Mark'], 'exist', 'skipOnError' => true, 'targetClass' => CarMarksEN::className(), 'targetAttribute' => ['ID_Mark' => 'id']],
            [['ID_Type'], 'exist', 'skipOnError' => true, 'targetClass' => ModelTypes::className(), 'targetAttribute' => ['ID_Type' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'      => 'ID',
            'ID_Mark' => 'Марка',
            'Name'    => 'Наименование',
            'ID_Type' => 'Тип',
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
                $msg = [];
                while ($data) {
                    $engine = array_shift($data);
                    self::setIsNewRecord(true);
                    $this->id = $engine[0];
                    $this->ID_Mark = $engine[1];
                    $this->Name = $engine[2];
                    $this->ID_Type = $engine[4];
                    if (!$this->save()) {
                        array_push($msg, [$this->getFirstErrors(), $engine]);
                    }
                }

                return $msg;
            }
        );
    }
}
