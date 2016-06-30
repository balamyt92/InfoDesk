<?php

namespace app\models;

/**
 * This is the model class for table "CatalogNumbersEN".
 *
 * @property string $Catalog_Number
 * @property int $ID_Mark
 * @property int $ID_Name
 */
class CatalogNumbersEN extends \yii\db\ActiveRecord implements iLegacyImport
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'CatalogNumbersEN';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['Catalog_Number', 'ID_Mark', 'ID_Name'], 'required'],
            [['ID_Mark', 'ID_Name'], 'integer'],
            [['Catalog_Number'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'Catalog_Number' => 'Catalog  Number',
            'ID_Mark'        => 'Id  Mark',
            'ID_Name'        => 'Id  Name',
        ];
    }

    public function loadData($data)
    {
        return self::getDb()->transaction(
            function ($db) use ($data) {
                $msg = [];
                while ($data) {
                    $number = array_shift($data);
                    self::setIsNewRecord(true);
                    $this->Catalog_Number = $number[0];
                    $this->ID_Mark = $number[1];
                    $this->ID_Name = $number[2];
                    if (!$this->save()) {
                        array_push($msg, [$this->getFirstErrors(), $number]);
                    }
                }

                return $msg;
            }
        );
    }
}
