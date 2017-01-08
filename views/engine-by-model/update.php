<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\CarEngineAndModelCorrespondencesEN */
/* @var $ID_Mark int */
/* @var $ID_Model int */
/* @var $models array */
/* @var $engines array */

$this->title = 'Изменить привязку двигателя у модели';
$this->params['breadcrumbs'][] = ['label' => 'Car Engine And Model Correspondences Ens', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->ID_Mark, 'url' => ['view', 'ID_Mark' => $model->ID_Mark, 'ID_Engine' => $model->ID_Engine, 'ID_Model' => $model->ID_Model]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="car-engine-and-model-correspondences-en-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model'     => $model,
        'ID_Mark'   => $ID_Mark,
        'ID_Model'  => $ID_Model,
        'models'    => $models,
        'engines'   => $engines,
    ]) ?>

</div>
