<?php

namespace app\controllers;

use app\models\LegacyImportTable;
use app\models\LegacyImportTableQuery;

/**
 * Class ImportController
 * @package app\controllers
 */

class ImportController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Запуск импорта в фоновом режиме
     * @return string
     */
    public function actionStartImport()
    {
        // todo: заплить проверку статуса импорта

        exec('php -q /app/yii legacy-import < /dev/null > /app/runtime/log/import.log');
//        $result = $this->parseFile(__DIR__ . '/../import/CarPresenceEN.txt');
//        return serialize($result[count($result) - 1]);
        // Возвращать статус запуслии или уже запущенна
        return "start";
    }

    /**
     * Функция парсик файл и возвращает двумерный массив[строка][ячейка]
     * @param $filename
     * @param null $column ожидаемое число столбцов
     * @return array результирующий массив
     */
    private function parseFile($filename, $column = null)
    {
        $f = file_get_contents($filename);
        $f = iconv("WINDOWS-1251", "UTF-8", $f);
        file_put_contents($filename . '.new', $f);

        $handle = fopen($filename . '.new', "r");
        $result = array();

        while (!feof($handle)) {
            $firm = fgetcsv($handle, 0, ";");
            while(count($firm) < $column && !feof($handle)) {
                $tmp = fgetcsv($handle, 0, ";");
                $tmp[0] = array_pop($firm) . $tmp[0];
                $firms = array_merge($firm, $tmp);
            }
            array_push($result, $firm);
        }
        fclose($handle);
        unlink($filename . '.new');
        return $result;
    }

    /**
     * Проверка статуса импорта
     * @param int $last_id поледнее полученное сообщение
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
