<?php

namespace app\models;

/**
 * This is the model class for table "CarBodyModelsEN".
 *
 * @property int $id
 * @property int $ID_Mark
 * @property int $ID_Model
 * @property string $Name
 * @property CarBodyModelGroupsEN[] $carBodyModelGroupsENs
 * @property CarBodyModelGroupsEN[] $carBodyModelGroupsENs0
 * @property CarMarksEN $iDMark
 * @property CarModelsEN $iDModel
 * @property ModelTypes $ID_Type
 */
class CarBodyModelsEN extends \yii\db\ActiveRecord implements iLegacyImport
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'CarBodyModelsEN';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ID_Mark', 'ID_Model', 'Name', 'ID_Type'], 'required'],
            [['ID_Mark', 'ID_Model', 'ID_Type'], 'integer'],
            [['Name'], 'string', 'max' => 255],
            [['ID_Mark'], 'exist', 'skipOnError' => true, 'targetClass' => CarMarksEN::className(), 'targetAttribute' => ['ID_Mark' => 'id']],
            [['ID_Model'], 'exist', 'skipOnError' => true, 'targetClass' => CarModelsEN::className(), 'targetAttribute' => ['ID_Model' => 'id']],
            [['ID_Type'], 'exist', 'skipOnError' => true, 'targetClass' => ModelTypes::className(), 'targetAttribute' => ['ID_Type' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'       => 'ID',
            'ID_Mark'  => 'Марка',
            'ID_Model' => 'Модель',
            'Name'     => 'Наименвоание',
            'ID_Type'  => 'Тип',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCarBodyModelGroupsENs()
    {
        return $this->hasMany(CarBodyModelGroupsEN::className(), ['ID_BodyModel' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCarBodyModelGroupsENs0()
    {
        return $this->hasMany(CarBodyModelGroupsEN::className(), ['ID_BodyGroup' => 'id']);
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIDType()
    {
        return $this->hasOne(ModelTypes::className(), ['id' => 'ID_Type']);
    }

    public function loadData($data)
    {
        return self::getDb()->transaction(
            function ($db) use ($data) {
                $msg = [];
                while ($data) {
                    $body = array_shift($data);
                    self::setIsNewRecord(true);
                    $this->id = $body[0];
                    $this->ID_Mark = $body[1];
                    $this->ID_Model = $body[2];
                    $this->Name = $body[3];
                    $this->ID_Type = $body[5];
                    if (!$this->save()) {
                        array_push($msg, [$this->getFirstErrors(), $body]);
                    }
                }

                return $msg;
            }
        );
    }
}
