<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\CarBodyModelsEN */

$this->title = 'Create Car Body Models En';
$this->params['breadcrumbs'][] = ['label' => 'Car Body Models Ens', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="car-body-models-en-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
