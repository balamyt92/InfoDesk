<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "CarPresenceEN".
 *
 * @property int $ID_Mark
 * @property int $ID_Model
 * @property int $ID_Name
 * @property int $ID_Firm
 * @property string $CarYear
 * @property int $ID_Body
 * @property int $ID_Engine
 * @property string $Comment
 * @property string $Hash_Comment
 * @property string $TechNumber
 * @property string $Catalog_Number
 * @property string $Cost
 * @property CarBodyModelsEN $iDBody
 * @property CarEngineModelsEN $iDEngine
 * @property Firms $iDFirm
 * @property CarMarksEN $iDMark
 * @property CarModelsEN $iDModel
 * @property CarENDetailNames $iDName
 * @property mixed search
 */
class CarPresenceEN extends ActiveRecord implements iLegacyImport
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ID_Mark', 'ID_Model', 'ID_Name', 'ID_Firm', 'CarYear', 'ID_Body', 'ID_Engine', 'Hash_Comment', 'TechNumber', 'Catalog_Number', 'Cost'], 'required'],
            [['ID_Mark', 'ID_Model', 'ID_Name', 'ID_Firm', 'ID_Body', 'ID_Engine'], 'integer'],
            [['Comment', 'TechNumber', 'search'], 'string'],
            [['Cost'], 'number'],
            [['CarYear'], 'string', 'max' => 20],
            [['Hash_Comment', 'Catalog_Number'], 'string', 'max' => 255],
            [['ID_Body'], 'exist', 'skipOnError' => true, 'targetClass' => CarBodyModelsEN::className(), 'targetAttribute' => ['ID_Body' => 'id']],
            [['ID_Engine'], 'exist', 'skipOnError' => true, 'targetClass' => CarEngineModelsEN::className(), 'targetAttribute' => ['ID_Engine' => 'id']],
            [['ID_Firm'], 'exist', 'skipOnError' => true, 'targetClass' => Firms::className(), 'targetAttribute' => ['ID_Firm' => 'id']],
            [['ID_Mark'], 'exist', 'skipOnError' => true, 'targetClass' => CarMarksEN::className(), 'targetAttribute' => ['ID_Mark' => 'id']],
            [['ID_Model'], 'exist', 'skipOnError' => true, 'targetClass' => CarModelsEN::className(), 'targetAttribute' => ['ID_Model' => 'id']],
            [['ID_Name'], 'exist', 'skipOnError' => true, 'targetClass' => CarENDetailNames::className(), 'targetAttribute' => ['ID_Name' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ID_Mark'        => 'Марка',
            'ID_Model'       => 'Модель',
            'ID_Name'        => 'Деталь',
            'ID_Firm'        => 'Фирма',
            'CarYear'        => 'Год',
            'ID_Body'        => 'Кузов',
            'ID_Engine'      => 'Двигатель',
            'Comment'        => 'Комментарий',
            'Hash_Comment'   => 'Hash',
            'TechNumber'     => 'Фото',
            'Catalog_Number' => 'Номер',
            'Cost'           => 'Цена',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIDBody()
    {
        return $this->hasOne(CarBodyModelsEN::className(), ['id' => 'ID_Body']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIDEngine()
    {
        return $this->hasOne(CarEngineModelsEN::className(), ['id' => 'ID_Engine']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIDFirm()
    {
        return $this->hasOne(Firms::className(), ['id' => 'ID_Firm']);
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
    public function getIDName()
    {
        return $this->hasOne(CarENDetailNames::className(), ['id' => 'ID_Name']);
    }

    public function loadData($data)
    {
        $result = array_map(function ($line) {
            return [
                $line[0],
                $line[1],
                $line[2],
                $line[3],
                $line[4],
                $line[5],
                $line[6],
                $line[7],
                md5($line[7]),
                $line[8],
                $line[9],
                mb_strtolower(trim(str_replace('-', '', $line[7] . ' ' . $line[9]))),
                floatval(substr($line[10], 0, count($line[10]) - 4)),
            ];
        }, $data);

        \Yii::$app->db->createCommand()
            ->batchInsert(
                self::tableName(),
                [
                    'ID_Mark', 'ID_Model', 'ID_Name',
                    'ID_Firm', 'CarYear', 'ID_Body',
                    'ID_Engine', 'Comment', 'Hash_comment',
                    'TechNumber', 'Catalog_Number', 'search',
                    'Cost',
                ],
                $result
            )->execute();

        return '';
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'CarPresenceEN';
    }
}
