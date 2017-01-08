<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\CarEngineAndBodyCorrespondencesEN */
/* @var $form yii\widgets\ActiveForm */
/* @var $ID_Mark int */
/* @var $ID_Model int */
/* @var $ID_Body int */
/* @var $models array */
/* @var $engines array */
/* @var $bodys array */
?>

<div class="car-engine-and-body-correspondences-en-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'ID_Mark')->label(false)->hiddenInput() ?>

    <?= $form->field($model, 'ID_Model')->dropDownList($models ? $models : [], [
        'onchange'=> '
            $.get("'.Yii::$app->urlManager->createUrl('engine-by-body/get-bodys').'&ID_Model="+$(this).val(), function( data ) {
              $( "select#models" ).html( data );
            });
            $.get("'.Yii::$app->urlManager->createUrl('engine-by-body/get-engines').'&ID_Model="+$(this).val(), function( data ) {
              $( "select#engines" ).html( data );
            });',
    ]) ?>

    <?= $form->field($model, 'ID_Body')->dropDownList($bodys ? $bodys : [], [ 'id' => 'models',]) ?>

    <?= $form->field($model, 'ID_Engine')->dropDownList($engines ? $engines : [], ['id' => 'engines',]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Изменить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a('Отмена', 'javascript:history.back()', ['class' => 'btn btn-warning']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
