<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "CarModelsEN".
 *
 * @property int $id
 * @property int $ID_Mark
 * @property string $Name
 * @property int $ID_Type
 * @property ModelTypes $iDType
 */
class CarModelsEN extends ActiveRecord implements iLegacyImport
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'CarModelsEN';
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
    public function getIDType()
    {
        return $this->hasOne(ModelTypes::className(), ['id' => 'ID_Type']);
    }

    public function loadData($data)
    {
        $result = array_map(function ($el) {
            return array_slice($el, 0, 4);
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
