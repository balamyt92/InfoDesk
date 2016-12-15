<?php
/**
 * @var app\models\CarPresenceEN
 * @var $exportModel             app\models\CarPresenceEN
 * @var $filterModel             app\models\CarPresenceSearch
 * @var $names                   array
 * @var $marks                   array
 * @var $models                  array
 * @var $bodys                   array
 * @var $engines                 array
 * @var $ID_Firm                 integer
 */
use kartik\export\ExportMenu;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

$this->title = 'Прайс фирмы';

$style = 'max-width: 100px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis';

$columns = [
    [
        'attribute'      => 'ID_Name',
        'value'          => 'iDName.Name',
        'contentOptions' => [
            'style' => $style,
        ],
        'filter' => $names,
    ],
    [
        'attribute'      => 'ID_Mark',
        'value'          => 'iDMark.Name',
        'contentOptions' => [
            'style' => $style,
        ],
        'filter' => $marks,
    ],
    [
        'attribute'      => 'ID_Model',
        'value'          => 'iDModel.Name',
        'contentOptions' => [
            'style' => $style,
        ],
        'filter' => $models,
    ],
    [
        'attribute'      => 'ID_Body',
        'value'          => 'iDBody.Name',
        'contentOptions' => [
            'style' => $style,
        ],
        'filter' => $bodys,
    ],
    [
        'attribute'      => 'ID_Engine',
        'value'          => 'iDEngine.Name',
        'contentOptions' => [
            'style' => $style,
        ],
        'filter' => $engines,
    ],
    [
        'attribute'      => 'Cost',
        'width'          => '20px',
        'contentOptions' => [
            'style' => $style,
        ],
    ],
    [
        'attribute'      => 'Comment',
        'width'          => '400px',
        'contentOptions' => [
            'style' => $style,
        ],
    ],
    [
        'attribute'      => 'Catalog_Number',
        'contentOptions' => [
            'style' => $style,
        ],
    ],
    [
        'attribute'      => 'TechNumber',
        'width'          => '20px',
        'contentOptions' => [
            'style' => $style,
        ],
    ],
    [
        'class'          => 'yii\grid\ActionColumn',
        'template'       => '{price-element-update} {price-element-delete}',
        'buttons'        => [
            'price-element-update' => function ($url, $model, $key) {
                $title = 'Изменить';
                $options = array_merge([
                    'title'      => $title,
                    'aria-label' => $title,
                    'data-pjax'  => '0',
                ]);
                $icon = Html::tag('span', '', ['class' => 'glyphicon glyphicon-pencil']);

                return Html::a($icon, $url, $options);
            },
            'price-element-delete' => function ($url, $model, $key) {
                $title = 'Удалить';
                $options = array_merge([
                    'title'      => $title,
                    'aria-label' => $title,
                    'data-pjax'  => '0',
                ]);
                $icon = Html::tag('span', '', ['class' => 'glyphicon glyphicon-trash']);

                return Html::a($icon, $url, $options);
            },
        ],
        'contentOptions' => [
            'style' => 'max-width: 100px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis',
        ],
    ],
];
?>

<div class="price-firm">
	<?php Pjax::begin(); ?>
	<?= GridView::widget([
        'dataProvider'  => $model,
        'filterModel'   => $filterModel,
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
        'toolbar'       => [
            '<span class="btn-group">{summary}</span>',
            Html::a('Добавить позицию', ['price-element-add', 'ID_Firm' => $ID_Firm], ['class' => 'btn btn-success']),
            '<span class="btn-group">'.Html::a('Назад', 'javascript:history.back()', ['class' => 'btn btn-warning']).'</span>',
            ExportMenu::widget([
                'dataProvider'      => $exportModel,
                'columns'           => $columns,
                'fontAwesome'       => true,
                'target'            => ExportMenu::TARGET_SELF,
                'dropdownOptions'   => [
                    'label' => 'Экспорт прайса',
                    'class' => 'btn btn-default',
                ],
                'showConfirmAlert' => false,
                'pdfLibrary'       => PHPExcel_Settings::PDF_RENDERER_MPDF,
                'pdfLibraryPath'   => '@vendor/mpdf/mpdf',
            ]),
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
	<?php Pjax::end(); ?>

</div>

<?php
$this->registerCss('
    table > tbody> tr:hover {
        background-color: #b1f1e2 !important;
    }');
