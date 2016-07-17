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
                "OR Address LIKE '%{$search_array[0]}%' ".
                "OR Phone LIKE '%{$search_array[0]}%' ".
                "OR Comment LIKE '%{$search_array[0]}%' ".
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
}
