<?php

namespace app\controllers;

use Yii;
use app\models\CarBodyModelsEN;
use app\models\ModelTypes;
use app\models\CarModelsEN;
use app\models\CarBodyModelsEnSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

/**
 * BodyController implements the CRUD actions for CarBodyModelsEN model.
 */
class BodyController extends Controller
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
     * Lists all CarBodyModelsEN models.
     * @return mixed
     */
    public function actionIndex($ID_Mark)
    {
        $searchModel = new CarBodyModelsEnSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $models = ArrayHelper::map(CarModelsEN::find()
            ->andFilterWhere(['ID_Mark' => $ID_Mark])
            ->orderBy('Name')
            ->asArray()->all(), 'id', 'Name');
        $types = ArrayHelper::map(ModelTypes::find()->asArray()->all(), 'id', 'Name');

        return $this->render('index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
            'types'        => $types,
            'models'       => $models,
            'ID_Mark'      => $ID_Mark,
        ]);
    }

    /**
     * Displays a single CarBodyModelsEN model.
     * @param integer $id
     * @param integer $ID_Mark
     * @param integer $ID_Model
     * @return mixed
     */
    public function actionView($id, $ID_Mark, $ID_Model)
    {
        return $this->render('view', [
            'model' => $this->findModel($id, $ID_Mark, $ID_Model),
        ]);
    }

    /**
     * Creates a new CarBodyModelsEN model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($ID_Mark, $ID_Model = null)
    {
        $model = new CarBodyModelsEN();
        $session = Yii::$app->session;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $session['add-body-by-model'] = [
                'ID_Model' => $model->ID_Model,
                'Name'     => $model->Name,
                'ID_Type'  => $model->ID_Type,
            ];
            return $this->redirect([
                'index',
                'ID_Mark'               => $model->ID_Mark,
                'CarBodyModelsEnSearch' => [
                    'ID_Model' => $model->ID_Model,
                    'Name'     => '',
                    'ID_Type'  => '',
                ]
            ]);
        } else {
            $models = ArrayHelper::map(CarModelsEN::find()
            ->andFilterWhere(['ID_Mark' => $ID_Mark])
            ->orderBy('Name')
            ->asArray()->all(), 'id', 'Name');
            $types = ArrayHelper::map(ModelTypes::find()->asArray()->all(), 'id', 'Name');

            if ($session->has('add-body-by-model')) {
                $model->ID_Model = $session['add-body-by-model']['ID_Model'];
                $model->Name     = $session['add-body-by-model']['Name'];
                $model->ID_Type  = $session['add-body-by-model']['ID_Type'];
            }
            if($ID_Model) {
                $model->ID_Model = $ID_Model;
            }
            $model->ID_Mark = $ID_Mark;
            return $this->render('create', [
                'model'  => $model,
                'types'  => $types,
                'models' => $models,
            ]);
        }
    }

    /**
     * Updates an existing CarBodyModelsEN model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @param integer $ID_Mark
     * @param integer $ID_Model
     * @return mixed
     */
    public function actionUpdate($id, $ID_Mark, $ID_Model)
    {
        $model = $this->findModel($id, $ID_Mark, $ID_Model);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect([
                'index',
                'ID_Mark'               => $model->ID_Mark,
                'CarBodyModelsEnSearch' => [
                    'ID_Model' => $model->ID_Model,
                    'Name'     => '',
                    'ID_Type'  => '',
                ]
            ]);
        } else {
            $models = ArrayHelper::map(CarModelsEN::find()
            ->andFilterWhere(['ID_Mark' => $ID_Mark])
            ->orderBy('Name')
            ->asArray()->all(), 'id', 'Name');
            $types = ArrayHelper::map(ModelTypes::find()->asArray()->all(), 'id', 'Name');

            return $this->render('update', [
                'model'  => $model,
                'types'  => $types,
                'models' => $models,
            ]);
        }
    }

    /**
     * Deletes an existing CarBodyModelsEN model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @param integer $ID_Mark
     * @param integer $ID_Model
     * @return mixed
     */
    public function actionDelete($id, $ID_Mark, $ID_Model)
    {
        try {
           $this->findModel($id, $ID_Mark, $ID_Model)->delete();
        } catch (\Exception $e) {
           Yii::$app->session->setFlash('error', $e->getMessage());
        }
        return $this->redirect([
            'index',
            'ID_Mark'               => $ID_Mark,
            'CarBodyModelsEnSearch' => [
                'ID_Model' => $ID_Model,
                'Name' => '',
                'ID_Type' => '',
            ]
        ]);
    }

    /**
     * Finds the CarBodyModelsEN model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @param integer $ID_Mark
     * @param integer $ID_Model
     * @return CarBodyModelsEN the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $ID_Mark, $ID_Model)
    {
        if (($model = CarBodyModelsEN::findOne(['id' => $id, 'ID_Mark' => $ID_Mark, 'ID_Model' => $ID_Model])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
