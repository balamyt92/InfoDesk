<?php

namespace app\models;

use yii\db\ActiveRecord;

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
class CarEngineModelGroupsEN extends ActiveRecord implements iLegacyImport
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
        $result = array_map(function ($el) {
            return [
                $el[0], $el[1], $el[2]
            ];
        }, $data);

        \Yii::$app->db->createCommand()
            ->batchInsert(
                self::tableName(),
                [
                    'ID_EngineGroup', 'ID_EngineModel', 'ID_Mark'
                ],
                $result
            )->execute();

        return '';
    }
}
