<?php

namespace app\controllers;

use app\models\CarBodyModelsEN;
use app\models\CarENDetailNames;
use app\models\CarEngineModelsEN;
use app\models\CarMarksEN;
use app\models\CarModelsEN;
use app\models\Firms;
use app\models\LoginForm;
use app\models\Services;
use app\models\StatFirmsFirms;
use app\models\StatFirmsQuery;
use app\models\StatPartsFirms;
use app\models\StatPartsQuery;
use app\models\StatServiceFirms;
use app\models\StatServiceQuery;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\Response;

/**
 * Class SiteController.
 */
class SiteController extends Controller
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
                        'actions' => ['logout', 'index', 'search', 'get-details-name', 'get-marks',
                                        'get-firm', 'get-models', 'get-bodys', 'get-engine',
                                        'search-parts', 'get-service-group',
                                        'service-search', 'stat-part-open-firm',
                                        'stat-service-open-firm', 'stat-firm-open-firm', ],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class'   => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
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
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

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
     * Функция поиска по фирмам
     *
     * @param string $str строка запроса
     *
     * @return string
     */
    public function actionSearch($str)
    {
        // Экранируем как можем :)
        $search = str_replace('%', "\%", $str);
        $search = str_replace('.', "\.", $search);
        $search_array = explode('+', $search);

        $sql = 'SELECT @rn:=@rn+1 as Row, d.* FROM '.
                '(SELECT @rn := 0) as r, '.
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

        // пишем статистику
        $stat = new StatFirmsQuery();
        $stat->firmStatistic($str, \Yii::$app->user->identity->id);

        // получаем Id запроса
        $id = $stat->find()->andWhere([
                'id_operator' => \Yii::$app->user->identity->id,
            ])->select('max(id)')->scalar();

        // формируем список фирм согласно их позиции
        $stat = new StatFirmsFirms();
        $firm_list = [];
        $last_id = 0;
        foreach ($firms as $key => $value) {
            if ($last_id != $value['id']) {
                $last_id = $value['id'];
                array_push($firm_list, $last_id);
            }
        }
        $stat->firmStatistic($firm_list, $id);

        \Yii::$app->response->format = Response::FORMAT_JSON;

        return [
            'success'  => true,
            'message'  => $firms,
            'query_id' => $id,
        ];
    }

    /**
     * Функция получения списка деталей.
     *
     * @return array
     */
    public function actionGetDetailsName()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;

        return CarENDetailNames::find()->orderBy('Name')->all();
    }

    /**
     * Функция получения списка марок.
     *
     * @return array
     */
    public function actionGetMarks()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;

        return CarMarksEN::find()->orderBy('Name')->all();
    }

    /**
     * Функция получения списка моделей по параметрам
     *
     * @param int $id
     *
     * @return array
     */
    public function actionGetModels($id)
    {
        $carModels = CarModelsEN::find()
            ->where(['=', 'ID_Mark', $id])
            ->orderBy(['Name' => SORT_ASC])
            ->asArray()->all();

        \Yii::$app->response->format = Response::FORMAT_JSON;

        return $carModels;
    }

    /**
     * Функция получения списка кузовов по параметрам
     *
     * @param int $id
     *
     * @return array
     */
    public function actionGetBodys($id)
    {
        $carBodys = CarBodyModelsEN::find()
            ->where(['=', 'ID_Model', $id])
            ->orderBy(['Name' => SORT_ASC])
            ->asArray()->all();

        \Yii::$app->response->format = Response::FORMAT_JSON;

        return $carBodys;
    }

    /**
     * Функция получения списка двигателей по параметрам
     *
     * @param int|string $mark_id
     * @param int|string $model_id
     * @param int|string $body_id
     *
     * @return array
     */
    public function actionGetEngine($mark_id, $model_id, $body_id)
    {
        $carEngine = [];

        if ($model_id === 'false' && $body_id === 'false') {
            $carEngine = CarEngineModelsEN::find()
                ->where(['=', 'ID_Mark', $mark_id])
                ->orderBy(['Name' => SORT_ASC])
                ->asArray()->all();
        } elseif ($body_id === 'false') {
            $sql = 'SELECT B.id,B.Name FROM CarEngineAndModelCorrespondencesEN as A '.
                   'LEFT JOIN CarEngineModelsEN as B ON (A.ID_Engine = B.id) '.
                   "WHERE A.ID_Mark={$mark_id} AND A.ID_Model={$model_id} AND B.Name IS NOT NULL ".
                   'ORDER BY Name';
            $carEngine = CarEngineModelsEN::findBySql($sql)->asArray()->all();
        } else {
            $sql = 'SELECT B.id,B.Name FROM CarEngineAndBodyCorrespondencesEN as A '.
                   'LEFT JOIN CarEngineModelsEN as B ON (A.ID_Engine = B.id) '.
                   "WHERE A.ID_Mark={$mark_id} AND A.ID_Model={$model_id} AND A.ID_Body={$body_id} AND B.Name IS NOT NULL ".
                   'ORDER BY Name';
            $carEngine = CarEngineModelsEN::findBySql($sql)->asArray()->all();
        }

        \Yii::$app->response->format = Response::FORMAT_JSON;

        return $carEngine;
    }

    /**
     * Функция получения карточки фирмы.
     *
     * @param int $firm_id
     *
     * @return array
     */
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
     * @param $number string номер детали
     *
     * @var $page integer какая страница результата нас интересует
     * @var $limit integer соклько строк результатов нам надо
     *
     * @return array возвращаем JSON
     */
    public function actionSearchParts($detail_id, $mark_id, $model_id, $body_id, $engine_id, $number)
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
                              (SELECT id FROM CarModelsEN WHERE Name = '***' AND ID_Mark IN ({$mark_search}))";
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
                                (SELECT id FROM CarEngineModelsEN WHERE Name='***')";
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
            $sql .= " AND (MATCH (A.Comment,A.Catalog_Number) AGAINST ('{$number}'))";
        }

        // убираем дубои от JOIN-ов
        $sql .= ' GROUP BY DetailName , MarkName , ModelName , BodyName , EngineName , A.CarYear , A.Comment , A.Cost , A.Catalog_Number , A.TechNumber , A.ID_Firm , Firms.Priority ';

        // сортировка
        $sql .= ' ORDER BY Firms.Priority, Firms.id, DetailName LIMIT 10000';

        $command = $connection->createCommand($sql);
        $parts = $command->queryAll();

        // пишем статистику
        $stat = new StatPartsQuery();
        $stat->partStatistic($detail_id == 'false' ? 0 : $detail_id,
                             $mark_id == 'false' ? 0 : $mark_id,
                             $model_id == 'false' ? 0 : $model_id,
                             $body_id == 'false' ? 0 : $body_id,
                             $engine_id == 'false' ? 0 : $engine_id,
                             $number == 'false' ? '' : $number,
                             \Yii::$app->user->identity->id);

        // получаем Id запроса
        $id = $stat->find()->andWhere([
                'id_operator' => \Yii::$app->user->identity->id,
            ])->select('max(id)')->scalar();

        // формируем список фирм согласно их позиции
        $stat = new StatPartsFirms();
        $firm_list = [];
        $last_id = 0;
        foreach ($parts as $key => $value) {
            if ($last_id != $value['ID_Firm']) {
                $last_id = $value['ID_Firm'];
                array_push($firm_list, $last_id);
            }
        }
        $stat->partStatistic($firm_list, $id);

        \Yii::$app->response->format = Response::FORMAT_JSON;

        return [
            'success'  => true,
            'message'  => $parts,
            'query_id' => $id,
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
                  (SELECT A.ID_Firm, Firms.Address, A.Comment, A.CarList, Firms.District, Firms.Name as Name
                    FROM ServicePresence as A
                    LEFT JOIN Firms ON (A.ID_Firm=Firms.id)
                    WHERE A.ID_Service={$id} AND Firms.Enabled=1 
                    ORDER BY Firms.Name, Firms.Priority) as d";

        $command = \Yii::$app->getDb()->createCommand($sql);
        $rows = $command->queryAll();

        // пишем статистику
        $stat = new StatServiceQuery();
        $stat->serviceStatistic($id, \Yii::$app->user->identity->id);

        // получаем Id запроса
        $id_query = $stat->find()->andWhere([
                'id_operator' => \Yii::$app->user->identity->id,
            ])->select('max(id)')->scalar();

        // формируем список фирм согласно их позиции
        $stat = new StatServiceFirms();
        $firm_list = [];
        $last_id = 0;
        foreach ($rows as $key => $value) {
            if ($last_id != $value['ID_Firm']) {
                $last_id = $value['ID_Firm'];
                array_push($firm_list, $last_id);
            }
        }
        $stat->serviceStatistic($firm_list, $id_query);

        \Yii::$app->response->format = Response::FORMAT_JSON;

        return [
            'rows'     => $rows,
            'query_id' => $id_query,
        ];
    }

    /**
     * Функция записи статистики открытых фирм
     *
     * @param int $firm_id
     * @param int $query_id
     *
     * @return array
     */
    public function actionStatPartOpenFirm($firm_id, $query_id)
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;

        $stat = StatPartsFirms::find()->andWhere([
                'id_query' => $query_id,
                'id_firm'  => $firm_id,
            ])->one();
        $stat->opened = 1;

        if ($stat->update()) {
            return [
                'success' => true,
            ];
        } else {
            Yii::error('stat_parts_firms: Фирма не открыта');

            return [
                'success' => false,
            ];
        }
    }

    /**
     * Функция записи статистики открытых фирм
     *
     * @param int $firm_id
     * @param int $query_id
     *
     * @return array
     */
    public function actionStatFirmOpenFirm($firm_id, $query_id)
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;

        $stat = StatFirmsFirms::find()->andWhere([
                'id_query' => $query_id,
                'id_firm'  => $firm_id,
            ])->one();
        $stat->opened = 1;

        if ($stat->update()) {
            return [
                'success' => true,
            ];
        } else {
            Yii::error('stat_firm_firms: Фирма не открыта');

            return [
                'success' => false,
            ];
        }
    }

    /**
     * Функция записи статистики открытых фирм
     *
     * @param int $firm_id
     * @param int $query_id
     *
     * @return array
     */
    public function actionStatServiceOpenFirm($firm_id, $query_id)
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;

        $stat = StatServiceFirms::find()->andWhere([
                'id_query' => $query_id,
                'id_firm'  => $firm_id,
            ])->one();
        $stat->opened = 1;

        if ($stat->update()) {
            return [
                'success' => true,
            ];
        } else {
            Yii::error('stat_service_firms: Фирма не открыта');

            return [
                'success' => false,
            ];
        }
    }
}
