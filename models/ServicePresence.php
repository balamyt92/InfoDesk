<?php

namespace app\models;

/**
 * This is the model class for table "ServicePresence".
 *
 * @property int $ID_Service
 * @property int $ID_Firm
 * @property string $Comment
 * @property string $CarList
 * @property string $Coast
 * @property Firms $iDFirm
 * @property Services $iDService
 */
class ServicePresence extends \yii\db\ActiveRecord implements iLegacyImport
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ServicePresence';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ID_Service', 'ID_Firm', 'Comment'], 'required'],
            [['ID_Service', 'ID_Firm'], 'integer'],
            [['Comment', 'CarList', 'Coast'], 'string'],
            [['ID_Firm'], 'exist', 'skipOnError' => true, 'targetClass' => Firms::className(), 'targetAttribute' => ['ID_Firm' => 'id']],
            [['ID_Service'], 'exist', 'skipOnError' => true, 'targetClass' => Services::className(), 'targetAttribute' => ['ID_Service' => 'id']],
            [['update_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ID_Service' => 'Услуга',
            'ID_Firm'    => 'Id Firm',
            'Comment'    => 'Коментарий',
            'CarList'    => 'Обслуживаемые автомобили',
            'Coast'      => 'Цена',
            'update_at'  => 'Дата обновления',
        ];
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
    public function getIDService()
    {
        return $this->hasOne(Services::className(), ['id' => 'ID_Service']);
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
                    $service = array_shift($data);
                    self::setIsNewRecord(true);
                    $this->ID_Service = $service[0];
                    $this->ID_Firm = $service[1];
                    $this->Comment = $service[2];
                    $this->CarList = $service[3];
                    $this->Coast = $service[4];
                    if (!$this->save()) {
                        array_push($msg, [$this->getFirstErrors(), $service]);
                    }
                }

                return $msg;
            }
        );
    }
}
