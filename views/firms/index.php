<?php

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
                'vAlign'     => GridView::ALIGN_MIDDLE,
                'width'      => '20px',
            ],
            [
                'attribute'  => 'Name',
                'contentOptions' => [
                    'style' => 'max-width: 100px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis'
                ],
            ],
            [
                'attribute'  => 'Phone',
                'contentOptions' => [
                    'style' => 'max-width: 100px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis'
                ],
            ],
            [
                'attribute'  => 'Address',
                'contentOptions' => [
                    'style' => 'max-width: 100px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis'
                ],
            ],
            [
                'class'      => 'kartik\grid\BooleanColumn',
                'attribute'  => 'Enabled',
                'vAlign'     => GridView::ALIGN_MIDDLE,
                'trueLabel'  => 'Да',
                'falseLabel' => 'Нет',
                'width'      => '20px',
                'hAlign'    => GridView::ALIGN_CENTER,
            ],
            [
                'attribute' => 'Priority',
                'width'     => '20px',
                'vAlign'    => GridView::ALIGN_MIDDLE,
                'hAlign'    => GridView::ALIGN_CENTER,
            ],
            [
                'attribute'  => 'Comment',
                'contentOptions' => [
                    'style' => 'max-width: 100px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis'
                ],
            ],
            [
                'class'     => 'yii\grid\ActionColumn',
                'contentOptions' => [
                    'style' => 'max-width: 100px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis'
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
            "<span class=\"btn-group\">{summary}</span>",
            "<span class=\"btn-group\">{$create_button}</span>",
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
    ]); ?>
<?php Pjax::end(); ?></div>

<?php
// попросили, других способов это сделать не нашел
$script = <<< JS
$('#w1-filters > td:nth-child(5) > select > option:nth-child(1)').html('Все');
JS;
$this->registerJs($script, yii\web\View::POS_READY);
