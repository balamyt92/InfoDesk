<?php
/* @var $this yii\web\View */

use yii\data\SqlDataProvider;
use yii\widgets\Pjax;
use yii\grid\GridView;

$countPartsQuery = Yii::$app->db->createCommand('
    SELECT count(*) FROM stat_parts_query
')->queryScalar();

$providerPartsQuery = new SqlDataProvider([
    'sql' => 'SELECT * FROM stat_parts_query',
    'params' => [],
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
    SELECT count(*) FROM stat_parts_firms GROUP BY id_firm
')->queryScalar();

$providerPartsFirms = new SqlDataProvider([
    'sql' => 'SELECT id_firm, SUM(opened) as total FROM stat_parts_firms GROUP BY id_firm',
    'params' => [],
    'totalCount' => $countPartsFirms,
    'pagination' => [
        'pageSize' => 20,
    ],
    'sort' => [
        'attributes' => [
            'id_firm' => [
                'asc' => ['id_firm' => SORT_ASC],
                'desc' => ['id_firm' => SORT_DESC],
                'default' => SORT_ASC,
                'label' => 'Фирма',
            ],
            'total' => [
                'asc' => ['total' => SORT_ASC],
                'desc' => ['total' => SORT_DESC],
                'default' => SORT_ASC,
                'label' => 'Названа раз',
            ],
        ],
        'defaultOrder' => ['id_firm' => SORT_ASC],
    ],
]);

?>

<div class="row" style="margin-top: 20px">
    <div class="col-sm-3">
        <div class="panel panel-default">
            <div class="panel-heading">Статистика поиска фирм</div>
            <div class="panel-body">
                Panel content
            </div>
        </div>
    </div>
    <div class="col-sm-6">
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
                    'columns' => [
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
    <div class="col-sm-3">
        <div class="panel panel-default">
            <div class="panel-heading">Статистика поиска сервисов</div>
            <div class="panel-body">
                Panel content
            </div>
        </div>
    </div>
</div>