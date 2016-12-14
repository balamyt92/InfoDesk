<?php

use kartik\export\ExportMenu;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\FirmsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Фирмы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="firms-index">
        <?php  $create_button = Html::a('Новая фирма', ['create'], ['class' => 'btn btn-success']) ?>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel'  => $searchModel,
        'columns'      => [
            [
                'attribute'  => 'id',
                'width'      => '20px',
            ],
            [
                'attribute'      => 'Name',
                'contentOptions' => [
                    'style' => 'max-width: 100px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis',
                ],
            ],
            [
                'attribute'      => 'Phone',
                'contentOptions' => [
                    'style' => 'max-width: 100px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis',
                ],
            ],
            [
                'attribute'      => 'Address',
                'contentOptions' => [
                    'style' => 'max-width: 100px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis',
                ],
            ],
            [
                'class'      => 'kartik\grid\BooleanColumn',
                'attribute'  => 'Enabled',
                'trueLabel'  => 'Да',
                'falseLabel' => 'Нет',
                'width'      => '20px',
                'hAlign'     => GridView::ALIGN_CENTER,
            ],
            [
                'attribute' => 'Priority',
                'width'     => '20px',
                'hAlign'    => GridView::ALIGN_CENTER,
            ],
            [
                'attribute'      => 'Comment',
                'contentOptions' => [
                    'style' => 'max-width: 100px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis',
                ],
            ],
            [
                'class'          => 'yii\grid\ActionColumn',
                'template'       => '{price} {service} {update} {delete}',
                'buttons'        => [
                    'price' => function ($url, $model, $key) {
                        $title = 'Прайс';
                        $options = array_merge([
                            'title'      => $title,
                            'aria-label' => $title,
                            'data-pjax'  => '0',
                        ]);
                        $icon = Html::tag('span', '', ['class' => 'glyphicon glyphicon-list']);

                        return Html::a($icon, $url, $options);
                    },
                    'service' => function ($url, $model, $key) {
                        $title = 'Услуги';
                        $options = array_merge([
                            'title'      => $title,
                            'aria-label' => $title,
                            'data-pjax'  => '0',
                        ]);
                        $icon = Html::tag('span', '', ['class' => 'glyphicon glyphicon-wrench']);

                        return Html::a($icon, $url, $options);
                    },
                ],
                'contentOptions' => [
                    'style' => 'max-width: 100px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis',
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
            '<span class="btn-group">{summary}</span>',
            "<span class=\"btn-group\">{$create_button}</span>",
            ExportMenu::widget([
                'dataProvider'    => $dataProvider,
                'fontAwesome'     => true,
                'target'          => ExportMenu::TARGET_SELF,
                'dropdownOptions' => [
                    'label' => 'Экспорт списка фирм',
                    'class' => 'btn btn-default',
                ],
                'showConfirmAlert' => false,
                'pdfLibrary'       => PHPExcel_Settings::PDF_RENDERER_MPDF,
                'pdfLibraryPath'   => '@vendor/mpdf/mpdf',
                'enableFormatter'  => false,
            ]),
            '{toggleData}',
        ],
        'panelTemplate' => '
            <div class="{prefix}{type}">
                {panelBefore}
                {items}
                {panelAfter}
                {panelFooter}
            </div>
        ',
    ]); ?>
<?php Pjax::end(); ?></div>

<?php
$this->registerCss('
    table > tbody> tr:hover {
        background-color: #b1f1e2 !important;
    }');
