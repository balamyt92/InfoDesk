<?php

namespace app\controllers;

use app\models\CarEngineModelsEN;
use app\models\CarEngineModelsEnSearch;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * EngineController implements the CRUD actions for CarEngineModelsEN model.
 */
class EngineController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class'   => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all CarEngineModelsEN models.
     *
     * @return mixed
     */
    public function actionIndex($ID_Mark)
    {
        $searchModel = new CarEngineModelsEnSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single CarEngineModelsEN model.
     *
     * @param int $id
     * @param int $ID_Mark
     *
     * @return mixed
     */
    public function actionView($id, $ID_Mark)
    {
        return $this->render('view', [
            'model' => $this->findModel($id, $ID_Mark),
        ]);
    }

    /**
     * Creates a new CarEngineModelsEN model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new CarEngineModelsEN();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id, 'ID_Mark' => $model->ID_Mark]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing CarEngineModelsEN model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param int $id
     * @param int $ID_Mark
     *
     * @return mixed
     */
    public function actionUpdate($id, $ID_Mark)
    {
        $model = $this->findModel($id, $ID_Mark);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id, 'ID_Mark' => $model->ID_Mark]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing CarEngineModelsEN model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param int $id
     * @param int $ID_Mark
     *
     * @return mixed
     */
    public function actionDelete($id, $ID_Mark)
    {
        $this->findModel($id, $ID_Mark)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the CarEngineModelsEN model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param int $id
     * @param int $ID_Mark
     *
     * @throws NotFoundHttpException if the model cannot be found
     *
     * @return CarEngineModelsEN the loaded model
     */
    protected function findModel($id, $ID_Mark)
    {
        if (($model = CarEngineModelsEN::findOne(['id' => $id, 'ID_Mark' => $ID_Mark])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
