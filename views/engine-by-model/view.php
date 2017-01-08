<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\CarEngineAndModelCorrespondencesEN */

$this->title = $model->ID_Mark;
$this->params['breadcrumbs'][] = ['label' => 'Car Engine And Model Correspondences Ens', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="car-engine-and-model-correspondences-en-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'ID_Mark' => $model->ID_Mark, 'ID_Engine' => $model->ID_Engine, 'ID_Model' => $model->ID_Model], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'ID_Mark' => $model->ID_Mark, 'ID_Engine' => $model->ID_Engine, 'ID_Model' => $model->ID_Model], [
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
            'ID_Engine',
            'ID_Model',
        ],
    ]) ?>

</div>
