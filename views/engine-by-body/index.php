<?php

use kartik\export\ExportMenu;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CarEngineAndBodyCorrespondencesENSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $ID_Mark int */
/* @var $ID_Model int */
/* @var $ID_Body int */
/* @var $models int */
/* @var $bodys int */

$this->title = 'Двигатели в кузове';
$this->params['breadcrumbs'][] = $this->title;
$session = Yii::$app->session;

$add_button = Html::a('Добавить двигатель',
    [
        'create',
        'ID_Mark'  => isset($_GET['CarEngineAndBodyCorrespondencesENSearch']['ID_Mark']) ? $_GET['CarEngineAndBodyCorrespondencesENSearch']['ID_Mark'] : $ID_Mark,
        'ID_Model' => isset($_GET['CarEngineAndBodyCorrespondencesENSearch']['ID_Model']) ? $_GET['CarEngineAndBodyCorrespondencesENSearch']['ID_Model'] : $ID_Model,
        'ID_Body'  => isset($_GET['CarEngineAndBodyCorrespondencesENSearch']['ID_Body']) ? $_GET['CarEngineAndBodyCorrespondencesENSearch']['ID_Body'] : $ID_Body,
    ],
    ['class' => 'btn btn-success']);
$back_button = Html::a('Назад в модели', [
    'models/index',
    'ID_Mark'           => $ID_Mark,
    'CarModelsEnSearch' => $session->has('find-models') ? $session['find-models'] : '',
], ['class' => 'btn btn-warning']);

$back_to_mark_button = Html::a('Назад в марки', [
    'marks/index',
    'ID_Mark'           => $ID_Mark,
    'CarMarksEnSearch'  => $session->has('find-marks') ? $session['find-marks'] : '',
], ['class' => 'btn btn-warning']);

$back_to_body_button = Html::a('Назад в кузова', [
    'body/index',
    'ID_Mark'               => $ID_Mark,
    'CarBodyModelsEnSearch' => $session->has('find-bodys') ? $session['find-bodys'] : '',
], ['class' => 'btn btn-warning']);

$style = 'max-width: 100px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis';

$columns = [
    ['class' => 'yii\grid\SerialColumn'],
    [
        'label'     => 'Марка',
        'attribute' => 'ID_Mark',
        'value'     => 'iDMark.Name',
        'filter'    => false,
    ],
    [
        'label'     => 'Модель',
        'attribute' => 'ID_Model',
        'value'     => 'iDModel.Name',
        'filter'    => $models,
    ],
    [
        'label'     => 'Кузов',
        'attribute' => 'ID_Body',
        'value'     => 'iDBody.Name',
        'filter'    => $bodys,
    ],
    [
        'label'     => 'Двигтель',
        'attribute' => 'engine',
        'value'     => 'iDEngine.Name',
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
<div class="car-engine-and-body-correspondences-en-index" style="padding-top: 10px;">
    <?php Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel'  => $searchModel,
        'columns'      => $columns,
        'panel'        => [
            'type' => 'default',
        ],
        'pager'        => [
            'firstPageLabel' => 'Перв.',
            'lastPageLabel'  => 'Послед.',
            'prevPageLabel'  => 'Пред.',
            'nextPageLabel'  => 'След.',
            'maxButtonCount' => 20,
        ],
        'toolbar'      => [
            "{$add_button}",
            "{$back_to_mark_button}",
            "{$back_button}",
            "{$back_to_body_button}",
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
