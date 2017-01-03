<?php

use kartik\export\ExportMenu;
use yii\helpers\Html;
use kartik\grid\GridView;
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
        'ID_Mark' => $ID_Mark,
        'ID_Model' => $ID_Model,
    ],
    ['class' => 'btn btn-success']);

$back_button = Html::a('Назад в модели', [
        'models/index',
        'ID_Mark' => $ID_Mark,
        'CarModelsEnSearch' => $session->has('find-models') ? $session['find-models'] : '',
    ], ['class' => 'btn btn-warning']);

$engines_button = Html::a('Двигатели', [
        'engine/index',
        'ID_Mark' => $ID_Mark,
        'ID_Model' => $ID_Model,
    ], ['class' => 'btn btn-warning']);

$style = 'max-width: 100px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis';

$columns = [
    ['class' => 'yii\grid\SerialColumn'],
    [
        'value'          => 'iDMark.Name',
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
        'class' => 'yii\grid\ActionColumn',
        'contentOptions' => [
            'style' => $style,
        ],
        'template'       => '{update} {delete}',
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