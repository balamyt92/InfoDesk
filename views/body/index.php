<?php

use kartik\export\ExportMenu;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CarBodyModelsEnSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $types array */
/* @var $models array */
/* @var $ID_Mark int */

$this->title = 'Кузова';
$this->params['breadcrumbs'][] = $this->title;

$ID_Model = isset($_GET['CarBodyModelsEnSearch']['ID_Model']) ? $_GET['CarBodyModelsEnSearch']['ID_Model'] : null;
$session = Yii::$app->session;

$add_button = Html::a('Добавить кузов',
    [
        'create',
        'ID_Mark'  => $ID_Mark,
        'ID_Model' => $ID_Model,
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

$engines_button = Html::a('Двигатели модели', [
        'engine-by-model/index',
        'ID_Mark'  => $ID_Mark,
        'ID_Model' => $ID_Model,
        'CarEngineAndModelCorrespondencesENSearch' => [
            'ID_Model' => $ID_Model,
            'engine'   => '',
        ],
    ], ['class' => 'btn btn-warning']);

$style = 'max-width: 100px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis';

$columns = [
    ['class' => 'yii\grid\SerialColumn'],
    [
        'label' => 'Марка',
        'value' => 'iDMark.Name',
    ],
    [
        'attribute'      => 'ID_Model',
        'value'          => 'iDModel.Name',
        'filter'         => $models,
    ],
    [
        'attribute'      => 'Name',
        'contentOptions' => [
            'style' => $style,
        ],
    ],
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
        'template'       => '{update} {delete} {engines}',
        'buttons'        => [
            'engines' => function ($url, $model, $key) {
                $title = 'Двигатели';
                $options = array_merge([
                    'title'      => $title,
                    'aria-label' => $title,
                    'data-pjax'  => '0',
                ]);

                return Html::a($title, [
                    'engine-by-body/index',
                    'ID_Mark'                                 => $model->ID_Mark,
                    'ID_Model'                                => $model->ID_Model,
                    'ID_Body'                                 => $model->id,
                    'CarEngineAndBodyCorrespondencesENSearch' => [
                        'ID_Mark'  => $model->ID_Mark,
                        'ID_Model' => $model->ID_Model,
                        'ID_Body'  => $model->id,
                    ],
                ], $options);
            },
        ],
    ],
];
?>
<div class="car-body-models-en-index"  style="padding-top: 10px;">
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
            "{$engines_button}",
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
                'pdfLibrary'       => PHPExcel_Settings::PDF_RENDERER_MPDF,
                'pdfLibraryPath'   => '@vendor/mpdf/mpdf',
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
