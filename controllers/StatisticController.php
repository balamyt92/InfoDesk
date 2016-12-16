<?php

namespace app\controllers;

use app\models\Firms;
use app\models\statistic\ParamForm;
use app\models\User;
use Yii;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
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

        if ($model->load(Yii::$app->request->get()) && $model->validate()) {
            return $this->render('index', [
                'model' => $model,
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
                'model' => $model,
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
}
