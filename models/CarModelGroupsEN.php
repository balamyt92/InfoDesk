<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "CarModelGroupsEN".
 *
 * @property int $ID_Group
 * @property int $ID_Model
 * @property int $ID_Mark
 * @property CarModelsEN $iDGroup
 * @property CarMarksEN $iDMark
 * @property CarModelsEN $iDModel
 */
class CarModelGroupsEN extends ActiveRecord implements iLegacyImport
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'CarModelGroupsEN';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ID_Group', 'ID_Model', 'ID_Mark'], 'required'],
            [['ID_Group', 'ID_Model', 'ID_Mark'], 'integer'],
            [['ID_Group'], 'exist', 'skipOnError' => true, 'targetClass' => CarModelsEN::className(), 'targetAttribute' => ['ID_Group' => 'id']],
            [['ID_Mark'], 'exist', 'skipOnError' => true, 'targetClass' => CarMarksEN::className(), 'targetAttribute' => ['ID_Mark' => 'id']],
            [['ID_Model'], 'exist', 'skipOnError' => true, 'targetClass' => CarModelsEN::className(), 'targetAttribute' => ['ID_Model' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ID_Group' => 'Id  Group',
            'ID_Model' => 'Id  Model',
            'ID_Mark'  => 'Id  Mark',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIDGroup()
    {
        return $this->hasOne(CarModelsEN::className(), ['id' => 'ID_Group']);
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
        $result = array_map(function ($el) {
            return array_slice($el, 0, 3);
        }, $data);

        \Yii::$app->db->createCommand()
            ->batchInsert(
                self::tableName(),
                [
                    'ID_Group', 'ID_Model', 'ID_Mark'
                ],
                $result
            )->execute();

        return '';
    }
}
