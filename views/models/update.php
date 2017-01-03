<?php


/* @var $this yii\web\View */
/* @var $model app\models\CarModelsEN */
/* @var $model_types array */
/* @var $marks_list array */

$this->title = 'Update Car Models En: '.$model->Name;
$this->params['breadcrumbs'][] = ['label' => 'Car Models Ens', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->Name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="car-models-en-update" style="padding-top: 10px;">

    <?= $this->render('_form', [
        'model'       => $model,
        'model_types' => $model_types,
        'marks_list'  => $marks_list,
    ]) ?>

</div>
