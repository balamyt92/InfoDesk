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
use app\models\CarPresenceEN;
use app\models\CatalogNumbersEN;
use app\models\Firms;
use app\models\ServicePresence;
use app\models\Services;
use yii\console\Controller;
use yii\db\ActiveRecord;
use yii\db\IntegrityException;

class LegacyImportController extends Controller
{
    private $config;

    public function actionIndex()
    {
        $this->initImport();
        $this->runImport();
    }

    /**
     *    Инициализация параметров импорта.
     */
    private function initImport()
    {
        $this->config = require __DIR__ . '/../config/legacy-import.php';
    }

    /**
     *    Запуск импорта.
     */
    private function runImport()
    {
        $this->log('Запуск импорта');

        $files = $this->config['files'];
        foreach ($files as $file) {
            $this->parseFile(
                __DIR__ . '/../import/' . $file[0],
                $file[1],
                substr($file[0], 0, count($file[0]) - 5)
            );
        }

        $this->log('Импорт завершён', 'fin');
    }

    /**
     * Функция логирования.
     *
     * @param $message
     * @param string $status передает статут выполнения процесса передаваемы с собощением
     *                       run - импорт запущен,
     *                       err - ошибка импорта,
     *                       wrn - предупреждение,
     *                       fin - импорт завершон
     */
    private function log($message, $status = 'run')
    {
        if (is_array($message)) {
            echo $status, ':', PHP_EOL;
            echo print_r($message, true);
        } else {
            echo $status, ' : ', $message, PHP_EOL;
        }
    }

    /**
     * Функция парсит файл и возвращает двумерный массив[строка][ячейка].
     *
     * @param $filename
     * @param null $column ожидаемое число столбцов
     */
    private function parseFile($filename, $column = null, $table)
    {
        $f = file_get_contents($filename);
        $f = iconv('WINDOWS-1251', 'UTF-8', $f);
        file_put_contents($filename . '.new', $f);

        $handle = fopen($filename . '.new', 'r');
        $result = [];

        $class_name = "app\models\\" . $table;
        /** @var ActiveRecord $model */
        $model = new $class_name();

        $this->log('Очистка таблицы ' . $table);
        \Yii::$app->db->createCommand('SET foreign_key_checks = 0;')->execute();
        \Yii::$app->db->createCommand()->truncateTable($model::tableName())->execute();
        $this->log('Заполнение таблицы ' . $table);

        while (!feof($handle)) {
            $firm = fgetcsv($handle, 0, ';', '^');
            while (count($firm) < $column && !feof($handle)) {
                $tmp = fgetcsv($handle, 0, ';', '^');
                $tmp[0] = array_pop($firm) . "\n" . $tmp[0];
                $firm = array_merge($firm, $tmp);
            }
            if ($firm != false) {
                $result[] = $firm;
            }
            if (count($result) >= 1000 || feof($handle)) {
                if ($table == 'Service') {
                    // сортируем по ID_Parent для устранения ошибки внешнего ключа
                    // [ID_Parent] => Id  Parent is invalid.
                    usort($result, function ($a, $b) {
                        if ($a[3] == $b[3]) {
                            return 0;
                        }

                        return ($a[3] < $b[3]) ? -1 : 1;
                    });
                }
                $this->loadToBase($model, $result);
                $result = [];
            }
        }

        $this->log('Таблица ' . $table . ' импортирована (успешно записано строк ' . $model->find()->count() . ')');
        // если были отключены внешние ключи, включим их
        \Yii::$app->db->createCommand('SET foreign_key_checks = 1;')->execute();
        fclose($handle);
        unlink($filename . '.new');
    }

    /**
     * Функция заливающая данные в базу
     * Функция ожидает что в базе созданы таблица в которых порядок столбцов
     * соответсвует порядку столбцов в передаваемом массвие $data.
     *
     * @param $model ActiveRecord
     * @param array $data данные на запись в таблицу
     *
     * @internal param string $table имя таблицы
     */
    private function loadToBase($model, $data)
    {
        if ($model !== null) {
            while ($data) {
                $tmp = array_splice($data, 0, 1000);
                try {
                    $model->loadData($tmp);
                } catch (IntegrityException $e) {
                    // произошла ошибка записи, скорее всего из-за дублирования ключа
                    // перезапускаем заливу но уже по одному элементу
                    while ($tmp) {
                        $once = array_pop($tmp);
                        try {
                            $model->loadData($once);
                        } catch (IntegrityException $e) {
                            // поймали гадину
                            $this->log($e, 'err');
                            $this->log($once, 'err');
                        }
                    }
                } catch (\Error $e) {
                    // все плохо
                    $this->log($e, 'err');
                }
                $this->log('Записано строк ' . $model->find()->count());
            }
        }
    }
}
