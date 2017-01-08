<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\CarEngineAndBodyCorrespondencesENSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="car-engine-and-body-correspondences-en-search">

    <?php $form = ActiveForm::begin([
        'action'  => ['index'],
        'method'  => 'get',
        'options' => [
            'data-pjax' => 1,
        ],
    ]); ?>

    <?= $form->field($model, 'ID_Mark') ?>

    <?= $form->field($model, 'ID_Model') ?>

    <?= $form->field($model, 'ID_Body') ?>

    <?= $form->field($model, 'ID_Engine') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
