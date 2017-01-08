<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\CarEngineModelsEN */
/* @var $types array */
/* @var $marks array */

$this->title = 'Create Car Engine Models En';
$this->params['breadcrumbs'][] = ['label' => 'Car Engine Models Ens', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="car-engine-models-en-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'marks' => $marks,
        'types' => $types,
    ]) ?>

</div>
