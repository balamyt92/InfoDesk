<?php

namespace app\commands;

use app\models\CarBodyModelGroupsEN;
use app\models\CarBodyModelsEN;
use app\models\CarENDetailNames;
use app\models\CarEngineAndBodyCorrespondencesEN;
use app\models\CarEngineAndModelCorrespondencesEN;
use app\models\CarEngineModelGroupsEN;
use app\models\CarEngineModelsEN;
use app\models\CarENLinkedDetailNames;
use app\models\CarMarkGroupsEN;
use app\models\CarMarksEN;
use app\models\CarModelGroupsEN;
use app\models\CarModelsEN;
use app\models\CatalogNumbersEN;
use app\models\ServicePresence;
use yii\console\Controller;
use app\models\Firms;
use app\models\Services;

class LegacyImportController extends Controller
{
	private $config;

    public function actionIndex()
    {
    	$this->initImport();
    	$this->runImport();
    }

    /**
     *	Инициализация параметров импорта
     */
    private function initImport()
    {
    	$this->config = require(__DIR__ . '/../config/legacy-import.php');
    }

    /**
     *	Запуск импорта
     */
    private function runImport()
    {
    	$this->log("Запуск импорта");

    	$files = $this->config["files"];
    	foreach ($files as $file) {
    		$data = $this->parseFile(__DIR__ . '/../import/' . $file[0], $file[1]);
    		$tableName = substr($file[0], 0, count($file[0]) - 5);
    		$this->loadToBase($tableName, $data);
    	}

    	$this->log("Импорт завершён", "fin");
    }

    /**
     * Функция логирования
     * @param $message
     * @param string $status передает статут выполнения процесса передаваемы с собощением
     *  run - импорт запущен,
     *  err - ошибка импорта,
     *  wrn - предупреждение,
     *  fin - импорт завершон
     */
    private function log($message, $status = 'run')
    {
        if(is_array($message)) {
            echo $status, ':', PHP_EOL;
            echo print_r($message, true);
        } else {
		    echo $status, ' : ', $message, PHP_EOL;
        }
    }

    /**
     * Функция парсит файл и возвращает двумерный массив[строка][ячейка]
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
                $firm = array_merge($firm, $tmp);
            }
            if($firm != false) {
            	array_push($result, $firm);
            }
        }
        fclose($handle);
        //unlink($filename . '.new');
        return $result;
    }

    /**
     * Функция заливающая данные в базу
     * Функция ожидает что в базе созданы таблица в которых порядок столбцов
     * соответсвует порядку столбцов в передаваемом массвие $data
     * @param string $table имя таблицы
     * @param array $data данные на запись в таблицу
     * @return boolean возвращает false если произошла ошибка импорта
     */

    private function loadToBase($table, $data)
    {
        $model = null;

        switch ($table) {
            case "Firms":
                $model = new Firms();
                break;
            case "Services":
                $model = new Services();
                // сортируем по ID_Parent для устранения ошибки внешнего ключа
                // [ID_Parent] => Id  Parent is invalid.
                usort($data, function ($a , $b) {
                    if($a[3] == $b[3]) return 0;
                    return ($a[3] < $b[3]) ?  -1 : 1;
                });
                break;
            case "ServicePresence":
                $model = new ServicePresence();
                break;
            case "CarENDetailNames":
                $model = new CarENDetailNames();
                break;
            case "CarENLinkedDetailNames":
                $model = new CarENLinkedDetailNames();
                break;
            case "CarMarksEN":
                $model = new CarMarksEN();
                break;
            case "CarMarkGroupsEN":
                $model = new CarMarkGroupsEN();
                break;
            case "CarModelsEN":
                $model = new CarModelsEN();
                break;
            case "CarModelGroupsEN":
                $model = new CarModelGroupsEN();
                break;
            case "CarBodyModelsEN":
                $model = new CarBodyModelsEN();
                break;
            case "CarBodyModelGroupsEN":
                $model = new CarBodyModelGroupsEN();
                break;
            case "CarEngineModelGroupsEN":
                $model = new CarEngineModelGroupsEN();
                break;
            case "CarEngineModelsEN":
                $model = new CarEngineModelsEN();
                break;
            case "CarEngineAndModelCorrespondencesEN":
                $model = new CarEngineAndModelCorrespondencesEN();
                break;
            case "CarEngineAndBodyCorrespondencesEN":
                $model = new CarEngineAndBodyCorrespondencesEN();
                break;
            case "CatalogNumbersEN":
                $model = new CatalogNumbersEN();
                break;
                // TODO: дописать все ожидаемые входные файлы
        }

        if($model !== null) {

            \Yii::$app->db->createCommand("SET foreign_key_checks = 0;")->execute();
            $this->log("Очистка таблицы " . $table);

            if(!($model::deleteAll() >= 0)) {
                $this->log('Ошибка очисти таблицы ' . $table . '. Импорт прерван', 'err');
                return false;
            }

            $this->log('Заполнение таблицы ' . $table . ' (кол-во записей ' . count($data) . ')');

            while ($data) {
                $tmp = array_splice($data, 0, 100);
                $msg = $model->loadData($tmp);
                if(count($msg) > 0) {
                    $this->log($msg, 'wrn');
                }
                $this->log("Осталось строк " . count($data));
            }

            $this->log('Таблица ' . $table . ' импортирована (успешно записано строк ' . $model->find()->count() . ')');

            // если были отключены внешние ключи, включим их
            \Yii::$app->db->createCommand("SET foreign_key_checks = 1;")->execute();
        }
        else {
            $this->log("Ошибка загрузки в базу, не правильное имя таблицы - " . $table, 'wrn');
        }

        return true;
    }
}
