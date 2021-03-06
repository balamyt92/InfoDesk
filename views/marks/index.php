<?php

use kartik\export\ExportMenu;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CarMarksEnSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $mark_types array */

$this->title = 'Марки';
$this->params['breadcrumbs'][] = $this->title;

$marks_create_button = Html::a('Добавить марку', ['create'], ['class' => 'btn btn-success']);
?>

<div class="car-marks-en-index" style="padding-top: 10px;">
    <?php Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel'  => $searchModel,
        'columns'      => [
            ['class' => 'yii\grid\SerialColumn'],
            'Name',
            [
                'attribute'      => 'ID_Type',
                'value'          => 'iDType.Name',
                'filter'         => $mark_types,
            ],
            [
                'class'          => 'yii\grid\ActionColumn',
                'template'       => '{update} {delete} {models} {bodys} {engines} {group}',
                'buttons'        => [
                    'models' => function ($url, $model, $key) {
                        $title = 'Модели';
                        $options = array_merge([
                            'title'      => $title,
                            'aria-label' => $title,
                            'data-pjax'  => '0',
                        ]);

                        return Html::a($title, $url, $options);
                    },
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
                    'group' => function ($url, $model, $key) {
                        if($model->ID_Type === 2) {
                            $title = 'Группа';
                            $options = array_merge([
                                'title'      => $title,
                                'aria-label' => $title,
                                'data-pjax'  => '0',
                            ]);
                            return Html::a($title, $url, $options);
                        }
                        return '';
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
            "{$marks_create_button}",
            ExportMenu::widget([
                'dataProvider'    => $dataProvider,
                'fontAwesome'     => true,
                'target'          => ExportMenu::TARGET_SELF,
                'dropdownOptions' => [
                    'label' => 'Экспорт списка марок',
                    'class' => 'btn btn-default',
                ],
                'showConfirmAlert' => false,
                'enableFormatter'  => false,
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
