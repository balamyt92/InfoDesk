<?php

namespace app\controllers;

use app\models\LegacyImportTable;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * Class ImportController.
 */
class ImportController extends \yii\web\Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow'   => true,
                    ],
                    [
                        'actions' => ['logout', 'index', 'start-import'],
                        'allow'   => true,
                        'roles'   => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class'   => VerbFilter::className(),
                'actions' => [
                    'logout' => ['POST'],
                ],
            ],
        ];
    }

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
     * @return array
     *
     * запрос вид 1 import-status&last_id=1
     */
    public function actionImportStatus($last_id)
    {
        $status = LegacyImportTable::find()->where('id>:id', [':id' => $last_id])->all();

        \Yii::$app->response->format = Response::FORMAT_JSON;

        return [$last_id];
    }
}
