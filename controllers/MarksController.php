<?php

namespace app\controllers;

use app\models\CarModelsEN;
use app\models\MarkTypes;
use Yii;
use app\models\CarMarksEN;
use app\models\CarMarksEnSearch;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MarksController implements the CRUD actions for CarMarksEN model.
 */
class MarksController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all CarMarksEN models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CarMarksEnSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $types = ArrayHelper::map(MarkTypes::find()->asArray()->all(), 'id', 'Name');

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'mark_types' => $types,
            'err' => false,
        ]);
    }

    /**
     * Displays a single CarMarksEN model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new CarMarksEN model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new CarMarksEN();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            $types = ArrayHelper::map(MarkTypes::find()->asArray()->all(), 'id', 'Name');
            return $this->render('create', [
                'model' => $model,
                'mark_types' => $types,
            ]);
        }
    }

    /**
     * Updates an existing CarMarksEN model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            $types = ArrayHelper::map(MarkTypes::find()->asArray()->all(), 'id', 'Name');
            return $this->render('update', [
                'model' => $model,
                'mark_types' => $types,
            ]);
        }
    }

    /**
     * Deletes an existing CarMarksEN model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        try {
           $this->findModel($id)->delete();
        } catch (\Exception $e) {
           Yii::$app->session->setFlash('error', $e->getMessage());
        }
        return $this->redirect(['index']);
    }

    public function actionModels($id)
    {
        return $this->redirect(['models/index', 'ID_Mark' => $id]);
    }

    /**
     * Finds the CarMarksEN model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CarMarksEN the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CarMarksEN::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
