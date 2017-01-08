<?php

namespace app\controllers;

use app\models\CarBodyModelsEN;
use app\models\CarEngineAndBodyCorrespondencesEN;
use app\models\CarEngineAndBodyCorrespondencesENSearch;
use app\models\CarEngineModelsEN;
use app\models\CarModelsEN;
use Yii;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * EngineByBodyController implements the CRUD actions for CarEngineAndBodyCorrespondencesEN model.
 */
class EngineByBodyController extends Controller
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
     * Lists all CarEngineAndBodyCorrespondencesEN models.
     *
     * @return mixed
     */
    public function actionIndex($ID_Mark, $ID_Model, $ID_Body)
    {
        $param = Yii::$app->request->queryParams;
        $searchModel = new CarEngineAndBodyCorrespondencesENSearch();
        $dataProvider = $searchModel->search($param);

        if (isset($param['CarEngineAndBodyCorrespondencesENSearch'])) {
            $session = Yii::$app->session;
            $session['find-engine-by-body'] = $param['CarEngineAndBodyCorrespondencesENSearch'];
        }

        $models = ArrayHelper::map(CarModelsEN::find()
            ->andFilterWhere(['ID_Mark' => $ID_Mark])
            ->orderBy('Name')
            ->asArray()
            ->all(), 'id', 'Name');

        $bodys = ArrayHelper::map(CarBodyModelsEN::find()
            ->andFilterWhere(['ID_Mark' => $ID_Mark, 'ID_Model' => $searchModel->ID_Model])
            ->orderBy('Name')
            ->asArray()
            ->all(), 'id', 'Name');

        return $this->render('index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
            'ID_Mark'      => $ID_Mark,
            'ID_Model'     => $ID_Model,
            'ID_Body'      => $ID_Body,
            'models'       => $models,
            'bodys'        => $bodys,
        ]);
    }

    /**
     * Displays a single CarEngineAndBodyCorrespondencesEN model.
     *
     * @param int $ID_Mark
     * @param int $ID_Model
     * @param int $ID_Body
     * @param int $ID_Engine
     *
     * @return mixed
     */
    public function actionView($ID_Mark, $ID_Model, $ID_Body, $ID_Engine)
    {
        return $this->render('view', [
            'model' => $this->findModel($ID_Mark, $ID_Model, $ID_Body, $ID_Engine),
        ]);
    }

    /**
     * Creates a new CarEngineAndBodyCorrespondencesEN model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @param $ID_Mark
     * @param $ID_Model
     * @param $ID_Body
     *
     * @return mixed
     */
    public function actionCreate($ID_Mark, $ID_Model, $ID_Body)
    {
        $model = new CarEngineAndBodyCorrespondencesEN();
        $session = Yii::$app->session;

        if ($model->load(Yii::$app->request->post())) {
            try {
                $model->save();
            } catch (\Exception $e) {
                Yii::$app->session->setFlash('error', $e->getMessage());
                goto input;
            }
            $session['last-add-engine-by-body'] = Yii::$app->request->post();

            $redirected = [
                'index',
                'ID_Mark'  => $ID_Mark,
                'ID_Model' => $ID_Model,
                'ID_Body'  => $ID_Body,
            ];
            if ($session->has('find-engine-by-body')) {
                $redirected['CarEngineAndBodyCorrespondencesENSearch'] = $session['find-engine-by-body'];
            }

            return $this->redirect($redirected);
        } else {
            input:
            if (!Yii::$app->request->post() && $session->has('last-add-engine-by-body')) {
                $model->load($session['last-add-engine-by-body']);
            }
            $model->ID_Model = $ID_Model;
            $model->ID_Mark = $ID_Mark;
            $model->ID_Body = $ID_Body;

            $models = ArrayHelper::map(CarModelsEN::find()
                ->andFilterWhere(['ID_Mark' => $ID_Mark])
                ->orderBy('Name')
                ->asArray()->all(), 'id', 'Name');

            $bodys = ArrayHelper::map(CarBodyModelsEN::find()
                ->andFilterWhere(['ID_Mark' => $ID_Mark, 'ID_Model' => $ID_Model])
                ->orderBy('Name')
                ->asArray()->all(), 'id', 'Name');

            $engines = ArrayHelper::map(CarEngineAndBodyCorrespondencesEN::find()
                ->select('e.id as id, e.Name as Name')
                ->leftJoin('CarEngineModelsEN e', 'e.id=CarEngineAndBodyCorrespondencesEN.ID_Engine')
                ->andFilterWhere(['ID_Model' => $ID_Model])
                ->groupBy('e.id')
                ->orderBy('e.Name')
                ->asArray()->all(), 'id', 'Name');

            return $this->render('create', [
                'model'     => $model,
                'ID_Mark'   => $ID_Mark,
                'ID_Model'  => $ID_Model,
                'ID_Body'   => $ID_Body,
                'models'    => $models,
                'engines'   => $engines,
                'bodys'     => $bodys,
            ]);
        }
    }

    /**
     * Updates an existing CarEngineAndBodyCorrespondencesEN model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param int $ID_Mark
     * @param int $ID_Model
     * @param int $ID_Body
     * @param int $ID_Engine
     *
     * @return mixed
     */
    public function actionUpdate($ID_Mark, $ID_Model, $ID_Body, $ID_Engine)
    {
        $model = $this->findModel($ID_Mark, $ID_Model, $ID_Body, $ID_Engine);
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
                'ID_Body'  => $ID_Body,
            ];
            if ($session->has('find-engine-by-body')) {
                $redirected['CarEngineAndBodyCorrespondencesENSearch'] = $session['find-engine-by-body'];
            }

            return $this->redirect($redirected);
        } else {
            input:
            $model->ID_Model = $ID_Model;
            $model->ID_Mark = $ID_Mark;
            $model->ID_Body = $ID_Body;
            $model->ID_Engine = $ID_Engine;

            $models = ArrayHelper::map(CarModelsEN::find()
                ->andFilterWhere(['ID_Mark' => $ID_Mark])
                ->orderBy('Name')
                ->asArray()->all(), 'id', 'Name');

            $bodys = ArrayHelper::map(CarBodyModelsEN::find()
                ->andFilterWhere(['ID_Mark' => $ID_Mark, 'ID_Model' => $ID_Model])
                ->orderBy('Name')
                ->asArray()->all(), 'id', 'Name');

            $engines = ArrayHelper::map(CarEngineAndBodyCorrespondencesEN::find()
                ->select('e.id as id, e.Name as Name')
                ->leftJoin('CarEngineModelsEN e', 'e.id=CarEngineAndBodyCorrespondencesEN.ID_Engine')
                ->andFilterWhere(['ID_Model' => $ID_Model])
                ->groupBy('e.id')
                ->orderBy('e.Name')
                ->asArray()->all(), 'id', 'Name');

            return $this->render('update', [
                'model'     => $model,
                'ID_Mark'   => $ID_Mark,
                'ID_Model'  => $ID_Model,
                'ID_Body'   => $ID_Body,
                'models'    => $models,
                'engines'   => $engines,
                'bodys'     => $bodys,
            ]);
        }
    }

    /**
     * Deletes an existing CarEngineAndBodyCorrespondencesEN model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param int $ID_Mark
     * @param int $ID_Model
     * @param int $ID_Body
     * @param int $ID_Engine
     *
     * @return mixed
     */
    public function actionDelete($ID_Mark, $ID_Model, $ID_Body, $ID_Engine)
    {
        $this->findModel($ID_Mark, $ID_Model, $ID_Body, $ID_Engine)->delete();

        return $this->redirect(['index']);
    }

    public function actionGetBodys($ID_Model)
    {
        $models = CarBodyModelsEN::find()
            ->select('id, Name')
            ->andFilterWhere(['ID_Model' => $ID_Model])
            ->orderBy('Name')->asArray()->all();

        echo '<option></option>';
        foreach ($models as $value) {
            echo "<option value='{$value['id']}'>{$value['Name']}</option>";
        }
    }

    public function actionGetEngines($ID_Model)
    {
        $models = CarEngineAndBodyCorrespondencesEN::find()
            ->select('e.id, e.Name')
            ->leftJoin('CarEngineModelsEN e', 'e.id=CarEngineAndBodyCorrespondencesEN.ID_Engine')
            ->andFilterWhere(['ID_Model' => $ID_Model])
            ->groupBy('e.id')
            ->orderBy('e.Name')
            ->asArray()->all();

        echo '<option></option>';
        foreach ($models as $value) {
            echo "<option value='{$value['id']}'>{$value['Name']}</option>";
        }
    }

    /**
     * Finds the CarEngineAndBodyCorrespondencesEN model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param int $ID_Mark
     * @param int $ID_Model
     * @param int $ID_Body
     * @param int $ID_Engine
     *
     * @throws NotFoundHttpException if the model cannot be found
     *
     * @return CarEngineAndBodyCorrespondencesEN the loaded model
     */
    protected function findModel($ID_Mark, $ID_Model, $ID_Body, $ID_Engine)
    {
        if (($model = CarEngineAndBodyCorrespondencesEN::findOne(['ID_Mark' => $ID_Mark, 'ID_Model' => $ID_Model, 'ID_Body' => $ID_Body, 'ID_Engine' => $ID_Engine])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
