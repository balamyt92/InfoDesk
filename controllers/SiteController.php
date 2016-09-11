<?php

namespace app\controllers;

use app\models\CarBodyModelsEN;
use app\models\CarEngineAndBodyCorrespondencesEN;
use app\models\CarEngineAndModelCorrespondencesEN;
use app\models\CarEngineModelsEN;
use app\models\CarModelsEN;
use app\models\CarPresenceEN;
use app\models\Firms;
use app\models\Services;
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
        $sql = "SELECT @rn:=@rn+1 as Row, d.* FROM ".
                "(SELECT @rn := 0) as r, ".
                "(SELECT * FROM Firms WHERE (Name LIKE '%{$search_array[0]}%' ".
                    "OR Comment LIKE '%{$search_array[0]}%' ".
                    "OR Address LIKE '%{$search_array[0]}%' ".
                    "OR Phone LIKE '%{$search_array[0]}%' ".
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
        $sql .= ' ORDER BY Name, Address) as d';

        $firms = Firms::findBySql($sql)->all();

        \Yii::$app->response->format = Response::FORMAT_JSON;

        return [
            'success' => true,
            'message' => $firms,
        ];
    }

    public function actionGetModels($id)
    {
        $carModels = CarModelsEN::find()->where(['=', 'ID_Mark', $id])->
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
        $carBodys = CarBodyModelsEN::find()->where(['=', 'ID_Model', $id])->
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

        if ($model_id === 'false' && $body_id === 'false') {
            $carEngine = CarEngineModelsEN::find()->where(['=', 'ID_Mark', $mark_id])->
                                                    OrderBy(['Name' => SORT_ASC])->
                                                    asArray()->all();
        } elseif ($body_id === 'false') {
            $sql = 'SELECT B.id,B.Name FROM CarEngineAndModelCorrespondencesEN as A '.
                   'LEFT JOIN CarEngineModelsEN as B ON (A.ID_Engine = B.id) '.
                   "WHERE A.ID_Mark={$mark_id} AND A.ID_Model={$model_id} AND B.Name IS NOT NULL ".
                   'ORDER BY Name';
            $carEngine = CarEngineModelsEN::findBySql($sql)->all();
        } else {
            $sql = 'SELECT B.id,B.Name FROM CarEngineAndBodyCorrespondencesEN as A '.
                   'LEFT JOIN CarEngineModelsEN as B ON (A.ID_Engine = B.id) '.
                   "WHERE A.ID_Mark={$mark_id} AND A.ID_Model={$model_id} AND A.ID_Body={$body_id} AND B.Name IS NOT NULL ".
                   'ORDER BY Name';
            $carEngine = CarEngineModelsEN::findBySql($sql)->all();
        }

        \Yii::$app->response->format = Response::FORMAT_JSON;

        return [
            'success' => true,
            'message' => $carEngine,
        ];
    }

    public function actionGetFirm($firm_id)
    {
        $firm = Firms::find()->where(['=', 'id', $firm_id])->asArray()->all();

        \Yii::$app->response->format = Response::FORMAT_JSON;

        return [
            'success' => true,
            'message' => $firm,
        ];
    }

    /**
     * Функция поиска запчастей.
     *
     * @param $detail_id
     * @param $mark_id
     * @param $model_id
     * @param $body_id
     * @param $engine_id
     * @param $page integer какая страница результата нас интересует
     * @param $limit integer соклько строк результатов нам надо
     * @param $number string номер детали
     *
     * @return array возвращаем JSON
     */
    public function actionSearchParts($detail_id, $mark_id, $model_id, $body_id, $engine_id, $page, $limit, $number)
    {
        $connection = \Yii::$app->getDb();
        $detail_search = $detail_id;
        $mark_search = $mark_id;
        $model_search = $model_id;
        $body_search = $body_id;
        $engine_search = $engine_id;

        // запрос результирующеё таблицы
        $sql = 'SELECT DETAIL.Name as DetailName, MARK.Name as MarkName, MODEL.Name as ModelName, '.
            'BODY.Name as BodyName, ENGINE.Name as EngineName, A.CarYear, A.Comment, '.
            'A.Cost, A.Catalog_Number, A.TechNumber, A.ID_Firm, Firms.Priority '.
            'FROM CarPresenceEN AS A '.
            'LEFT JOIN CarENDetailNames AS DETAIL ON (DETAIL.id=A.ID_Name) '.
            'LEFT JOIN CarMarksEN as MARK ON (MARK.id=A.ID_Mark) '.
            'LEFT JOIN CarModelsEN as MODEL ON (MODEL.id=A.ID_Model) '.
            'LEFT JOIN CarBodyModelsEN as BODY ON (BODY.id=A.ID_Body) '.
            'LEFT JOIN CarEngineModelsEN as ENGINE ON (ENGINE.id=A.ID_Engine) '.
            'LEFT JOIN Firms ON (Firms.id=A.ID_Firm) '.
            'WHERE Firms.Enabled=1 ';

        if (!($detail_id === 'false')) {
            // ищем все связанные детали
            $link_detail_sql = "SELECT ID_LinkedDetail from CarENLinkedDetailNames where ID_GroupDetail = {$detail_id}";
            $link = $this->getLinkedString($link_detail_sql, 'ID_LinkedDetail');
            if ($link) {
                $detail_search .= ','.$link;
                $sql .= "AND A.ID_Name IN ({$detail_search}) ";
            } else {
                $sql .= "AND A.ID_Name = {$detail_id} ";
            }
        }

        if (!($mark_id === 'false')) {
            // ищем связанные марки
            $link_mar_sql = "(SELECT ID_Group FROM CarMarkGroupsEN WHERE ID_Mark= {$mark_id}) UNION 
                            (SELECT id FROM CarMarksEN WHERE Name = '***')";
            $link = $this->getLinkedString($link_mar_sql, 'ID_Group');
            if ($link) {
                $mark_search .= ','.$link;
                $sql .= "AND A.ID_Mark IN ({$mark_search}) ";
            } else {
                $sql .= "AND A.ID_Mark = {$mark_id} ";
            }
        }

        if (!($model_id === 'false')) {
            // ищем связанные модели
            $link_model_sql = "(SELECT ID_Group FROM CarModelGroupsEN WHERE ID_Model = {$model_id}) UNION 
                              (SELECT id FROM CarModelsEN WHERE Name = '***' AND ID_Mark = {$mark_id})";
            $link = $this->getLinkedString($link_model_sql, 'ID_Group');
            if ($link) {
                $model_search .= ','.$link;
                $sql .= "AND A.ID_Model IN ({$model_search}) ";
            } else {
                $sql .= "AND A.ID_Model = {$model_id} ";
            }
        }

        if (!($body_id === 'false')) {
            // ищем связанные кузова
            $link_body_sql = "(SELECT ID_BodyGroup FROM CarBodyModelGroupsEN 
                                WHERE ID_BodyModel = {$body_id} AND ID_Mark IN ({$mark_search}) AND ID_Model IN ({$model_search})) 
                              UNION 
                              (SELECT id FROM CarBodyModelsEN 
                                WHERE Name = '***' AND ID_Mark IN ({$mark_search}) AND ID_Model IN ({$model_search}))
                              UNION 
                              (SELECT ID_BodyModel FROM CarBodyModelGroupsEN 
                                WHERE ID_BodyGroup IN (
                                        SELECT id FROM CarBodyModelsEN WHERE Name LIKE CONCAT('',(SELECT Name FROM CarBodyModelsEN WHERE id = {$body_id}),'')
                                ) AND ID_Mark IN ({$mark_search}) AND ID_Model IN ({$model_search}))";
            $link = $this->getLinkedString($link_body_sql, 'ID_BodyGroup');
            if ($link) {
                $body_search .= ','.$link;
                $sql .= "AND A.ID_Body IN ({$body_search}) ";
            } else {
                $sql .= "AND A.ID_Body = {$body_id} ";
            }
        }
        if (!($engine_id === 'false')) {
            $link_engine_sql = "(SELECT ID_EngineModel FROM CarEngineModelGroupsEN 
                                  WHERE ID_EngineGroup={$engine_id})
                                UNION
                                (SELECT id FROM CarEngineModelsEN WHERE Name='***' AND ID_Mark={$mark_id})";
            $link = $this->getLinkedString($link_engine_sql, 'ID_EngineModel');
            if ($link) {
                $engine_search .= ','.$link;
                $sql .= "AND A.ID_Engine IN ({$engine_search}) ";
            } else {
                $sql .= "AND A.ID_Engine={$engine_id} ";
            }
        }


        // поиск по номеру
        if (!empty($number)) {
            //            $number_search = str_replace('-', '%', $number);
//            $number_search = str_replace('?', '_', $number_search);
//            $sql .= " AND (A.Comment LIKE '%{$number_search}%' OR A.Catalog_Number LIKE '%{$number_search}%') ";
//
            $sql .= " AND (MATCH (A.Comment,A.Catalog_Number) AGAINST ('{$number}'))";
        }

        // сортировка
        $sql .= ' ORDER BY Firms.Priority, Firms.id, DetailName, MarkName, ModelName, BodyName, EngineName';

        // пагинация
        $sql .= " LIMIT {$limit}";
        if ($page > 1) {
            $fin = ((int) $page - 1) * (int) $limit;
            $sql .= " OFFSET {$fin}";
        }

        $command = $connection->createCommand($sql);
        $parts = $command->queryAll();

        \Yii::$app->response->format = Response::FORMAT_JSON;

        return [
            'success' => true,
            'message' => $parts,
        ];
    }

    /**
     * Функция формируют строку для запроса свзянных id для деталей/марок/моделей/кузовов/двигателей.
     *
     * @param string $sql    запрос которым можно получить список нужных id
     * @param string $column интересующая нас колонка
     *
     * @return string результат в виде строки со списком id чере запятую
     */
    private function getLinkedString($sql, $column)
    {
        $link = \Yii::$app->getDb()->createCommand($sql)->queryAll();
        $tmp = [];
        foreach ($link as $value) {
            array_push($tmp, $value[$column]);
        }
        $link = implode(',', $tmp);

        return $link;
    }

    /**
     * @param $id
     *
     * @return array
     */
    public function actionGetServiceGroup($id)
    {
        $services = Services::find()->where(['=', 'ID_Parent', $id])->orderBy(['Name' => SORT_ASC])->all();

        $html = '';
        foreach ($services as $value) {
            $html .= '<option style="border-bottom: solid 1px;" value="'.$value['id'].'">'.$value['Name'].'</option>';
        }

        \Yii::$app->response->format = Response::FORMAT_JSON;

        return [
            'success' => true,
            'message' => $html,
        ];
    }

    public function actionServiceSearch($id)
    {
        $rows = [];


        $sql = "SELECT @rn:=@rn+1 as Row, d.* FROM 
                  (SELECT @rn := 0) as r, 
                  (SELECT A.ID_Firm, A.Comment, A.CarList, Firms.District, Firms.Name as Name
                    FROM ServicePresence as A 
                    LEFT JOIN Firms ON (A.ID_Firm=Firms.id) 
                    WHERE A.ID_Service={$id} 
                    ORDER BY Firms.Priority) as d";

        $command = \Yii::$app->getDb()->createCommand($sql);
        $rows = $command->queryAll();

        \Yii::$app->response->format = Response::FORMAT_JSON;

        return [
            'rows' => $rows,
        ];
    }
}
