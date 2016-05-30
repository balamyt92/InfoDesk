<?php

namespace app\controllers;

class ImportController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionStartImport()
    {
    	$result = $this->parseFile(__DIR__ . '/../import/CarPresenceEN.txt');
    	return serialize($result[count($result) - 1]);
    }

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
		return $result;
    }

}
