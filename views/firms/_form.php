<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Firms */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="firms-form">

    <?php $form = ActiveForm::begin(['layout' => 'horizontal']); ?>

    <?= $form->field($model, 'Name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'OrganizationType')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ActivityType')->textarea(['rows' => 3]) ?>

    <?= $form->field($model, 'District')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Address')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Phone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Fax')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'URL')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'OperatingMode')->textarea(['rows' => 3]) ?>

    <?= $form->field($model, 'Enabled')->dropDownList([ 0 => 'Нет', 1 => 'Да']) ?>

    <?= $form->field($model, 'Identifier')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Priority')->textInput() ?>

    <?= $form->field($model, 'Comment')->textarea(['rows' => 2]) ?>

    <div class="form-group">
        <div class="col-sm-3"></div>
        <div class="col-sm-6">
            <?= Html::submitButton($model->isNewRecord ? 'Созать' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            <?= Html::a('Отмена', 'javascript:history.back()', ['class' => 'btn btn-warning']) ?>
        </div>
    </div>

    <?php ActiveForm::end();

    $this->registerCss("
        .form-group {
            margin-bottom: 0px; 
        }");
    ?>

</div>
