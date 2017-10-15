<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "CarMarksEN".
 *
 * @property int $id
 * @property string $Name
 * @property int $ID_Type
 * @property MarkTypes $iDType
 */
class CarMarksEN extends ActiveRecord implements iLegacyImport
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'CarMarksEN';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['Name', 'ID_Type'], 'required'],
            [['ID_Type'], 'integer'],
            [['Name'], 'string', 'max' => 255],
            [['ID_Type'], 'exist', 'skipOnError' => true, 'targetClass' => MarkTypes::className(), 'targetAttribute' => ['ID_Type' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'      => 'ID',
            'Name'    => 'Наименование',
            'ID_Type' => 'Тип',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIDType()
    {
        return $this->hasOne(MarkTypes::className(), ['id' => 'ID_Type']);
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
                    'id', 'Name', 'ID_Type'
                ],
                $result
            )->execute();

        return '';
    }
}
