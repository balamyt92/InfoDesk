<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\CarEngineAndBodyCorrespondencesEN */
/* @var $ID_Mark int */
/* @var $ID_Model int */
/* @var $ID_Body int */
/* @var $models array */
/* @var $engines array */
/* @var $bodys array */

$this->title = 'Добавить связь двигателя к кузову';
$this->params['breadcrumbs'][] = ['label' => 'Car Engine And Body Correspondences Ens', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="car-engine-and-body-correspondences-en-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model'     => $model,
        'ID_Mark'   => $ID_Mark,
        'ID_Model'  => $ID_Model,
        'ID_Body'   => $ID_Body,
        'models'    => $models,
        'engines'   => $engines,
        'bodys'     => $bodys,
    ]) ?>

</div>
