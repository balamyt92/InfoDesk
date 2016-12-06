<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Firms */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="firms-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'Name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Address')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'Phone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Comment')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'Enabled')->checkbox() ?>

    <?= $form->field($model, 'ActivityType')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'OrganizationType')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'District')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Fax')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'URL')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'OperatingMode')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'Identifier')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Priority')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
