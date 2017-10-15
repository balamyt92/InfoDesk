<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "Firms".
 *
 * @property int $id
 * @property string $Name
 * @property string $Address
 * @property string $Phone
 * @property string $Comment
 * @property int $Enabled
 * @property string $ActivityType
 * @property string $OrganizationType
 * @property string $District
 * @property string $Fax
 * @property string $Email
 * @property string $URL
 * @property string $OperatingMode
 * @property string $Identifier
 * @property int $Priority
 */
class Firms extends ActiveRecord implements iLegacyImport
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'Firms';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['Name', 'Enabled', 'Identifier', 'Priority'], 'required'],
            [['Address', 'Comment', 'ActivityType', 'OperatingMode'], 'string'],
            [['Enabled', 'Priority'], 'integer'],
            [['Name', 'Phone', 'District'], 'string', 'max' => 200],
            [['OrganizationType', 'Fax', 'Email', 'URL', 'Identifier'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'               => 'ID',
            'Name'             => 'Название организации',
            'Address'          => 'Фактический адрес',
            'Phone'            => 'Телефоны',
            'Comment'          => 'Комментарий',
            'Enabled'          => 'В поиске',
            'ActivityType'     => 'Профиль деятельности',
            'OrganizationType' => 'Юридическое лицо',
            'District'         => 'Район города',
            'Fax'              => 'Факс',
            'Email'            => 'Электронная почта',
            'URL'              => 'Сайт',
            'OperatingMode'    => 'Режим работы',
            'Identifier'       => 'Идентификатор',
            'Priority'         => 'Приоритет',
        ];
    }

    /**
     * @param array $data данные для записи
     *
     * @return string возвращает массив ошибок
     */
    public function loadData($data)
    {
        \Yii::$app->db->createCommand()
            ->batchInsert(
                self::tableName(),
                [
                    'id', 'Name', 'Address', 'Phone', 'Comment',
                    'Enabled', 'ActivityType', 'OrganizationType',
                    'District', 'Fax', 'Email', 'URL', 'OperatingMode',
                    'Identifier', 'Priority'
                ],
                $data
            )->execute();

        return '';
    }
}
