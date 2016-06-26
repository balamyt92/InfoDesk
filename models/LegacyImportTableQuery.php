<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[LegacyImportTable]].
 *
 * @see LegacyImportTable
 */
class LegacyImportTableQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return LegacyImportTable[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return LegacyImportTable|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
