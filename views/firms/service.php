<?php
/**
 * @var $model app\models\ServicePresence
 * @var $exportModel app\models\ServicePresence
 * @var $filterModel app\models\ServicePresenceSearch
 * @var $services array
 */

use kartik\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;
use kartik\export\ExportMenu;

$this->title = 'Услуги фирмы';

$style = 'max-width: 100px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis';

$columns = [
	[
		'attribute' => 'ID_Service',
		'value' => 'iDService.Name',
		'contentOptions' => [
			'style' => $style
		],
		'filter' => $services,
	],
	[
		'attribute' => 'Comment',
		'contentOptions' => [
			'style' => $style
		],
	],
	[
		'attribute' => 'CarList',
		'contentOptions' => [
			'style' => $style
		],
	],
	[
		'attribute' => 'Coast',
		'contentOptions' => [
			'style' => $style
		],
	],
]
?>

<div class="service-firm">
	<?php Pjax::begin(); ?>    
	<?= GridView::widget([
		'dataProvider' => $model,
		'columns' => $columns,
		'filterModel' => $filterModel,
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
            "<span class=\"btn-group\">{summary}</span>",
            "<span class=\"btn-group\">" . Html::a('Назад', 'javascript:history.back()', ['class' => 'btn btn-warning']) . "</span>",
            ExportMenu::widget([
                'dataProvider'    => $exportModel,
                'fontAwesome'     => true,
                'target'          => ExportMenu::TARGET_SELF,
                'dropdownOptions' => [
                    'label' => 'Экспорт списка услуг',
                    'class' => 'btn btn-default'
                ],
                'showConfirmAlert' => false,
                'pdfLibrary' => PHPExcel_Settings::PDF_RENDERER_MPDF,
                'pdfLibraryPath' => '@vendor/mpdf/mpdf',
            ]),
            '{toggleData}',
        ],
        'panelTemplate' => "
            <div class=\"{prefix}{type}\">
                {panelBefore}
                {items}
                {panelAfter}
                {panelFooter}
            </div>
        ",
	]);?>
	<?php Pjax::end();?>

</div>