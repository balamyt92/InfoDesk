<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\CarBodyModelsEN */

$this->title = $model->Name;
$this->params['breadcrumbs'][] = ['label' => 'Car Body Models Ens', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="car-body-models-en-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id, 'ID_Mark' => $model->ID_Mark, 'ID_Model' => $model->ID_Model], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id, 'ID_Mark' => $model->ID_Mark, 'ID_Model' => $model->ID_Model], [
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
            'id',
            'ID_Mark',
            'ID_Model',
            'Name',
            'ID_Type',
        ],
    ]) ?>

</div>
