<?php

namespace app\models;

/**
 * This is the model class for table "legacy_import_table".
 *
 * @property int $id
 * @property int $status
 * @property string $message
 */
class LegacyImportTable extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'legacy_import_table';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status'], 'integer'],
            [['message'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'      => 'ID',
            'status'  => 'Status',
            'message' => 'Message',
        ];
    }

    /**
     * {@inheritdoc}
     *
     * @return LegacyImportTableQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new LegacyImportTableQuery(get_called_class());
    }
}
