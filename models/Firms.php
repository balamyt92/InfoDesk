<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "firms".
 *
 * @property integer $id
 * @property string $Name
 * @property string $Address
 * @property string $Phone
 * @property string $Comment
 * @property integer $Enabled
 * @property string $ActivityType
 * @property string $OrganizationType
 * @property string $District
 * @property string $Fax
 * @property string $Email
 * @property string $URL
 * @property string $OperatingMode
 * @property string $Identifier
 * @property integer $Priority
 */
class Firms extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'firms';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Name', 'Enabled', 'ActivityType', 'Identifier', 'Priority'], 'required'],
            [['Address', 'Comment', 'ActivityType', 'OperatingMode'], 'string'],
            [['Enabled', 'Priority'], 'integer'],
            [['Name'], 'string', 'max' => 75],
            [['Phone', 'District'], 'string', 'max' => 200],
            [['OrganizationType', 'Fax', 'Email', 'URL', 'Identifier'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'Name' => 'Name',
            'Address' => 'Address',
            'Phone' => 'Phone',
            'Comment' => 'Comment',
            'Enabled' => 'Enabled',
            'ActivityType' => 'Activity Type',
            'OrganizationType' => 'Organization Type',
            'District' => 'District',
            'Fax' => 'Fax',
            'Email' => 'Email',
            'URL' => 'Url',
            'OperatingMode' => 'Operating Mode',
            'Identifier' => 'Identifier',
            'Priority' => 'Priority',
        ];
    }
}
