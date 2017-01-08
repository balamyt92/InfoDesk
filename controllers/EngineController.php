<?php

namespace app\controllers;

use app\models\CarEngineModelsEN;
use app\models\CarEngineModelsEnSearch;
use app\models\CarMarksEN;
use app\models\ModelTypes;
use Yii;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
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
     * @param $ID_Mark
     *
     * @return mixed
     */
    public function actionIndex($ID_Mark)
    {
        $param = Yii::$app->request->queryParams;
        $searchModel = new CarEngineModelsEnSearch();
        $dataProvider = $searchModel->search($param);

        $types = ArrayHelper::map(ModelTypes::find()->asArray()->all(), 'id', 'Name');

        if (isset($param['CarEngineModelsEnSearch'])) {
            $session = Yii::$app->session;
            $session['find-engines'] = $param['CarEngineModelsEnSearch'];
        }

        return $this->render('index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
            'types'        => $types,
            'ID_Mark'      => $ID_Mark,
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
    public function actionCreate($ID_Mark)
    {
        $model = new CarEngineModelsEN();
        $model->ID_Mark = $ID_Mark;
        $session = Yii::$app->session;

        if ($model->load(Yii::$app->request->post())) {
            try {
                $model->save();
            } catch (\Exception $e) {
                Yii::$app->session->setFlash('error', $e->getMessage());
                goto input;
            }
            $session['last-add-engine'] = Yii::$app->request->post()['CarEngineModelsEN'];

            $redirected = [
                'index',
                'ID_Mark' => $model->ID_Mark,
            ];
            if ($session->has('find-engines')) {
                $redirected['CarEngineModelsEnSearch'] = $session['find-engines'];
            }

            return $this->redirect($redirected);
        } else {
            input:
            $types = ArrayHelper::map(ModelTypes::find()->asArray()->all(), 'id', 'Name');
            if (!Yii::$app->request->post() && $session->has('last-add-engine')) {
                $model->Name = $session['last-add-engine']['Name'];
                $model->ID_Type = $session['last-add-engine']['ID_Type'];
            }

            $model->ID_Mark = $ID_Mark;

            $marks = ArrayHelper::map(CarMarksEN::find()
                ->orderBy('Name')
                ->asArray()->all(), 'id', 'Name');
            $types = ArrayHelper::map(ModelTypes::find()->asArray()->all(), 'id', 'Name');

            return $this->render('create', [
                'model' => $model,
                'marks' => $marks,
                'types' => $types,
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
        $session = Yii::$app->session;

        if ($model->load(Yii::$app->request->post())) {
            try {
                $model->save();
            } catch (\Exception $e) {
                Yii::$app->session->setFlash('error', $e->getMessage());
                goto input;
            }

            $redirected = [
                'index',
                'ID_Mark' => $model->ID_Mark,
            ];
            if ($session->has('find-engines')) {
                $redirected['CarEngineModelsEnSearch'] = $session['find-engines'];
            }

            return $this->redirect($redirected);
        } else {
            input:
            $marks = ArrayHelper::map(CarMarksEN::find()
                ->orderBy('Name')
                ->asArray()->all(), 'id', 'Name');
            $types = ArrayHelper::map(ModelTypes::find()->asArray()->all(), 'id', 'Name');

            return $this->render('create', [
                'model' => $model,
                'marks' => $marks,
                'types' => $types,
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
        $session = Yii::$app->session;
        try {
            $this->findModel($id, $ID_Mark)->delete();
        } catch (\Exception $e) {
            $session->setFlash('error', $e->getMessage());
        }

        return $this->redirect([
            'index',
            'ID_Mark'                 => $ID_Mark,
            'CarEngineModelsEnSearch' => $session->has('find-engines') ? $session['find-engines'] : '',
        ]);
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
