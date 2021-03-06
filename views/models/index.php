<?php

use kartik\export\ExportMenu;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CarModelsEnSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $model_types array */
/* @var $ID_Mark integer */

$this->title = 'Модели';
$this->params['breadcrumbs'][] = $this->title;
$session = Yii::$app->session;

$back_to_mark_button = Html::a('Назад в марки', [
    'marks/index',
    'ID_Mark'          => $ID_Mark,
    'CarMarksEnSearch' => $session->has('find-marks') ? $session['find-marks'] : '',
], ['class' => 'btn btn-warning']);

$add_button = Html::a('Добавить модель', ['create', 'ID_Mark' => $ID_Mark], ['class' => 'btn btn-success']);
?>
<div class="car-models-en-index" style="padding-top: 10px;">
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

                        return Html::a($title, [
                                'body/index',
                                'ID_Mark'               => $model->ID_Mark,
                                'CarBodyModelsEnSearch' => [
                                    'ID_Model' => $model->id,
                                    'Name'     => '',
                                    'ID_Type'  => '',
                                ],
                            ], $options);
                    },
                    'engines' => function ($url, $model, $key) {
                        $title = 'Двигатели';
                        $options = array_merge([
                            'title'      => $title,
                            'aria-label' => $title,
                            'data-pjax'  => '0',
                        ]);

                        return Html::a($title, [
                                'engine-by-model/index',
                                'ID_Mark'                                  => $model->ID_Mark,
                                'ID_Model'                                 => $model->id,
                                'CarEngineAndModelCorrespondencesENSearch' => [
                                    'ID_Mark'  => $model->ID_Mark,
                                    'ID_Model' => $model->id,
                                ],
                            ], $options);
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
            "{$add_button}",
            "{$back_to_mark_button}",
            ExportMenu::widget([
                'dataProvider'    => $dataProvider,
                'columns'         => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'Name',
                    [
                        'attribute'      => 'ID_Type',
                        'value'          => 'iDType.Name',
                        'filter'         => $model_types,
                    ],
                ],
                'fontAwesome'     => true,
                'target'          => ExportMenu::TARGET_SELF,
                'dropdownOptions' => [
                    'label' => 'Экспорт списка моделей',
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
