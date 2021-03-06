<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\CarEngineModelsEN */
/* @var $form yii\widgets\ActiveForm */
/* @var $types array */
/* @var $marks array */
?>

<div class="car-engine-models-en-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'ID_Mark')->dropDownList($marks) ?>

    <?= $form->field($model, 'Name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ID_Type')->dropDownList($types) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Изменить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a('Отмена', 'javascript:history.back()', ['class' => 'btn btn-warning']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
