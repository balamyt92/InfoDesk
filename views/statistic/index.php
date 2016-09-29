<?php
/* @var $this yii\web\View */

$this->title = 'Статистика';

use yii\data\SqlDataProvider;
use yii\grid\GridView;
use yii\widgets\Pjax;

$countPartsQuery = Yii::$app->db->createCommand('
    SELECT count(*) FROM stat_parts_query
')->queryScalar();

$providerPartsQuery = new SqlDataProvider([
    'sql'        => 'SELECT * FROM stat_parts_query',
    'params'     => [],
    'totalCount' => $countPartsQuery,
    'pagination' => [
        'pageSize' => 10,
    ],
    'sort' => [
        'attributes' => [
            'date_time',
        ],
    ],
]);

$countPartsFirms = Yii::$app->db->createCommand('
    SELECT count(*) FROM (SELECT id_firm, SUM(opened) as total FROM stat_parts_firms GROUP BY id_firm) cnt
')->queryScalar();

$providerPartsFirms = new SqlDataProvider([
    'sql'        => 'SELECT id_firm, SUM(opened) as total FROM stat_parts_firms GROUP BY id_firm',
    'params'     => [],
    'totalCount' => $countPartsFirms,
    'pagination' => [
        'pageSize' => 10,
    ],
    'sort' => [
        'attributes' => [
            'id_firm' => [
                'asc'     => ['id_firm' => SORT_ASC],
                'desc'    => ['id_firm' => SORT_DESC],
                'default' => SORT_ASC,
                'label'   => 'Фирма',
            ],
            'total' => [
                'asc'     => ['total' => SORT_ASC],
                'desc'    => ['total' => SORT_DESC],
                'default' => SORT_ASC,
                'label'   => 'Названа раз',
            ],
        ],
        'defaultOrder' => ['id_firm' => SORT_ASC],
    ],
]);


$countFirmsQuery = Yii::$app->db->createCommand('
    SELECT count(*) FROM stat_firms_query
')->queryScalar();

$providerFirmsQuery = new SqlDataProvider([
    'sql'        => 'SELECT * FROM stat_firms_query',
    'params'     => [],
    'totalCount' => $countFirmsQuery,
    'pagination' => [
        'pageSize' => 10,
    ],
    'sort' => [
        'attributes' => [
            'date_time',
        ],
    ],
]);

$countFirmsFirms = Yii::$app->db->createCommand('
    SELECT count(*) FROM (SELECT id_firm, SUM(opened) as total FROM stat_firms_firms GROUP BY id_firm) cnt
')->queryScalar();

$providerFirmsFirms = new SqlDataProvider([
    'sql'        => 'SELECT id_firm, SUM(opened) as total FROM stat_firms_firms GROUP BY id_firm',
    'params'     => [],
    'totalCount' => $countFirmsFirms,
    'pagination' => [
        'pageSize' => 10,
    ],
    'sort' => [
        'attributes' => [
            'id_firm' => [
                'asc'     => ['id_firm' => SORT_ASC],
                'desc'    => ['id_firm' => SORT_DESC],
                'default' => SORT_ASC,
                'label'   => 'Фирма',
            ],
            'total' => [
                'asc'     => ['total' => SORT_ASC],
                'desc'    => ['total' => SORT_DESC],
                'default' => SORT_ASC,
                'label'   => 'Названа раз',
            ],
        ],
        'defaultOrder' => ['id_firm' => SORT_ASC],
    ],
]);

$countServiceQuery = Yii::$app->db->createCommand('
    SELECT count(*) FROM stat_service_query
')->queryScalar();

$providerServiceQuery = new SqlDataProvider([
    'sql'        => 'SELECT * FROM stat_service_query',
    'params'     => [],
    'totalCount' => $countServiceQuery,
    'pagination' => [
        'pageSize' => 10,
    ],
    'sort' => [
        'attributes' => [
            'date_time',
        ],
    ],
]);

$countServiceFirms = Yii::$app->db->createCommand('
    SELECT count(*) FROM (SELECT id_firm, SUM(opened) as total FROM stat_service_firms GROUP BY id_firm) cnt
')->queryScalar();

$providerServiceFirms = new SqlDataProvider([
    'sql'        => 'SELECT id_firm, SUM(opened) as total FROM stat_service_firms GROUP BY id_firm',
    'params'     => [],
    'totalCount' => $countServiceFirms,
    'pagination' => [
        'pageSize' => 10,
    ],
    'sort' => [
        'attributes' => [
            'id_firm' => [
                'asc'     => ['id_firm' => SORT_ASC],
                'desc'    => ['id_firm' => SORT_DESC],
                'default' => SORT_ASC,
                'label'   => 'Фирма',
            ],
            'total' => [
                'asc'     => ['total' => SORT_ASC],
                'desc'    => ['total' => SORT_DESC],
                'default' => SORT_ASC,
                'label'   => 'Названа раз',
            ],
        ],
        'defaultOrder' => ['id_firm' => SORT_ASC],
    ],
]);
?>

<div class="row" style="margin-top: 20px">
    <div class="col-sm-12">
        <div class="panel panel-default">
            <div class="panel-heading">Статистика поиска запчастей</div>
            <div class="panel-body">
                <?php
                Pjax::begin();
                echo GridView::widget([
                'dataProvider' => $providerPartsQuery,
                ]);
                Pjax::end();

                Pjax::begin();
                echo GridView::widget([
                    'dataProvider' => $providerPartsFirms,
                    'columns'      => [
                        ['class' => 'yii\grid\SerialColumn'],

                        'id_firm',
                        'total',
                    ],
                ]);
                Pjax::end();
                ?>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <div class="panel panel-default">
            <div class="panel-heading">Статистика поиска фирм</div>
            <div class="panel-body">
                <?php
                Pjax::begin();
                echo GridView::widget([
                'dataProvider' => $providerFirmsQuery,
                ]);
                Pjax::end();

                Pjax::begin();
                echo GridView::widget([
                    'dataProvider' => $providerFirmsFirms,
                    'columns'      => [
                        ['class' => 'yii\grid\SerialColumn'],

                        'id_firm',
                        'total',
                    ],
                ]);
                Pjax::end();
                ?>
            </div>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="panel panel-default">
            <div class="panel-heading">Статистика поиска сервисов</div>
            <div class="panel-body">
                <?php
                Pjax::begin();
                echo GridView::widget([
                'dataProvider' => $providerServiceQuery,
                ]);
                Pjax::end();

                Pjax::begin();
                echo GridView::widget([
                    'dataProvider' => $providerServiceFirms,
                    'columns'      => [
                        ['class' => 'yii\grid\SerialColumn'],

                        'id_firm',
                        'total',
                    ],
                ]);
                Pjax::end();
                ?>
            </div>
        </div>
    </div>
</div>