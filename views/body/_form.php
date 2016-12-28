<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\CarBodyModelsEN */
/* @var $form yii\widgets\ActiveForm */
/* @var $types array */
/* @var $models array */
?>

<div class="car-body-models-en-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'ID_Mark')->label(false)->hiddenInput() ?>

    <?= $form->field($model, 'ID_Model')->dropDownList($models) ?>

    <?= $form->field($model, 'Name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ID_Type')->dropDownList($types) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
