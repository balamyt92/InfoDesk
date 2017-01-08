<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\CarEngineAndModelCorrespondencesENSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="car-engine-and-model-correspondences-en-search">

    <?php $form = ActiveForm::begin([
        'action'  => ['index'],
        'method'  => 'get',
        'options' => [
            'data-pjax' => 1,
        ],
    ]); ?>

    <?= $form->field($model, 'ID_Mark') ?>

    <?= $form->field($model, 'ID_Engine') ?>

    <?= $form->field($model, 'ID_Model') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
