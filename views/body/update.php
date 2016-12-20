<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\CarBodyModelsEN */

$this->title = 'Update Car Body Models En: ' . $model->Name;
$this->params['breadcrumbs'][] = ['label' => 'Car Body Models Ens', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->Name, 'url' => ['view', 'id' => $model->id, 'ID_Mark' => $model->ID_Mark, 'ID_Model' => $model->ID_Model]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="car-body-models-en-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
