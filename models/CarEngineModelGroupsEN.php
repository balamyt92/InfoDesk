<?php

namespace app\models;

/**
 * This is the model class for table "CarEngineModelGroupsEN".
 *
 * @property int $ID_EngineGroup
 * @property int $ID_EngineModel
 * @property int $ID_Mark
 * @property CarEngineModelsEN $iDEngineModel
 * @property CarEngineModelsEN $iDEngineGroup
 * @property CarMarksEN $iDMark
 */
class CarEngineModelGroupsEN extends \yii\db\ActiveRecord implements iLegacyImport
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'CarEngineModelGroupsEN';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ID_EngineGroup', 'ID_EngineModel', 'ID_Mark'], 'required'],
            [['ID_EngineGroup', 'ID_EngineModel', 'ID_Mark'], 'integer'],
            [['ID_EngineModel'], 'exist', 'skipOnError' => true, 'targetClass' => CarEngineModelsEN::className(), 'targetAttribute' => ['ID_EngineModel' => 'id']],
            [['ID_EngineGroup'], 'exist', 'skipOnError' => true, 'targetClass' => CarEngineModelsEN::className(), 'targetAttribute' => ['ID_EngineGroup' => 'id']],
            [['ID_Mark'], 'exist', 'skipOnError' => true, 'targetClass' => CarMarksEN::className(), 'targetAttribute' => ['ID_Mark' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ID_EngineGroup' => 'Id  Engine Group',
            'ID_EngineModel' => 'Id  Engine Model',
            'ID_Mark'        => 'Id  Mark',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIDEngineModel()
    {
        return $this->hasOne(CarEngineModelsEN::className(), ['id' => 'ID_EngineModel']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIDEngineGroup()
    {
        return $this->hasOne(CarEngineModelsEN::className(), ['id' => 'ID_EngineGroup']);
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
                $msg = [];
                while ($data) {
                    $engine = array_shift($data);
                    self::setIsNewRecord(true);
                    $this->ID_EngineGroup = $engine[0];
                    $this->ID_EngineModel = $engine[1];
                    $this->ID_Mark = $engine[2];
                    if (!$this->save()) {
                        array_push($msg, [$this->getFirstErrors(), $engine]);
                    }
                }

                return $msg;
            }
        );
    }
}
