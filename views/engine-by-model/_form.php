<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\CarEngineAndModelCorrespondencesEN */
/* @var $form yii\widgets\ActiveForm */
/* @var $models array */
/* @var $engines array */
?>

<div class="car-engine-and-model-correspondences-en-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'ID_Mark')->label(false)->hiddenInput() ?>

    <?= $form->field($model, 'ID_Model')->dropDownList($models ? $models : []) ?>

    <?= $form->field($model, 'ID_Engine')->dropDownList($engines ? $engines : []) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Изменить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a('Отмена', 'javascript:history.back()', ['class' => 'btn btn-warning']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
