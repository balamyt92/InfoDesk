<?php

namespace app\commands;

use yii\console\Controller;
use app\models\Firms;

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
    		$tablename = substr($file[0], 0, count($file[0]) - 5);
    		$this->loadToBase($tablename, $data);
    	}

    	$this->log("Импорт завершон", "fin");
    }

    /**
     * Функция логирования
     * @param status string передает статут выполнения процесса передаваемы с собощением
     * run - импорт запущен, err - ошибка импорта, wrn - предупреждение
     * fin - импорт завершон
     */
    private function log($message, $status = 'run')
    {
		echo $status, ' : ', $message, PHP_EOL;
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
        unlink($filename . '.new');
        return $result;
    }

    /**
     * Функция заливающая данные в базу
     * Функция ожидает что в базе созданы таблица в которых порядок столбцов соответсвует порядку столбцов в передаваемом @param data
     */

    private function loadToBase($table, $data)
    {
    	$model = new $table();
    	//echo var_dump($model);
    	// while (count($data) < 100) {
    	// 	$model->load
    	// }
    }
}
