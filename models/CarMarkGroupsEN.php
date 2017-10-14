<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "CarMarkGroupsEN".
 *
 * @property int $ID_Group
 * @property int $ID_Mark
 * @property CarMarksEN $iDGroup
 * @property CarMarksEN $iDMark
 */
class CarMarkGroupsEN extends ActiveRecord implements iLegacyImport
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'CarMarkGroupsEN';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ID_Group', 'ID_Mark'], 'required'],
            [['ID_Group', 'ID_Mark'], 'integer'],
            [['ID_Group'], 'exist', 'skipOnError' => true, 'targetClass' => CarMarksEN::className(), 'targetAttribute' => ['ID_Group' => 'id']],
            [['ID_Mark'], 'exist', 'skipOnError' => true, 'targetClass' => CarMarksEN::className(), 'targetAttribute' => ['ID_Mark' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ID_Group' => 'Id  Group',
            'ID_Mark'  => 'Id  Mark',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIDGroup()
    {
        return $this->hasOne(CarMarksEN::className(), ['id' => 'ID_Group']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIDMark()
    {
        return $this->hasOne(CarMarksEN::className(), ['id' => 'ID_Mark']);
    }

    public function loadData($data)
    {
        return self::getDb()->transaction(
            function ($db) use ($data) {
                $msg = [];
                while ($data) {
                    $mark = array_shift($data);
                    self::setIsNewRecord(true);
                    $this->ID_Group = $mark[0];
                    $this->ID_Mark = $mark[1];
                    if (!$this->save()) {
                        array_push($msg, [$this->getFirstErrors(), $mark]);
                    }
                }

                return $msg;
            }
        );
    }
}
