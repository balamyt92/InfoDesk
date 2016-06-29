<?php

namespace app\controllers;

use app\models\LegacyImportTable;

/**
 * Class ImportController.
 */
class ImportController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Запуск импорта в фоновом режиме.
     *
     * @return string
     */
    public function actionStartImport()
    {
        // todo: заплить проверку статуса импорта

        exec('php -q /app/yii legacy-import < /dev/null > /app/runtime/log/import.log');
//        $result = $this->parseFile(__DIR__ . '/../import/CarPresenceEN.txt');
//        return serialize($result[count($result) - 1]);
        // Возвращать статус запуслии или уже запущенна
        return 'start';
    }

    /**
     * Проверка статуса импорта.
     *
     * @param int $last_id поледнее полученное сообщение
     *
     * @return $this
     *
     * запрос вид 1 import-status&last_id=1
     */
    public function actionImportStatus($last_id)
    {
        $status = LegacyImportTable::find()->where('id>:id', [':id' => $last_id])->all();
        //return serialize($status);
        return $last_id;
    }
}
