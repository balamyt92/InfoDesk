<?php

namespace app\controllers;

use app\models\CarPresenceSearch;
use app\models\Firms;
use app\models\FirmsSearch;
use app\models\ServicePresence;
use app\models\ServicePresenceSearch;
use app\models\User;
use Yii;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * FirmsController implements the CRUD actions for Firms model.
 */
class FirmsController extends Controller
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
     * Lists all Firms models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new FirmsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionPrice($id)
    {
        $filterModel = new CarPresenceSearch();

        $renderDataProvider = $filterModel->search(Yii::$app->request->queryParams);

        $exportDataProvider = false;
        if(Yii::$app->request->post()) {
            // for big pdf generate need more time
            ini_set('max_execution_time', 900);
            $exportDataProvider =  $filterModel->search(Yii::$app->request->queryParams, ['pageSize' => false]);
        }

        // for off sql_mode=only_full_group_by
        $sql_set_mode = "set sql_mode = ''";
        Yii::$app->getDb()->createCommand($sql_set_mode)->execute();

        $names   = $filterModel->getDetailNames($id);
        $marks   = $filterModel->getMarksName($id);
        $models  = $filterModel->getModelsName($id);
        $bodys   = $filterModel->getBodysName($id);
        $engines = $filterModel->getEnginesName($id);

        return $this->render('price', [
            'model'         => $renderDataProvider,
            'exportModel'   => $exportDataProvider ? $exportDataProvider : $renderDataProvider,
            'filterModel'   => $filterModel,
            'names'         => $names,
            'marks'         => $marks,
            'models'        => $models,
            'bodys'         => $bodys,
            'engines'       => $engines,
        ]);
    }

    public function actionService($id)
    {
        $filterModel = new ServicePresenceSearch();

        $query = \app\models\ServicePresence::find()->where('ID_Firm = :id', [':id' => $id]);

        $renderDataProvider = $filterModel->search(Yii::$app->request->queryParams);

        $exportDataProvider = false;
        if(Yii::$app->request->post()) {
            // for big pdf generate need more time
            ini_set('max_execution_time', 900);
            $exportDataProvider =  $filterModel->search(Yii::$app->request->queryParams, ['pageSize' => false]);
        }

        // for off sql_mode=only_full_group_by
        $sql_set_mode = "set sql_mode = ''";
        Yii::$app->getDb()->createCommand($sql_set_mode)->execute();

        return $this->render('service', [
            'model'         => $renderDataProvider,
            'exportModel'   => $exportDataProvider ? $exportDataProvider : $renderDataProvider,
            'filterModel'   => $filterModel,
            'services'      => $filterModel->getServicesName($id),
            'ID_Firm'       => $id,
        ]);
    }


    /**
     * Displays a single Firms model.
     *
     * @param int $id
     *
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Firms model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Firms();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Firms model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param int $id
     *
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Firms model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param int $id
     *
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionSearch($q = null, $id = null)
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
            $out['results'] = ['id' => $id, 'text' => Firms::find($id)->name];
        }

        return $out;
    }

    /**
     * Finds the Firms model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param int $id
     *
     * @throws NotFoundHttpException if the model cannot be found
     *
     * @return Firms the loaded model
     */
    protected function findModel($id)
    {
        if (($model = Firms::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Updates an existing Service model.
     *
     * @param int    $ID_Service
     * @param int    $ID_Firm
     * @param string $Comment
     *
     * @return mixed
     */
    public function actionServiceUpdate($ID_Service, $ID_Firm, $Comment)
    {
        $model = $this->findService($ID_Service, $ID_Firm, $Comment);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['firms/service', 'id' => $ID_Firm]);
        } else {
            $items = ArrayHelper::map(
                \app\models\Services::find()
                    ->where('ID_Parent IS NOT NULL')
                    ->orderBy('Name')
                    ->asArray()->all(), 'id', 'Name');
            return $this->render('service_update', [
                'model' => $model,
                'items' => $items,
            ]);
        }
    }

    public function actionServiceAdd($ID_Firm)
    {
        $model = new ServicePresence();
        $model->ID_Firm = $ID_Firm;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['firms/service', 'id' => $ID_Firm]);
        } else {
            $items = ArrayHelper::map(
                \app\models\Services::find()
                    ->where('ID_Parent IS NOT NULL')
                    ->orderBy('Name')
                    ->asArray()->all(), 'id', 'Name');
            return $this->render('service_add', [
                'model' => $model,
                'items' => $items,
            ]);
        }
    }

    public function actionServiceDelete($ID_Service, $ID_Firm, $Comment)
    {
        $this->findService($ID_Service, $ID_Firm, $Comment)->delete();

        return $this->redirect(['firms/service', 'id' => $ID_Firm]);
    }

    protected function findService($ID_Service, $ID_Firm, $Comment)
    {
        $model = ServicePresence::find()
            ->andFilterWhere([
                'ID_Service' => $ID_Service,
                'ID_Firm' => $ID_Firm,
            ])
            ->andFilterWhere(['like', 'Comment', $Comment])->one();

        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
