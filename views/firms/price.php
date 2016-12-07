<?php
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

$this->title = 'Прайс фирмы';
?>

<div class="price-firm">
	<?= Html::a('Отмена', 'javascript:history.back()', ['class' => 'btn btn-warning']) ?>

	<?php Pjax::begin(); ?>    
	<?= GridView::widget([
		'dataProvider' => $model,
	]);?>
	<?php Pjax::end();?>

</div>