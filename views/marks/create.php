<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\CarMarksEN */
/* @var $mark_types array */

$this->title = 'Create Car Marks En';
$this->params['breadcrumbs'][] = ['label' => 'Создание марки', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="car-marks-en-create" style="margin-top: 20px;">

    <?= $this->render('_form', [
        'model' => $model,
        'mark_types' => $mark_types,
    ]) ?>

</div>
