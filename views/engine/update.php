<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\CarEngineModelsEN */

$this->title = 'Update Car Engine Models En: ' . $model->Name;
$this->params['breadcrumbs'][] = ['label' => 'Car Engine Models Ens', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->Name, 'url' => ['view', 'id' => $model->id, 'ID_Mark' => $model->ID_Mark]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="car-engine-models-en-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
