<?php

namespace app\controllers;

use app\models\CarBodyModelsEN;
use app\models\CarENDetailNames;
use app\models\CarEngineAndBodyCorrespondencesEN;
use app\models\CarEngineModelsEN;
use app\models\CarMarksEN;
use app\models\CarModelsEN;
use app\models\Firms;
use app\models\LoginForm;
use app\models\ServicePresence;
use app\models\Services;
use app\models\StatFirmsFirms;
use app\models\StatFirmsQuery;
use app\models\StatPartsFirms;
use app\models\StatPartsQuery;
use app\models\StatServiceFirms;
use app\models\StatServiceQuery;
use Yii;
use \Exception;
use yii\db\Query;
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
                        'actions' => [
                            'logout',
                            'index',
                            'search',
                            'get-details-name',
                            'get-marks',
                            'get-firm',
                            'get-models',
                            'get-bodys',
                            'get-engine',
                            'search-parts',
                            'get-service-group',
                            'service-search',
                            'stat-part-open-firm',
                            'stat-service-open-firm',
                            'stat-firm-open-firm',
                        ],
                        'allow'   => true,
                        'roles'   => ['@'],
                    ],
                ],
            ],
            'verbs'  => [
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
     * @return array
     */
    public function actionSearch($str = '')
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        try {
            $search_array = explode('+', $str);

            $columns = [
                'Name', 'Comment', 'Address', 'Phone', 'ActivityType', 'OrganizationType',
                'District', 'Fax', 'Email', 'URL', 'OperatingMode',
            ];
            $where = ['or'];
            foreach ($columns as $column) {
                $where[] = ['like', $column, $search_array[0]];
            }

            if (count($search_array) > 1) {
                $where = ['and', $where];
                $options = explode(' ', $search_array[1]);
                foreach ($options as $value) {
                    $and = ['or'];
                    foreach ($columns as $column) {
                        $and[] = ['like', $column, $value];
                    }
                    $where[] = $and;
                }
            }

            $firms = Firms::find()->where($where)->all();

            // пишем статистику
            $stat = new StatFirmsQuery();
            $stat->firmStatistic($str, \Yii::$app->user->identity->getId());

            // получаем Id запроса
            $id = $stat->find()->andWhere([
                'id_operator' => \Yii::$app->user->identity->getId(),
            ])->select('max(id)')->scalar();

            // формируем список фирм согласно их позиции
            $stat = new StatFirmsFirms();
            $firm_list = [];
            $last_id = 0;
            foreach ($firms as $value) {
                if ($last_id != $value['id']) {
                    $last_id = $value['id'];
                    $firm_list[] = $last_id;
                }
            }
            $stat->firmStatistic($firm_list, $id);

            $_SESSION['firms_last_query_id'] = $id;

            return [
                'success' => true,
                'data'    => $firms,
            ];
        } catch (Exception $e) {

            return [
                'success' => false,
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ];
        }
    }

    /**
     * Функция получения списка деталей.
     *
     * @return array
     */
    public function actionGetDetailsName()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        try {
            $details = CarENDetailNames::find()->orderBy('Name')->all();
            return [
                'success' => true,
                'data'    => $details,
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ];
        }
    }

    /**
     * Функция получения списка марок.
     *
     * @return array
     */
    public function actionGetMarks()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;

        try {
            $marks = CarMarksEN::find()->orderBy('Name')->all();
            return [
                'success' => true,
                'data'    => $marks,
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ];
        }
    }

    /**
     * Функция получения списка моделей по параметрам
     *
     * @param int $id
     *
     * @return array
     */
    public function actionGetModels($id = null)
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;

        try {
            $carModels = CarModelsEN::find()
                ->where(['=', 'ID_Mark', $id])
                ->orderBy(['Name' => SORT_ASC])
                ->asArray()->all();
            return [
                'success' => true,
                'data'    => $carModels,
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ];
        }
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
        \Yii::$app->response->format = Response::FORMAT_JSON;

        try {
            $carBodes = CarBodyModelsEN::find()
                ->where(['=', 'ID_Model', $id])
                ->orderBy(['Name' => SORT_ASC])
                ->asArray()->all();
            return [
                'success' => true,
                'data'    => $carBodes,
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ];
        }
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
    public function actionGetEngine($mark_id = null, $model_id = null, $body_id = null)
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        try {
            if (!$model_id && !$body_id && $mark_id) {
                $carEngine = CarEngineModelsEN::find()
                    ->where(['=', 'ID_Mark', $mark_id])
                    ->orderBy(['Name' => SORT_ASC])
                    ->asArray()->all();
            } elseif (!$body_id && $mark_id && $model_id) {
                $carEngine = CarEngineAndBodyCorrespondencesEN::find()
                    ->distinct()
                    ->alias('A')
                    ->select('B.*')
                    ->leftJoin('CarEngineModelsEN as B', 'A.ID_Engine = B.id')
                    ->where([
                        'and',
                        ['=', 'A.ID_Mark', $mark_id],
                        ['=', 'A.ID_Model', $model_id],
                        ['not', ['B.Name' => null]],
                    ])
                    ->orderBy(['B.Name' => SORT_ASC])
                    ->asArray()->all();
            } elseif ($body_id && $mark_id && $model_id) {
                $carEngine = CarEngineAndBodyCorrespondencesEN::find()
                    ->distinct()
                    ->alias('A')
                    ->select('B.*')
                    ->leftJoin('CarEngineModelsEN as B', 'A.ID_Engine = B.id')
                    ->where([
                        'and',
                        ['=', 'A.ID_Mark', $mark_id],
                        ['=', 'A.ID_Model', $model_id],
                        ['=', 'A.ID_Body', $body_id],
                        ['not', ['B.Name' => null]],
                    ])
                    ->orderBy(['B.Name' => SORT_ASC])
                    ->asArray()->all();
            } else {
                $carEngine = CarEngineModelsEN::find()->asArray()->limit(10000);
            }

            return [
                'success' => true,
                'data'    => $carEngine,
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ];
        }
    }

    /**
     * Функция получения карточки фирмы.
     *
     * @param int $firm_id
     *
     * @return array
     */
    public function actionGetFirm($firm_id = null)
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        try {
            if (is_null($firm_id)) {
                throw new Exception('Не казан id фирмы');
            };

            $firm = Firms::findOne(['=', 'id', $firm_id]);

            return [
                'success' => true,
                'firm'    => $firm,
            ];
        } catch (Exception $e) {
            return [
                'success' => true,
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ];
        }


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
     * @return array возвращаем JSON
     */
    public function actionSearchParts(
        $detail_id = null,
        $mark_id = null,
        $model_id = null,
        $body_id = null,
        $engine_id = null,
        $number = null
    ) {
        \Yii::$app->response->format = Response::FORMAT_JSON;

        try {
            $mark_search = (int)$mark_id;
            $model_search = (int)$model_id;

            // запрос результирующеё таблицы
            $query = (new Query())
                ->from('CarPresenceEN as A')
                ->select([
                    'DETAIL.Name as DetailName',
                    'MARK.Name as MarkName',
                    'MODEL.Name as ModelName',
                    'BODY.Name as BodyName',
                    'ENGINE.Name as EngineName',
                    'A.CarYear',
                    'A.Comment',
                    'A.Cost',
                    'A.Catalog_Number',
                    'A.TechNumber',
                    'A.ID_Firm as id',
                    'Firms.Priority',
                ])
                ->distinct()
                ->leftJoin('CarENDetailNames as DETAIL', 'DETAIL.id=A.ID_Name')
                ->leftJoin('CarMarksEN as MARK', 'MARK.id=A.ID_Mark')
                ->leftJoin('CarModelsEN as MODEL', 'MODEL.id=A.ID_Model')
                ->leftJoin('CarBodyModelsEN as BODY', 'BODY.id=A.ID_Body')
                ->leftJoin('CarEngineModelsEN as ENGINE', 'ENGINE.id=A.ID_Engine')
                ->leftJoin('Firms', 'Firms.id=A.ID_Firm')
                ->where('Firms.Enabled = 1');

            if ($detail_id) {
                // ищем все связанные детали
                $detail_search = (new Query())
                    ->from('CarENLinkedDetailNames')
                    ->select('ID_LinkedDetail')
                    ->where(['ID_GroupDetail' => (int)$detail_id])
                    ->all();

                $detail_search = array_reduce($detail_search, function ($acc, $el) {
                    $acc[] = (int)$el['ID_LinkedDetail'];
                    return $acc;
                }, [(int)$detail_id]);

                $query = $query->andWhere([
                    'in',
                    'A.ID_Name',
                    $detail_search,
                ]);
            }

            if ($mark_id) {
                // ищем связанные марки
                $mark_search = (new Query())
                    ->from('CarMarkGroupsEN')
                    ->select('ID_Group')
                    ->where(['ID_Mark' => (int)$mark_id])
                    ->union(
                        (new Query())->from('CarMarksEN')
                            ->select('id')
                            ->where(['=', 'Name', '***'])
                    )
                    ->all();
                $mark_search = array_reduce($mark_search, function ($acc, $el) {
                    $acc[] = (int)$el['ID_Group'];
                    return $acc;
                }, [(int)$mark_id]);

                $query = $query->andWhere([
                    'in',
                    'A.ID_Mark',
                    $mark_search,
                ]);
            }

            if ($model_id) {
                $model_search = (new Query())
                    ->from('CarModelGroupsEN')
                    ->select('ID_Group')
                    ->where(['ID_Model' => $model_id])
                    ->union(
                        (new Query())
                            ->from('CarModelsEN')
                            ->select('id')
                            ->where(['in', 'ID_Mark', $mark_search])
                            ->andWhere(['Name' => '***'])
                    )
                    ->all();
                $model_search = array_reduce($model_search, function ($acc, $el) {
                    $acc[] = (int)$el['ID_Group'];
                    return $acc;
                }, [(int)$model_id]);

                $query = $query->andWhere([
                    'in',
                    'A.ID_Model',
                    $model_search,
                ]);
            }

            if ($body_id) {
                $body_search = (new Query())
                    ->from('CarBodyModelGroupsEN')
                    ->select('ID_BodyGroup')
                    ->where(['ID_BodyModel' => (int)$body_id])
                    ->andWhere(['ID_Mark' => $mark_search])
                    ->andWhere(['ID_Model' => $model_search])
                    ->union(
                        (new Query())
                            ->from('CarBodyModelsEN')
                            ->select('id')
                            ->where([
                                'or',
                                ['Name' => '***'],
                                ['id' => (int)$body_id],
                            ])
                            ->andWhere(['in', 'ID_Mark', $mark_search])
                            ->andWhere(['in', 'ID_Model', $model_search])
                    )->union(
                        (new Query())
                            ->from('CarBodyModelGroupsEN')
                            ->select('ID_BodyModel')
                            ->where([
                                'ID_BodyGroup' => (int)$body_id,
                            ])
                            ->andWhere(['in', 'ID_Mark', $mark_search])
                            ->andWhere(['in', 'ID_Model', $model_search])
                    )
                    ->all();

                $body_search = array_reduce($body_search, function ($acc, $el) {
                    $acc[] = (int)$el['ID_BodyGroup'];
                    return $acc;
                }, [(int)$body_id]);

                $query = $query->andWhere([
                    'in',
                    'A.ID_Body',
                    $body_search,
                ]);
            }
            if ($engine_id) {
                $engine_search = (new Query())
                    ->from('CarEngineModelGroupsEN')
                    ->select('ID_EngineModel')
                    ->where(['ID_EngineGroup' => $engine_id])
                    ->union(
                        (new Query())
                            ->from('CarEngineModelsEN')
                            ->select('id')
                            ->where(['Name' => '***'])
                    )->all();

                $engine_search = array_reduce($engine_search, function ($acc, $el) {
                    $acc[] = (int)$el['ID_EngineModel'];
                    return $acc;
                }, [(int)$engine_id]);

                $query = $query->andWhere([
                    'in',
                    'A.ID_Engine',
                    $engine_search,
                ]);
            }

            // поиск по номеру
            if ($number) {
                $str = mb_strtolower(str_replace('-', '', trim($number)));
                if (!empty($str)) {
                    // Делаем лайком ибо объемы не такие большие и скорость должа быть норм,
                    // а в требованиях строгий поиск
                    $query = $query->andWhere(['like', 'A.search', $str]);
                }
            }

            $parts = $query->orderBy([
                'Firms.Priority' => SORT_ASC,
                'Firms.id'       => SORT_ASC,
                'DetailName'     => SORT_ASC,
            ])->limit(10000)->all();

            // пишем статистику
            $stat = new StatPartsQuery();
            $stat->partStatistic($detail_id, $mark_id, $model_id, $body_id, $engine_id, $number, \Yii::$app->user->identity->getId());

            // получаем Id запроса
            $id = StatPartsQuery::find()->andWhere([
                'id_operator' => \Yii::$app->user->identity->getId(),
            ])->select('max(id)')->scalar();

            // формируем список фирм согласно их позиции
            $stat = new StatPartsFirms();
            $firm_list = [];
            $last_id = 0;
            foreach ($parts as $key => $value) {
                if ($last_id != $value['id']) {
                    $last_id = $value['id'];
                    $firm_list[] = $last_id;
                }
            }
            $stat->partStatistic($firm_list, $id);

            $_SESSION['parts_last_query_id'] = $id;

            return [
                'success' => true,
                'data'    => $parts,
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ];
        }
    }

    /**
     * @param $id
     *
     * @return array
     */
    public function actionGetServiceGroup($id = null)
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        try {
            $services = Services::find()
                ->where(['=', 'ID_Parent', $id])
                ->orderBy(['Name' => SORT_ASC])
                ->all();

            $html = '';
            foreach ($services as $value) {
                $html .= "<option value=\"{$value['id']}\">{$value['Name']}</option>";
            }

            return [
                'success' => true,
                'data'    => $html,
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ];
        }
    }

    public function actionServiceSearch($id = null)
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        try {
            if (is_null($id)) {
                throw new Exception('Укажите id сервиса');
            }
            $rows = ServicePresence::find()
                ->select([
                    'A.ID_Firm as id',
                    'Firms.Address',
                    'A.Comment',
                    'A.CarList',
                    'Firms.District',
                    'Firms.Name as Name',
                ])
                ->alias('A')
                ->leftJoin('Firms', 'A.ID_Firm=Firms.id')
                ->where(['A.ID_Service' => $id])
                ->andWhere(['Firms.Enabled' => 1])
                ->orderBy(['Firms.Name' => SORT_ASC, 'Firms.Priority' => SORT_ASC])
                ->asArray()->all();

            // пишем статистику
            $stat = new StatServiceQuery();
            $stat->serviceStatistic($id, \Yii::$app->user->identity->getId());

            // получаем Id запроса
            $id_query = $stat->find()->andWhere([
                'id_operator' => \Yii::$app->user->identity->getId(),
            ])->select('max(id)')->scalar();

            // формируем список фирм согласно их позиции
            $stat = new StatServiceFirms();
            $firm_list = [];
            $last_id = 0;
            foreach ($rows as $key => $value) {
                if ($last_id != $value['id']) {
                    $last_id = $value['id'];
                    $firm_list[] = $last_id;
                }
            }
            $stat->serviceStatistic($firm_list, $id_query);

            $_SESSION['service_last_query_id'] = $id_query;

            return [
                'success' => true,
                'data'    => $rows,
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ];
        }
    }

    /**
     * Функция записи статистики открытых фирм
     *
     * @param int $id
     *
     * @return array
     * @throws Exception
     */
    public function actionStatPartOpenFirm($id = null)
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        try {
            $query_id = isset($_SESSION['parts_last_query_id']) ? $_SESSION['parts_last_query_id'] : null;
            if (!$query_id || !$id) {
                throw new Exception('Не указан firm_id или не было еще не одного запроса от пользователя');
            }

            /** @var StatPartsFirms $stat */
            $stat = StatPartsFirms::find()->andWhere([
                'id_query' => $query_id,
                'id_firm'  => $id,
            ])->one();
            if ($stat) {
                $stat->opened = 1;
                if (!$stat->update()) {
                    throw new Exception('Не удалось обновить запись');
                }
                return [
                    'success' => true,
                ];
            }
            throw new Exception('Фирма в данном запросе не участвовала');

        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ];
        }
    }

    /**
     * Функция записи статистики открытых фирм
     *
     * @param int $id
     *
     * @return array
     */
    public function actionStatFirmOpenFirm($id = null)
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        try {
            $query_id = isset($_SESSION['firms_last_query_id']) ? $_SESSION['firms_last_query_id'] : null;
            if (!$query_id || !$id) {
                throw new Exception('Не указан firm_id или не было еще не одного запроса от пользователя');
            }

            /** @var StatFirmsFirms $stat */
            $stat = StatFirmsFirms::find()->andWhere([
                'id_query' => $query_id,
                'id_firm'  => $id,
            ])->one();
            if ($stat) {
                $stat->opened = 1;
                if (!$stat->update()) {
                    throw new Exception('Не удалось обновить запись');
                }
                return [
                    'success' => true,
                ];
            }
            throw new Exception('Фирма в данном запросе не участвовала');

        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ];
        }
    }

    /**
     * Функция записи статистики открытых фирм
     *
     * @param int $id
     *
     * @return array
     */
    public function actionStatServiceOpenFirm($id = null)
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        try {
            $query_id = isset($_SESSION['service_last_query_id']) ? $_SESSION['service_last_query_id'] : null;
            if (!$query_id || !$id) {
                throw new Exception('Не указан firm_id или не было еще не одного запроса от пользователя');
            }

            /** @var StatServiceFirms $stat */
            $stat = StatServiceFirms::find()->andWhere([
                'id_query' => $query_id,
                'id_firm'  => $id,
            ])->one();
            if ($stat) {
                $stat->opened = 1;
                if (!$stat->update()) {
                    throw new Exception('Не удалось обновить запись');
                }
                return [
                    'success' => true,
                ];
            }
            throw new Exception('Фирма в данном запросе не участвовала');

        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ];
        }
    }
}
