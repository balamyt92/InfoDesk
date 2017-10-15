<?php

namespace app\models;

/**
 * This is the model class for table "CarENLinkedDetailNames".
 *
 * @property int $ID_GroupDetail
 * @property int $ID_LinkedDetail
 * @property CarENDetailNames $iDLinkedDetail
 * @property CarENDetailNames $iDGroupDetail
 */
class CarENLinkedDetailNames extends \yii\db\ActiveRecord implements iLegacyImport
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'CarENLinkedDetailNames';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ID_GroupDetail', 'ID_LinkedDetail'], 'required'],
            [['ID_GroupDetail', 'ID_LinkedDetail'], 'integer'],
            [['ID_LinkedDetail'], 'exist', 'skipOnError' => true, 'targetClass' => CarENDetailNames::className(), 'targetAttribute' => ['ID_LinkedDetail' => 'id']],
            [['ID_GroupDetail'], 'exist', 'skipOnError' => true, 'targetClass' => CarENDetailNames::className(), 'targetAttribute' => ['ID_GroupDetail' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ID_GroupDetail'  => 'Id  Group Detail',
            'ID_LinkedDetail' => 'Id  Linked Detail',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIDLinkedDetail()
    {
        return $this->hasOne(CarENDetailNames::className(), ['id' => 'ID_LinkedDetail']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIDGroupDetail()
    {
        return $this->hasOne(CarENDetailNames::className(), ['id' => 'ID_GroupDetail']);
    }

    public function loadData($data)
    {
        $result = array_map(function ($el) {
            return array_slice($el, 0, 2);
        }, $data);

        \Yii::$app->db->createCommand()
            ->batchInsert(
                self::tableName(),
                [
                    'ID_GroupDetail', 'ID_LinkedDetail'
                ],
                $result
            )->execute();

        return '';
    }
}
