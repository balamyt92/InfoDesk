<?php

namespace app\models;

/**
 * This is the model class for table "Services".
 *
 * @property int $id
 * @property string $Name
 * @property int $ID_Parent
 * @property Services $iDParent
 * @property Services[] $services
 */
class Services extends \yii\db\ActiveRecord implements iLegacyImport
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'Services';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['Name'], 'required'],
            [['ID_Parent'], 'integer'],
            [['Name'], 'string', 'max' => 255],
            [['ID_Parent'], 'exist', 'skipOnError' => true, 'targetClass' => self::className(), 'targetAttribute' => ['ID_Parent' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'        => 'ID',
            'Name'      => 'Name',
            'ID_Parent' => 'Id  Parent',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIDParent()
    {
        return $this->hasOne(self::className(), ['id' => 'ID_Parent']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getServices()
    {
        return $this->hasMany(self::className(), ['ID_Parent' => 'id']);
    }

    /**
     * @param array $data данные для записи
     *
     * @return string возвращает массив ошибок
     */
    public function loadData($data)
    {
        $result = array_map(function ($el) {
            return [
                $el[0], $el[1], $el[3]
            ];
        }, $data);

        \Yii::$app->db->createCommand()
            ->batchInsert(
                self::tableName(),
                [
                    'id', 'Name', 'ID_Parent'
                ],
                $result
            )->execute();

        return '';
    }
}
