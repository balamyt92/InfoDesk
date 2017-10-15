<?php

namespace app\models;

use yii\db\ActiveRecord;

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
class CarEngineModelsEN extends ActiveRecord implements iLegacyImport
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
        $result = array_map(function ($el) {
            return [
                $el[0], $el[1], $el[2], $el[4]
            ];
        }, $data);

        \Yii::$app->db->createCommand()
            ->batchInsert(
                self::tableName(),
                [
                    'id', 'ID_Mark', 'Name', 'ID_Type'
                ],
                $result
            )->execute();

        return '';
    }
}
