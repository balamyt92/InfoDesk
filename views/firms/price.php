<?php
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;
use kartik\export\ExportMenu;

$this->title = 'Прайс фирмы';
?>

<div class="price-firm">
	<?php Pjax::begin(); ?>    
	<?= GridView::widget([
		'dataProvider' => $model,
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
			    'dataProvider' 	  => $model,
			    'fontAwesome' 	  => true,
			    'target' 		  => ExportMenu::TARGET_SELF,
			    'dropdownOptions' => [
			        'label' => 'Экспорт прайса',
			        'class' => 'btn btn-default'
			    ],
                'pdfLibrary' => PHPExcel_Settings::PDF_RENDERER_MPDF,
			    'pdfLibraryPath' => '@vendor/mpdf/mpdf',
			]),
			'{export}',
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