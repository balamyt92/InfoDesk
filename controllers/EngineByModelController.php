<?php

namespace app\controllers;

use app\models\CarEngineModelsEN;
use app\models\CarModelsEN;
use Yii;
use app\models\CarEngineAndModelCorrespondencesEN;
use app\models\CarEngineAndModelCorrespondencesENSearch;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * EngineByModelController implements the CRUD actions for CarEngineAndModelCorrespondencesEN model.
 */
class EngineByModelController extends Controller
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
     * Lists all CarEngineAndModelCorrespondencesEN models.
     * @param $ID_Mark
     * @param $ID_Model
     * @return mixed
     */
    public function actionIndex($ID_Mark, $ID_Model)
    {
        $param = Yii::$app->request->queryParams;
        $searchModel = new CarEngineAndModelCorrespondencesENSearch();
        $dataProvider = $searchModel->search($param);

        $models = ArrayHelper::map(CarModelsEN::find()
            ->andFilterWhere(['ID_Mark' => $ID_Mark])
            ->orderBy('Name')
            ->asArray()
            ->all(), 'id', 'Name');

        if (isset($param['CarEngineAndModelCorrespondencesENSearch'])) {
            $session = Yii::$app->session;
            $session['find-engine-by-model'] = $param['CarEngineAndModelCorrespondencesENSearch'];
        }

        return $this->render('index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
            'ID_Mark'      => $ID_Mark,
            'ID_Model'     => $ID_Model,
            'models'       => $models
        ]);
    }

    /**
     * Displays a single CarEngineAndModelCorrespondencesEN model.
     * @param integer $ID_Mark
     * @param integer $ID_Engine
     * @param integer $ID_Model
     * @return mixed
     */
    public function actionView($ID_Mark, $ID_Engine, $ID_Model)
    {
        return $this->render('view', [
            'model' => $this->findModel($ID_Mark, $ID_Engine, $ID_Model),
        ]);
    }

    /**
     * Creates a new CarEngineAndModelCorrespondencesEN model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @param $ID_Mark
     * @param $ID_Model
     * @return mixed
     */
    public function actionCreate($ID_Mark, $ID_Model)
    {
        $model = new CarEngineAndModelCorrespondencesEN();
        $session = Yii::$app->session;

        if ($model->load(Yii::$app->request->post())) {
            try {
                $model->save();
            } catch (\Exception $e) {
                Yii::$app->session->setFlash('error', $e->getMessage());
                goto input;
            }
            $session['last-add-engine-by-model'] = Yii::$app->request->post();

            $redirected = [
                'index',
                'ID_Mark'  => $ID_Mark,
                'ID_Model' => $ID_Model,
            ];
            if ($session->has('find-engine-by-model')) {
                $redirected['CarEngineAndModelCorrespondencesENSearch'] = $session['find-engine-by-model'];
            }

            return $this->redirect($redirected);
        } else {
            input:
            if (!Yii::$app->request->post() && $session->has('last-add-engine-by-model')) {
                $model->load($session['last-add-engine-by-model']);
            }

            $model->ID_Model = $ID_Model;
            $model->ID_Mark = $ID_Mark;

            $models = ArrayHelper::map(CarModelsEN::find()
                ->andFilterWhere(['ID_Mark' => $ID_Mark])
                ->orderBy('Name')
                ->asArray()->all(), 'id', 'Name');

            $engines = ArrayHelper::map(CarEngineModelsEN::find()
                ->andFilterWhere(['ID_Mark' => $ID_Mark])
                ->orderBy('Name')
                ->asArray()->all(), 'id', 'Name');

            return $this->render('create', [
                'model'     => $model,
                'ID_Mark'   => $ID_Mark,
                'ID_Model'  => $ID_Model,
                'models'    => $models,
                'engines'   => $engines,
            ]);
        }
    }

    /**
     * Updates an existing CarEngineAndModelCorrespondencesEN model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $ID_Mark
     * @param integer $ID_Engine
     * @param integer $ID_Model
     * @return mixed
     */
    public function actionUpdate($ID_Mark, $ID_Engine, $ID_Model)
    {
        $model = $this->findModel($ID_Mark, $ID_Engine, $ID_Model);
        $session = Yii::$app->session;

        if ($model->load(Yii::$app->request->post())) {
            try {
                $model->save();
            } catch (\Exception $e) {
                $session->setFlash('error', $e->getMessage());
                goto input;
            }
            $redirected = [
                'index',
                'ID_Mark'  => $ID_Mark,
                'ID_Model' => $ID_Model,
            ];
            if ($session->has('find-engine-by-model')) {
                $redirected['CarEngineAndModelCorrespondencesENSearch'] = $session['find-engine-by-model'];
            }

            return $this->redirect($redirected);
        } else {
            input:
            $model->ID_Model = $ID_Model;
            $model->ID_Mark = $ID_Mark;
            $model->ID_Engine = $ID_Engine;

            $models = ArrayHelper::map(CarModelsEN::find()
                ->andFilterWhere(['ID_Mark' => $ID_Mark])
                ->orderBy('Name')
                ->asArray()->all(), 'id', 'Name');

            $engines = ArrayHelper::map(CarEngineModelsEN::find()
                ->andFilterWhere(['ID_Mark' => $ID_Mark])
                ->orderBy('Name')
                ->asArray()->all(), 'id', 'Name');

            return $this->render('update', [
                'model'     => $model,
                'ID_Mark'   => $ID_Mark,
                'ID_Model'  => $ID_Model,
                'models'    => $models,
                'engines'   => $engines,
            ]);
        }
    }

    /**
     * Deletes an existing CarEngineAndModelCorrespondencesEN model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $ID_Mark
     * @param integer $ID_Engine
     * @param integer $ID_Model
     * @return mixed
     */
    public function actionDelete($ID_Mark, $ID_Engine, $ID_Model)
    {
        $session = Yii::$app->session;
        try {
            $this->findModel($ID_Mark, $ID_Engine, $ID_Model)->delete();
        } catch (\Exception $e) {
            $session->setFlash('error', $e->getMessage());
        }

        return $this->redirect([
            'index',
            'ID_Mark'  => $ID_Mark,
            'ID_Model' => $ID_Model,
            'CarEngineAndModelCorrespondencesENSearch' => $session->has('find-engine-by-model') ? $session['find-engine-by-model'] : '',
        ]);
    }

    /**
     * Finds the CarEngineAndModelCorrespondencesEN model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $ID_Mark
     * @param integer $ID_Engine
     * @param integer $ID_Model
     * @return CarEngineAndModelCorrespondencesEN the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($ID_Mark, $ID_Engine, $ID_Model)
    {
        if (($model = CarEngineAndModelCorrespondencesEN::findOne(['ID_Mark' => $ID_Mark, 'ID_Engine' => $ID_Engine, 'ID_Model' => $ID_Model])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
