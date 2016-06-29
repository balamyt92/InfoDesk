<?php

namespace app\models;

/**
 * This is the model class for table "CarENDetailNames".
 *
 * @property int $id
 * @property string $Name
 */
class CarENDetailNames extends \yii\db\ActiveRecord implements iLegacyImport
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
        return self::getDb()->transaction(
            function ($db) use ($data) {
                $msg = [];
                while ($data) {
                    $name = array_shift($data);
                    self::setIsNewRecord(true);
                    $this->id = $name[0];
                    $this->Name = $name[1];
                    if (!$this->save()) {
                        array_push($msg, [$this->getFirstErrors(), $name]);
                    }
                }

                return $msg;
            }
        );
    }
}
