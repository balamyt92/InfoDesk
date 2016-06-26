<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "MarkTypes".
 *
 * @property integer $id
 * @property string $Name
 *
 * @property CarMarksEN[] $carMarksENs
 */
class MarkTypes extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'MarkTypes';
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
    public function getCarMarksENs()
    {
        return $this->hasMany(CarMarksEN::className(), ['ID_Type' => 'id']);
    }
}
