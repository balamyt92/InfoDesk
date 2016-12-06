<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\FirmsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Фирмы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="firms-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]);?>

    <p>
        <?= Html::a('Новая фирма', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= kartik\grid\GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel'  => $searchModel,
        'columns'      => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute'  => 'id',
                'vAlign'     => 'middle',
            ],
            'Name',
            'Phone',
            'Address:ntext',
            [
                'class'      => 'kartik\grid\BooleanColumn',
                'attribute'  => 'Enabled',
                'vAlign'     => 'middle',
                'trueLabel'  => 'Да',
                'falseLabel' => 'Нет',
            ],
            'Priority',
            'Comment:ntext',

            ['class' => 'yii\grid\ActionColumn'],
        ],
        'panel' => [
            'type' => 'default',
        ]
    ]); ?>
<?php Pjax::end(); ?></div>
