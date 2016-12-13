<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ServicePresence */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="firms-form">

    <?php $form = ActiveForm::begin(['layout' => 'horizontal']); ?>

    <?= $form->field($model, 'ID_Service')->dropDownList($items) ?>

    <?= $form->field($model, 'Comment')->textarea(['rows' => 3]) ?>

    <?= $form->field($model, 'CarList')->textarea(['rows' => 3]) ?>

    <?= $form->field($model, 'Coast')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <div class="col-sm-3"></div>
        <div class="col-sm-6">
            <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Ок', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            <?= Html::a('Отмена', 'javascript:history.back()', ['class' => 'btn btn-warning']) ?>
        </div>
    </div>

    <?php ActiveForm::end();?>

</div>
