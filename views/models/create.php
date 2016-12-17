<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\CarModelsEN */
/* @var $model_types array */
/* @var $marks_list array */

$this->title = 'Create Car Models En';
$this->params['breadcrumbs'][] = ['label' => 'Car Models Ens', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="car-models-en-create"  style="padding-top: 10px;">

    <?= $this->render('_form', [
        'model' => $model,
        'model_types' => $model_types,
        'marks_list' => $marks_list,
    ]) ?>

</div>
