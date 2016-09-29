<?php

namespace app\controllers;

use app\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;

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
                        'actions'       => ['logout', 'index', 'update', 'delete', 'view', 'create'],
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
        return $this->render('index');
    }
}
