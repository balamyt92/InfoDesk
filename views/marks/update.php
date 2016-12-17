<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\CarMarksEN */
/* @var $mark_types array */

$this->title = 'Редактирование марки: ' . $model->Name;
$this->params['breadcrumbs'][] = ['label' => 'Редактирование марки', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->Name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменение';
?>
<div class="car-marks-en-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'mark_types' => $mark_types,
    ]) ?>

</div>
