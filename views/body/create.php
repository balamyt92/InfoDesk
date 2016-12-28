<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\CarBodyModelsEN */
/* @var $types array */
/* @var $models array */

$this->title = 'Добавить кузов';
$this->params['breadcrumbs'][] = ['label' => 'Добавить кузов', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="car-body-models-en-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model'  => $model,
        'types'  => $types,
        'models' => $models,
    ]) ?>

</div>
