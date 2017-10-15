<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "CatalogNumbersEN".
 *
 * @property string $Catalog_Number
 * @property int $ID_Mark
 * @property int $ID_Name
 */
class CatalogNumbersEN extends ActiveRecord implements iLegacyImport
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
        $result = array_map(function ($el) {
            return array_slice($el, 0, 3);
        }, $data);

        \Yii::$app->db->createCommand()
            ->batchInsert(
                self::tableName(),
                [
                    'Catalog_Number', 'ID_Mark', 'ID_Name'
                ],
                $result
            )->execute();

        return '';
    }
}
