<?php

use kartik\export\ExportMenu;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CarEngineModelsEnSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Двигатели';
$this->params['breadcrumbs'][] = $this->title;

$add_button = Html::a('Добавить двигатель', ['create'], ['class' => 'btn btn-success']);
?>
<div class="car-engine-models-en-index"  style="padding-top: 10px;">

    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute'      => 'ID_Mark',
                'value'          => 'iDMark.Name',
            ],
            'Name',
            [
                'attribute'      => 'ID_Type',
                'value'          => 'iDType.Name',
            ],
            ['class' => 'yii\grid\ActionColumn'],
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
    ]); ?>
    <?php Pjax::end(); ?>
</div>
