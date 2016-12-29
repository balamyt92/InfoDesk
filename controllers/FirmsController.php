<?php

namespace app\controllers;

use app\models\CarBodyModelsEN;
use app\models\CarENDetailNames;
use app\models\CarEngineAndBodyCorrespondencesEN;
use app\models\CarEngineAndModelCorrespondencesEN;
use app\models\CarEngineModelsEN;
use app\models\CarMarksEN;
use app\models\CarModelsEN;
use app\models\CarPresenceEN;
use app\models\CarPresenceSearch;
use app\models\Firms;
use app\models\FirmsSearch;
use app\models\ServicePresence;
use app\models\ServicePresenceSearch;
use app\models\Services;
use app\models\User;
use Yii;
use yii\db\ActiveRecord;
use yii\db\IntegrityException;
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

        $session = Yii::$app->session;
        $session['firms-list-filter'] = isset($_GET['FirmsSearch']) ? $_GET['FirmsSearch'] : '';

        return $this->render('index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Select firm price.
     *
     * @param int $id firm
     *
     * @return mixed
     */
    public function actionPrice($id)
    {
        $filterModel = new CarPresenceSearch();

        $renderDataProvider = $filterModel->search(Yii::$app->request->queryParams);

        $exportDataProvider = false;
        if (Yii::$app->request->post()) {
            // for big pdf generate need more time
            ini_set('max_execution_time', 900);
            $exportDataProvider = $filterModel->search(Yii::$app->request->queryParams, ['pageSize' => false]);
        }

        // for off sql_mode=only_full_group_by
        $sql_set_mode = "set sql_mode = ''";
        Yii::$app->getDb()->createCommand($sql_set_mode)->execute();

        $names = $filterModel->getDetailNames($id);
        $marks = $filterModel->getMarksName($id);
        $models = $filterModel->getModelsName($id);
        $bodys = $filterModel->getBodysName($id);
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
            'ID_Firm'       => $id,
        ]);
    }

    /**
     * Select firm services.
     *
     * @param int $id firm
     *
     * @return mixed
     */
    public function actionService($id)
    {
        $filterModel = new ServicePresenceSearch();

        $query = ServicePresence::find()->where('ID_Firm = :id', [':id' => $id]);

        $renderDataProvider = $filterModel->search(Yii::$app->request->queryParams);

        $exportDataProvider = false;
        if (Yii::$app->request->post()) {
            // for big pdf generate need more time
            ini_set('max_execution_time', 900);
            $exportDataProvider = $filterModel->search(Yii::$app->request->queryParams, ['pageSize' => false]);
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
     * If creation is successful, the browser will be redirected to the 'index' page.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Firms();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'FirmsSearch' => isset($_GET['FirmsSearch']) ? $_GET['FirmsSearch'] : '',]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'FirmsSearch' => isset($_GET['FirmsSearch']) ? $_GET['FirmsSearch'] : '',
            ]);
        }
    }

    /**
     * Updates an existing Firms model.
     * If update is successful, the browser will be redirected to the 'index' page.
     *
     * @param int $id
     *
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $session = Yii::$app->session;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'FirmsSearch' => isset($session['firms-list-filter']) ? $session['firms-list-filter'] : '']);
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
        $session = Yii::$app->session;

        return $this->redirect(['index', 'FirmsSearch' => isset($session['firms-list-filter']) ? $session['firms-list-filter'] : '']);
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

        if ($model->load(Yii::$app->request->post())) {
            try {
                $model->save();
            } catch (IntegrityException $e) {
                $items = ArrayHelper::map(
                    Services::find()
                        ->where('ID_Parent IS NOT NULL')
                        ->orderBy('Name')
                        ->asArray()->all(), 'id', 'Name');

                return $this->render('service_update', [
                    'model' => $model,
                    'items' => $items,
                    'err'   => $e,
                ]);
            }

            return $this->redirect(['firms/service', 'id' => $ID_Firm]);
        } else {
            $items = ArrayHelper::map(
                Services::find()
                    ->where('ID_Parent IS NOT NULL')
                    ->orderBy('Name')
                    ->asArray()->all(), 'id', 'Name');

            return $this->render('service_update', [
                'model' => $model,
                'items' => $items,
                'err'   => false,
            ]);
        }
    }

    /**
     * Add new service in firm.
     *
     * @param int $ID_Firm
     *
     * @return mixed
     */
    public function actionServiceAdd($ID_Firm)
    {
        $model = new ServicePresence();
        $model->ID_Firm = $ID_Firm;
        if ($model->load(Yii::$app->request->post())) {
            try {
                $model->save();
            } catch (IntegrityException $e) {
                $items = ArrayHelper::map(
                    Services::find()
                        ->where('ID_Parent IS NOT NULL')
                        ->orderBy('Name')
                        ->asArray()->all(), 'id', 'Name');

                return $this->render('service_add', [
                    'model' => $model,
                    'items' => $items,
                    'err'   => $e,
                ]);
            }

            return $this->redirect(['firms/service', 'id' => $ID_Firm, 'err' => false]);
        } else {
            $last = ServicePresence::find()->orderBy('update_at DESC')->one();

            $model->ID_Service = $last->ID_Service;
            $model->Comment = $last->Comment;
            $model->CarList = $last->CarList;
            $model->Coast = $last->Coast;

            $items = ArrayHelper::map(
                Services::find()
                    ->where('ID_Parent IS NOT NULL')
                    ->orderBy('Name')
                    ->asArray()->all(), 'id', 'Name');

            return $this->render('service_add', [
                'model' => $model,
                'items' => $items,
                'err'   => false,
            ]);
        }
    }

    /**
     * Delete service in firm.
     *
     * @param int    $ID_Service
     * @param int    $ID_Firm
     * @param string $Comment
     *
     * @return mixed
     */
    public function actionServiceDelete($ID_Service, $ID_Firm, $Comment)
    {
        $this->findService($ID_Service, $ID_Firm, $Comment)->delete();

        return $this->redirect(['firms/service', 'id' => $ID_Firm]);
    }

    /**
     * Delete all services in firm.
     *
     * @param int $ID_Firm
     *
     * @return mixed
     */
    public function actionServiceDeleteAll($ID_Firm)
    {
        ServicePresence::deleteAll('ID_Firm=:id', [':id' => $ID_Firm]);

        return $this->redirect(['firms/service', 'id' => $ID_Firm]);
    }

    /**
     * Find service in firm.
     *
     * @param int    $ID_Service
     * @param int    $ID_Firm
     * @param string $Comment
     *
     * @throws NotFoundHttpException
     *
     * @return ActiveRecord
     */
    protected function findService($ID_Service, $ID_Firm, $Comment)
    {
        $model = ServicePresence::find()
            ->andFilterWhere([
                'ID_Service' => $ID_Service,
                'ID_Firm'    => $ID_Firm,
            ])
            ->andFilterWhere(['like', 'Comment', $Comment])->one();

        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Edit element in price list.
     *
     * @throws NotFoundHttpException
     *
     * @return mixed
     */
    public function actionPriceElementUpdate()
    {
        $params = Yii::$app->request->get();
        if (count($params) < 11) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        $model = $this->findPriceElement($params);

        if ($model->load(Yii::$app->request->post())) {
            try {
                $model->Hash_Comment = md5($model->Comment);
                $model->save();
            } catch (IntegrityException $e) {
                $param = Yii::$app->request->post();
                $items = $this->getItemForPriceEditForm($param['CarPresenceEN']);

                return $this->render('price_update', [
                    'model'   => $model,
                    'err'     => $e,
                    'names'   => $items['names'],
                    'marks'   => $items['marks'],
                    'models'  => $items['models'],
                    'bodys'   => $items['bodys'],
                    'engines' => $items['engines'],
                ]);
            }

            return $this->redirect(['firms/price', 'id' => $params['ID_Firm']]);
        } else {
            $items = $this->getItemForPriceEditForm($params);

            return $this->render('price_update', [
                'model'   => $model,
                'err'     => false,
                'names'   => $items['names'],
                'marks'   => $items['marks'],
                'models'  => $items['models'],
                'bodys'   => $items['bodys'],
                'engines' => $items['engines'],
            ]);
        }
    }

    /**
     * Delete element in price.
     *
     * @throws NotFoundHttpException
     *
     * @return mixed
     */
    public function actionPriceElementDelete()
    {
        $params = Yii::$app->request->get();
        if (count($params) < 11) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        $this->findPriceElement($params)->delete();

        return $this->redirect(['firms/price', 'id' => $params['ID_Firm']]);
    }

    /**
     * Delete all elements in price.
     *
     * @param int $ID_Firm
     *
     * @return mixed
     */
    public function actionPriceDeleteAll($ID_Firm)
    {
        CarPresenceEN::deleteAll('ID_Firm=:id', [':id' => $ID_Firm]);

        return $this->redirect(['firms/price', 'id' => $ID_Firm]);
    }

    /**
     * Add element in price.
     *
     * @param int $ID_Firm
     *
     * @return Response
     */
    public function actionPriceElementAdd($ID_Firm)
    {
        $model = new CarPresenceEN();
        $model->ID_Firm = $ID_Firm;
        if ($model->load(Yii::$app->request->post())) {
            try {
                $model->Hash_Comment = md5($model->Comment);
                $model->save();
            } catch (IntegrityException $e) {
                $param = Yii::$app->request->post();
                $items = $this->getItemForPriceEditForm($param['CarPresenceEN']);

                return $this->render('price_add', [
                    'model'   => $model,
                    'err'     => $e,
                    'names'   => $items['names'],
                    'marks'   => $items['marks'],
                    'models'  => $items['models'],
                    'bodys'   => $items['bodys'],
                    'engines' => $items['engines'],
                ]);
            }

            return $this->redirect(['firms/price', 'id' => $ID_Firm, 'err' => false]);
        } else {
            $items = $this->getItemForPriceEditForm([
                'ID_Mark'  => false,
                'ID_Model' => false,
            ]);

            return $this->render('price_add', [
                'model'   => $model,
                'err'     => false,
                'names'   => $items['names'],
                'marks'   => $items['marks'],
                'models'  => $items['models'],
                'bodys'   => $items['bodys'],
                'engines' => $items['engines'],
            ]);
        }
    }

    /**
     * Find element in price list.
     *
     * @param array $params
     *
     * @return array|\yii\db\ActiveRecord
     */
    protected function findPriceElement($params)
    {
        $model = CarPresenceEN::find()
            ->andFilterWhere([
                'ID_Mark'   => $params['ID_Mark'],
                'ID_Model'  => $params['ID_Model'],
                'ID_Name'   => $params['ID_Name'],
                'ID_Firm'   => $params['ID_Firm'],
                'ID_Body'   => $params['ID_Body'],
                'ID_Engine' => $params['ID_Engine'],
                'Cost'      => $params['Cost'],
            ])
            ->andFilterWhere(['like', 'CarYear', $params['CarYear']])
            ->andFilterWhere(['like', 'Hash_Comment', $params['Hash_Comment']])
            ->andFilterWhere(['like', 'TechNumber', $params['TechNumber']])
            ->andFilterWhere(['like', 'Catalog_Number', $params['Catalog_Number']])
            ->one();

        return $model;
    }

    /**
     * Get details names, marks, models, bodys, engines list
     * for edit form element of price list.
     *
     * @param array $param
     *
     * @return array
     */
    protected function getItemForPriceEditForm($param)
    {
        $names = ArrayHelper::map(
            CarENDetailNames::find()
                ->select('id, Name')
                ->orderBy('Name')
                ->asArray()->all(),
            'id', 'Name');

        $marks = ArrayHelper::map(
            CarMarksEN::find()
                ->select('id, Name')
                ->orderBy('Name')->asArray()->all(),
            'id', 'Name');

        $models = false;
        if ($param['ID_Mark']) {
            $models = ArrayHelper::map(CarModelsEN::find()
                ->select('id, Name')
                ->andFilterWhere(['ID_Mark' => $param['ID_Mark']])
                ->orderBy('Name')->asArray()->all(), 'id', 'Name');
        }

        $bodys = false;
        if ($param['ID_Model']) {
            $bodys = ArrayHelper::map(CarBodyModelsEN::find()
                ->select('id, Name')
                ->andFilterWhere([
                    'ID_Mark'  => $param['ID_Mark'],
                    'ID_Model' => $param['ID_Model'],
                ])->orderBy('Name')->asArray()->all(), 'id', 'Name');
        }

        // need refactor... :(
        // very bad code
        $engines = false;
        if ($param['ID_Mark']) {
            if ($param['ID_Model']) {
                if ($param['ID_Body']) {
                    $links = ArrayHelper::getColumn(
                        CarEngineAndBodyCorrespondencesEN::find()
                        ->select('ID_Engine')
                        ->andFilterWhere([
                            'ID_Mark'  => $param['ID_Mark'],
                            'ID_Model' => $param['ID_Model'],
                            'ID_Body'  => $param['ID_Body'],
                        ])->asArray()->all(), 'ID_Engine');
                } else {
                    $links = ArrayHelper::getColumn(
                        CarEngineAndModelCorrespondencesEN::find()
                        ->select('ID_Engine')
                        ->andFilterWhere([
                            'ID_Mark'  => $param['ID_Mark'],
                            'ID_Model' => $param['ID_Model'],
                        ])->asArray()->all(), 'ID_Engine');
                }
                $engines = ArrayHelper::map(CarEngineModelsEN::find()
                    ->select('id, Name')
                    ->andFilterWhere([
                        'id' => $links,
                    ])->orderBy('Name')->asArray()->all(), 'id', 'Name');
            } else {
                $engines = ArrayHelper::map(CarEngineModelsEN::find()
                    ->select('id, Name')
                    ->andFilterWhere([
                        'ID_Mark' => $param['ID_Mark'],
                    ])->orderBy('Name')->asArray()->all(), 'id', 'Name');
            }
        }

        return compact('names', 'marks', 'models', 'bodys', 'engines');
    }

    /**
     * Get Models list for <select> field for _price_form from AJAX.
     *
     * @param int $id Mark id
     */
    public function actionGetModels($id)
    {
        $models = CarModelsEN::find()
            ->select('id, Name')
            ->andFilterWhere(['ID_Mark' => $id])
            ->orderBy('Name')->asArray()->all();

        echo '<option></option>';
        foreach ($models as $value) {
            echo "<option value='{$value['id']}'>{$value['Name']}</option>";
        }
    }

    /**
     * Get Bodys list for <select> field for _price_form from AJAX.
     *
     * @param int $id_models
     */
    public function actionGetBodys($id_models)
    {
        $boyds = CarBodyModelsEN::find()
            ->select('id, Name')
            ->andFilterWhere([
                'ID_Model' => $id_models,
            ])->orderBy('Name')->asArray()->all();

        echo '<option></option>';
        foreach ($boyds as $value) {
            echo "<option value='{$value['id']}'>{$value['Name']}</option>";
        }
    }

    /**
     * Get Engines list for <select> field for _price_form from AJAX.
     *
     * @param int $id_mark
     */
    public function actionGetEnginesByMark($id_mark)
    {
        $engines = CarEngineModelsEN::find()
            ->select('id, Name')
            ->andFilterWhere([
                'ID_Mark' => $id_mark,
            ])->orderBy('Name')->asArray()->all();

        echo '<option></option>';
        foreach ($engines as $value) {
            echo "<option value='{$value['id']}'>{$value['Name']}</option>";
        }
    }

    /**
     * Get Engines list for <select> field for _price_form from AJAX.
     *
     * @param int $id_model
     */
    public function actionGetEnginesByModel($id_model)
    {
        $links = ArrayHelper::getColumn(
            CarEngineAndModelCorrespondencesEN::find()
                ->select('ID_Engine')
                ->andFilterWhere([
                    'ID_Model' => $id_model,
                ])->asArray()->all(), 'ID_Engine');

        $engines = CarEngineModelsEN::find()
            ->select('id, Name')
            ->andFilterWhere([
            'id' => $links,
        ])->orderBy('Name')->asArray()->all();

        echo '<option></option>';
        foreach ($engines as $value) {
            echo "<option value='{$value['id']}'>{$value['Name']}</option>";
        }
    }

    /**
     * Get Engines list for <select> field for _price_form from AJAX.
     *
     * @param int $id_body
     */
    public function actionGetEnginesByBody($id_body)
    {
        $links = ArrayHelper::getColumn(
            CarEngineAndBodyCorrespondencesEN::find()
                ->select('ID_Engine')
                ->andFilterWhere([
                    'ID_Body' => $id_body,
                ])->asArray()->all(), 'ID_Engine');

        $engines = CarEngineModelsEN::find()
            ->select('id, Name')
            ->andFilterWhere([
                'id' => $links,
            ])->orderBy('Name')->asArray()->all();

        echo '<option></option>';
        foreach ($engines as $value) {
            echo "<option value='{$value['id']}'>{$value['Name']}</option>";
        }
    }
}
