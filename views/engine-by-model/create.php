<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\CarEngineAndModelCorrespondencesEN */
/* @var $ID_Mark int */
/* @var $ID_Model int */
/* @var $models array */
/* @var $engines array */

$this->title = 'Добавить привязку двигателя к модели';
$this->params['breadcrumbs'][] = ['label' => 'Car Engine And Model Correspondences Ens', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="car-engine-and-model-correspondences-en-create">
    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model'     => $model,
        'ID_Mark'   => $ID_Mark,
        'ID_Model'  => $ID_Model,
        'models'    => $models,
        'engines'   => $engines,
    ]) ?>
</div>
