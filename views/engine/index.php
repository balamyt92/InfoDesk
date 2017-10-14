<?php

use kartik\export\ExportMenu;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CarEngineModelsEnSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $ID_Mark int */
/* @var $types array */

$this->title = 'Двигатели';
$this->params['breadcrumbs'][] = $this->title;
$session = Yii::$app->session;

$add_button = Html::a('Добавить двигатель', [
    'create',
    'ID_Mark'  => $ID_Mark,
], ['class' => 'btn btn-success']);

$back_to_mark_button = Html::a('Назад в марки', [
    'marks/index',
    'ID_Mark'          => $ID_Mark,
    'CarMarksEnSearch' => $session->has('find-marks') ? $session['find-marks'] : '',
], ['class' => 'btn btn-warning']);

$style = 'max-width: 100px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis';

$columns = [
    ['class' => 'yii\grid\SerialColumn'],
    [
        'label' => 'Марка',
        'value' => 'iDMark.Name',
    ],
    'Name',
    [
        'attribute'      => 'ID_Type',
        'value'          => 'iDType.Name',
        'filter'         => $types,
    ],
    [
        'class'          => 'yii\grid\ActionColumn',
        'contentOptions' => [
            'style' => $style,
        ],
        'template'       => '{update} {delete}',
    ],
];
?>
<div class="car-engine-models-en-index"  style="padding-top: 10px;">

    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider'  => $dataProvider,
        'filterModel'   => $searchModel,
        'columns'       => $columns,
        'panel'         => [
            'type' => 'default',
        ],
        'pager'         => [
            'firstPageLabel' => 'Перв.',
            'lastPageLabel'  => 'Послед.',
            'prevPageLabel'  => 'Пред.',
            'nextPageLabel'  => 'След.',
            'maxButtonCount' => 20,
        ],
        'toolbar'      => [
            "{$add_button}",
            "{$back_to_mark_button}",
            ExportMenu::widget([
                'dataProvider'      => $dataProvider,
                'columns'           => $columns,
                'fontAwesome'       => true,
                'target'            => ExportMenu::TARGET_SELF,
                'dropdownOptions'   => [
                    'label' => 'Экспорт списка',
                    'class' => 'btn btn-default',
                ],
                'showConfirmAlert' => false,
            ]),
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>

<?php
$this->registerCss('
    table > tbody> tr:hover {
        background-color: #b1f1e2 !important;
    }');
