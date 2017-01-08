<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\CarEngineAndBodyCorrespondencesEN */

$this->title = $model->ID_Mark;
$this->params['breadcrumbs'][] = ['label' => 'Car Engine And Body Correspondences Ens', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="car-engine-and-body-correspondences-en-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'ID_Mark' => $model->ID_Mark, 'ID_Model' => $model->ID_Model, 'ID_Body' => $model->ID_Body, 'ID_Engine' => $model->ID_Engine], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'ID_Mark' => $model->ID_Mark, 'ID_Model' => $model->ID_Model, 'ID_Body' => $model->ID_Body, 'ID_Engine' => $model->ID_Engine], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'ID_Mark',
            'ID_Model',
            'ID_Body',
            'ID_Engine',
        ],
    ]) ?>

</div>
