<?php

namespace app\controllers;

use app\models\CarEngineAndBodyCorrespondencesEN;
use app\models\CarEngineAndModelCorrespondencesEN;
use app\models\Firms;
use app\models\CarModelsEN;
use app\models\CarBodyModelsEN;
use app\models\CarEngineModelsEN;
use yii\web\Controller;
use yii\web\Response;

/**
 * Class SiteController.
 */
class SiteController extends Controller
{
    /**
     * Рендер главной.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * @param string $str строка запроса
     *
     * @return string
     */
    public function actionSearch($str)
    {
        $search_array = explode('+', $str);
        $sql = "SELECT * FROM Firms WHERE (Name LIKE '%{$search_array[0]}%' ".
                "OR Comment LIKE '%{$search_array[0]}%' " .
                "OR Address LIKE '%{$search_array[0]}%' ".
                "OR Phone LIKE '%{$search_array[0]}%' " .
                "OR ActivityType LIKE '%{$search_array[0]}%' ".
                "OR OrganizationType LIKE '%{$search_array[0]}%' ".
                "OR District LIKE '%{$search_array[0]}%' ".
                "OR Fax LIKE '%{$search_array[0]}%' ".
                "OR Email LIKE '%{$search_array[0]}%' ".
                "OR URL LIKE '%{$search_array[0]}%' ".
                "OR OperatingMode LIKE '%{$search_array[0]}%')";

        if (count($search_array) > 1) {
            $options = explode(' ', $search_array[1]);
            foreach ($options as $key => $value) {
                $sql .= " AND (Name LIKE '%{$value}%' ".
                "OR Address LIKE '%{$value}%' ".
                "OR Phone LIKE '%{$value}%' ".
                "OR Comment LIKE '%{$value}%' ".
                "OR ActivityType LIKE '%{$value}%' ".
                "OR OrganizationType LIKE '%{$value}%' ".
                "OR District LIKE '%{$value}%' ".
                "OR Fax LIKE '%{$value}%' ".
                "OR Email LIKE '%{$value}%' ".
                "OR URL LIKE '%{$value}%' ".
                "OR OperatingMode LIKE '%{$value}%')";
            }
        }
        $sql .= ' ORDER BY Name, Address';

        $firms = Firms::findBySql($sql)->all();

        \Yii::$app->response->format = Response::FORMAT_JSON;

        return [
            'success' => true,
            'message' => $firms,
        ];
    }

    public function actionGetModels($id) 
    {
        $carModels = CarModelsEN::find()->where([ '=','ID_Mark', $id ])->
                                        OrderBy(['Name' => SORT_ASC])->
                                        asArray()->all();

        \Yii::$app->response->format = Response::FORMAT_JSON;

        return [
            'success' => true,
            'message' => $carModels,
        ];
    }

    public function actionGetBodys($id)
    {
        $carBodys = CarBodyModelsEN::find()->where([ '=','ID_Model', $id ])->
                                            OrderBy(['Name' => SORT_ASC])->
                                            asArray()->all();

        \Yii::$app->response->format = Response::FORMAT_JSON;

        return [
            'success' => true,
            'message' => $carBodys,
        ];
    }

    public function actionGetEngine($mark_id, $model_id, $body_id)
    {
        $carEngine = [];

        if($model_id === "false" && $body_id === "false") {
            $carEngine = CarEngineModelsEN::find()->where([ '=','ID_Mark', $mark_id ])->
                                                    OrderBy(['Name' => SORT_ASC])->
                                                    asArray()->all();
        } elseif ($body_id === "false") {
            $sql = "SELECT B.id,B.Name FROM CarEngineAndModelCorrespondencesEN as A " .
                   "LEFT JOIN CarEngineModelsEN as B ON (A.ID_Engine = B.id) " .
                   "WHERE A.ID_Mark={$mark_id} AND A.ID_Model={$model_id} AND B.Name IS NOT NULL " .
                   "ORDER BY Name";
            $carEngine = CarEngineModelsEN::findBySql($sql)->all();
        } else {
            $sql = "SELECT B.id,B.Name FROM CarEngineAndBodyCorrespondencesEN as A ".
                   "LEFT JOIN CarEngineModelsEN as B ON (A.ID_Engine = B.id) " .
                   "WHERE A.ID_Mark={$mark_id} AND A.ID_Model={$model_id} AND A.ID_Body={$body_id} AND B.Name IS NOT NULL " .
                   "ORDER BY Name";
            $carEngine = CarEngineModelsEN::findBySql($sql)->all();
        }

        \Yii::$app->response->format = Response::FORMAT_JSON;

        return [
            'success' => true,
            'message' => $carEngine,
        ];
    }
}
