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
        return self::getDb()->transaction(
            function ($db) use ($data) {
                $msg = [];
                while ($data) {
                    $firm = array_shift($data);
                    self::setIsNewRecord(true);
                    $this->id = $firm[0];
                    $this->Name = $firm[1];
                    $this->Address = $firm[2];
                    $this->Phone = $firm[3];
                    $this->Comment = $firm[4];
                    $this->Enabled = $firm[5];
                    $this->ActivityType = $firm[6];
                    $this->OrganizationType = $firm[7];
                    $this->District = $firm[8];
                    $this->Fax = $firm[9];
                    $this->Email = $firm[10];
                    $this->URL = $firm[11];
                    $this->OperatingMode = $firm[12];
                    $this->Identifier = $firm[13];
                    $this->Priority = $firm[14];
                    if (!$this->save()) {
                        array_push($msg, [$this->getFirstErrors(), $firm]);
                    }
                }

                return $msg;
            }
        );
    }
}
