<?php

namespace app\controllers;

use app\models\Firms;
use app\models\StatFirmsQuery;
use app\models\statistic\ParamForm;
use app\models\StatPartsQuery;
use app\models\StatServiceQuery;
use app\models\User;
use Yii;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\Response;

class StatisticController extends Controller
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
                        'allow'         => true,
                        'roles'         => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return User::isUserAdmin(Yii::$app->user->identity->username);
                        },
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

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * @return string
     */
    public function actionIndex()
    {
        $model = new ParamForm();
        $param = Yii::$app->request->get();
        if ($model->load($param) && $model->validate()) {
            $graphics = $this->getGraphicsModel($model);

            return $this->render('index', [
                'model'    => $model,
                'graphics' => $graphics,
            ]);
        } else {
            // default select operators
            $model->operators = [
                0 => '2',
                1 => '3',
                2 => '4',
                3 => '5',
                4 => '6',
            ];

            return $this->render('index', [
                'model'    => $model,
                'graphics' => null,
            ]);
        }
    }

    /**
     * Search firms.
     *
     * @param string $q
     * @param int    $id
     *
     * @return array
     */
    public function actionSearchFirm($q = null, $id = null)
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            $query = new Query();
            $query->select('id, name AS text, address')
                ->from('Firms')
                ->where(['like', 'name', $q])
                ->orderBy(['name' => SORT_ASC])
                ->limit(50);
            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
        } elseif ($id > 0) {
            $out['results'] = ['id' => $id, 'text' => Firms::find()->name];
        }

        return $out;
    }

    /**
     * @param $model \app\models\statistic\ParamForm
     *
     * @return array
     */
    private function getGraphicsModel($model)
    {
        // disable stupid sql mode
        $sql_set_mode = "set sql_mode = ''";
        $connection = Yii::$app->getDb();
        $connection->createCommand($sql_set_mode)->execute();

        $series = [];
        $date_start = date('Y-m-d H:i:s', strtotime($model->date_start));
        $date_end = date('Y-m-d H:i:s', strtotime($model->date_end));

        // select all days in between date
        $sql = "select * from 
            (select adddate('1970-01-01',t4.i*10000 + t3.i*1000 + t2.i*100 + t1.i*10 + t0.i) selected_date from
             (select 0 i union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t0,
             (select 0 i union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t1,
             (select 0 i union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t2,
             (select 0 i union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t3,
             (select 0 i union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t4) v
            where selected_date between :day_start - INTERVAL 1 DAY and :day_end
        ";
        $categories = ArrayHelper::getColumn($connection->createCommand(
            $sql,
            [
                ':day_start' => $date_start,
                ':day_end'   => $date_end,
            ]
        )->queryAll(), 'selected_date');

        // запчасти
        if (in_array('0', $model->sections)) {
            $parts = ArrayHelper::map(StatPartsQuery::find()
                ->select('DATE(date_time) as date, COUNT(*) as value')
                ->where(['between', 'date_time', $date_start, $date_end])
                ->orderBy('date_time')
                ->groupBy('DAY(date_time)')
                ->asArray()
                ->all(), 'date', 'value');
            $series[] = [
                'name' => 'Запчасти',
                'data' => array_map(function ($e) use ($parts) {
                    return isset($parts[$e]) ? $parts[$e] : 0;
                }, $categories),
            ];
        }
        // услуги
        if (in_array('1', $model->sections)) {
            $service = ArrayHelper::map(StatServiceQuery::find()
                ->select('DATE(date_time) as date, COUNT(*) as value')
                ->where(['between', 'date_time', $date_start, $date_end])
                ->orderBy('date_time')
                ->groupBy('DAY(date_time)')
                ->asArray()
                ->all(), 'date', 'value');
            $series[] = [
                'name' => 'Услуги',
                'data' => array_map(function ($e) use ($service) {
                    return isset($service[$e]) ? $service[$e] : 0;
                }, $categories),
            ];
        }
        // поиск
        if (in_array('2', $model->sections)) {
            $search = ArrayHelper::map(StatFirmsQuery::find()
                ->select('DATE(date_time) as date, COUNT(*) as value')
                ->where(['between', 'date_time', $date_start, $date_end])
                ->orderBy('date_time')
                ->groupBy('DAY(date_time)')
                ->asArray()
                ->all(), 'date', 'value');
            $series[] = [
                'name' => 'Поиск фирм',
                'data' => array_map(function ($e) use ($search) {
                    return isset($search[$e]) ? $search[$e] : 0;
                }, $categories),
            ];
        }

        return ['categories' => $categories, 'series' => $series];
    }
}
