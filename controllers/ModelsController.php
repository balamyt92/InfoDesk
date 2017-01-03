<?php

namespace app\controllers;

use app\models\CarMarksEN;
use app\models\ModelTypes;
use Yii;
use app\models\CarModelsEN;
use app\models\CarModelsEnSearch;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ModelsController implements the CRUD actions for CarModelsEN model.
 */
class ModelsController extends Controller
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
     * Lists all CarModelsEN models.
     * @return mixed
     */
    public function actionIndex()
    {
        $param = Yii::$app->request->queryParams;
        $searchModel = new CarModelsEnSearch();
        $dataProvider = $searchModel->search($param);
        $types = ArrayHelper::map(ModelTypes::find()->all(), 'id', 'Name');

        if(isset($param['CarModelsEnSearch'])) {
            $session = Yii::$app->session;
            $session['find-models'] = $param['CarModelsEnSearch'];
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model_types'  => $types,
            'ID_Mark'      => $param['ID_Mark']
        ]);
    }

    /**
     * Displays a single CarModelsEN model.
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
     * Creates a new CarModelsEN model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new CarModelsEN();
        $session = Yii::$app->session;

        if ($model->load(Yii::$app->request->post())) {
            try {
                $model->save();
            } catch (\Exception $e) {
                Yii::$app->session->setFlash('error', $e->getMessage());
                goto input;
            }
            $session['last-add-model'] = Yii::$app->request->post();
            return $this->redirect([
                'index',
                'ID_Mark' => $model->ID_Mark,
                'CarModelsEnSearch' => $session->has('find-models') ? $session['find-models'] : '',
            ]);
        } else {
            input:
            $marks_list = ArrayHelper::map(CarMarksEN::find()->orderBy('Name')->all(), 'id', 'Name');
            $types = ArrayHelper::map(ModelTypes::find()->all(), 'id', 'Name');
            $params = Yii::$app->request->queryParams;
            if(!Yii::$app->request->post() && $session->has('last-add-model')) {
                $model->load($session['last-add-model']);
            }
            $model->ID_Mark = $params['ID_Mark'];
            return $this->render('create', [
                'model' => $model,
                'model_types' => $types,
                'marks_list' => $marks_list,
            ]);
        }
    }

    /**
     * Updates an existing CarModelsEN model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            try {
                $model->save();
            } catch (\Exception $e) {
                Yii::$app->session->setFlash('error', $e->getMessage());
                goto input;
            }
            $session = Yii::$app->session;
            return $this->redirect([
                'index',
                'ID_Mark' => $model->ID_Mark,
                'CarModelsEnSearch' => $session->has('find-models') ? $session['find-models'] : '',
            ]);
        } else {
            input:
            $marks_list = ArrayHelper::map(CarMarksEN::find()->orderBy('Name')->all(), 'id', 'Name');
            $types = ArrayHelper::map(ModelTypes::find()->all(), 'id', 'Name');
            return $this->render('update', [
                'model' => $model,
                'model_types' => $types,
                'marks_list' => $marks_list,
            ]);
        }
    }

    /**
     * Deletes an existing CarModelsEN model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $mark = $model->ID_Mark;
        try {
            $model->delete();
        } catch (\Exception $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
        $session = Yii::$app->session;
        return $this->redirect([
            'index',
            'ID_Mark' => $mark,
            'CarModelsEnSearch' => $session->has('find-models') ? $session['find-models'] : '',
        ]);
    }

    /**
     * Finds the CarModelsEN model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CarModelsEN the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CarModelsEN::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Display body of model
     *
     * @param  int $id  model id
     *
     * @return mixed
     */
    public function actionBodys($id)
    {
        return $this->redirect(['body/by-model', 'ID_Model' => $id]);
    }


    /**
     * Display engine of model
     *
     * @param  int $id  model id
     *
     * @return mixed
     */
    public function actionEngines($id)
    {
        return $this->redirect(['engine/by-model', 'ID_Model' => $id]);
    }
}
