<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ModelTypes".
 *
 * @property integer $id
 * @property string $Name
 *
 * @property CarModelsEN[] $carModelsENs
 */
class ModelTypes extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ModelTypes';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Name'], 'required'],
            [['Name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'Name' => 'Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCarModelsENs()
    {
        return $this->hasMany(CarModelsEN::className(), ['ID_Type' => 'id']);
    }
}
