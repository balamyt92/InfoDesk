<?php

use kartik\export\ExportMenu;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CarModelsEnSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $model_types array */
/* @var $ID_Mark integer */

$this->title = 'Модели';
$this->params['breadcrumbs'][] = $this->title;

$add_button = Html::a('Добавить модель', ['create', 'ID_Mark' => $ID_Mark], ['class' => 'btn btn-success']);
?>
<div class="car-models-en-index" style="padding-top: 10px;">
    <?php Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'Name',
            [
                'attribute'      => 'ID_Type',
                'value'          => 'iDType.Name',
                'filter'         => $model_types,
            ],
            [
                'class'          => 'yii\grid\ActionColumn',
                'template'       => '{update} {delete} {bodys} {engines}',
                'buttons'        => [
                    'bodys' => function ($url, $model, $key) {
                        $title = 'Кузова';
                        $options = array_merge([
                            'title'      => $title,
                            'aria-label' => $title,
                            'data-pjax'  => '0',
                        ]);
                        return Html::a($title, $url, $options);
                    },
                    'engines' => function ($url, $model, $key) {
                        $title = 'Двигатели';
                        $options = array_merge([
                            'title'      => $title,
                            'aria-label' => $title,
                            'data-pjax'  => '0',
                        ]);
                        return Html::a($title, $url, $options);
                    },
                ],
                'contentOptions' => [
                    'style' => 'max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis',
                ],
            ],
        ],
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
        'toolbar'       => [
            '<span class="btn-group" style="padding-top: 10px;">{summary}</span>',
            "<span class=\"btn-group\">{$add_button}</span>",
            ExportMenu::widget([
                'dataProvider'    => $dataProvider,
                'fontAwesome'     => true,
                'target'          => ExportMenu::TARGET_SELF,
                'dropdownOptions' => [
                    'label' => 'Экспорт списка моделей',
                    'class' => 'btn btn-default',
                ],
                'showConfirmAlert' => false,
                'pdfLibrary'       => PHPExcel_Settings::PDF_RENDERER_MPDF,
                'pdfLibraryPath'   => '@vendor/mpdf/mpdf',
                'enableFormatter'  => false,
            ]),
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
