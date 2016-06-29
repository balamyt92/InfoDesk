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
        return self::getDb()->transaction(
            function ($db) use ($data) {
                $msg = [];
                while ($data) {
                    $service = array_shift($data);
                    self::setIsNewRecord(true);
                    $this->id = $service[0];
                    $this->Name = $service[1];
                    $this->ID_Parent = $service[3];
                    if (!$this->save()) {
                        array_push($msg, [$this->getFirstErrors(), $service]);
                    }
                }

                return $msg;
            }
        );
    }
}
