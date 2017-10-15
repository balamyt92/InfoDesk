<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "CarENDetailNames".
 *
 * @property int $id
 * @property string $Name
 */
class CarENDetailNames extends ActiveRecord implements iLegacyImport
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'CarENDetailNames';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['Name'], 'required'],
            [['Name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'   => 'ID',
            'Name' => 'Name',
        ];
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
                    'id', 'Name'
                ],
                $result
            )->execute();

        return '';
    }
}
