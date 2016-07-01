<?php

namespace app\controllers;

use app\models\Firms;
use yii\web\Controller;
use yii\web\Response;

/**
 * Class SiteController.
 */
class SiteController extends Controller
{
    /**
     * Рендер гланой.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * @param string $str строка запроса
     * @return string
     */
    public function actionSearch($str)
    {
        $firms = Firms::find()->filterWhere(['like', 'Name', $str])->
                                orFilterWhere(['like', 'Address', $str ])->
                                orFilterWhere(['like', 'Comment', $str ])->
                                orFilterWhere(['like', 'ActivityType', $str ])->
                                orFilterWhere(['like', 'OperatingMode', $str ])->
                                orFilterWhere(['like', 'Phone', $str ])->
                                orFilterWhere(['like', 'District', $str ])->
                                orFilterWhere(['like', 'OrganizationType', $str ])->
                                orFilterWhere(['like', 'Email', $str ])->
                                orFilterWhere(['like', 'URL', $str ])->orderBy('Name', 'Addres')->all();
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $result = [
            'success' => true,
            'message' => $firms,
        ];
        return $result;
    }
}
