<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\CarPresenceEN */
/* @var $form yii\widgets\ActiveForm */
/* @var $names array */
/* @var $marks array */
/* @var $models array */
/* @var $bodys array */
/* @var $engines array */

?>
<div class="firms-form">

    <?php $form = ActiveForm::begin(['layout' => 'horizontal']); ?>

    <?= $form->field($model, 'ID_Name')->dropDownList($names) ?>

    <?= $form->field($model, 'ID_Mark')->dropDownList($marks, [
        'onchange'=> '
            $.get( "'.Yii::$app->urlManager->createUrl('firms/get-models').'&id="+$(this).val(), function( data ) {
              $( "select#models" ).html( data );
            });
            $.get( "'.Yii::$app->urlManager->createUrl('firms/get-engines-by-mark').'&id_mark="+$(this).val(), function( data ) {
              $( "select#engines" ).html( data );
            });',
    ]) ?>

    <?= $form->field($model, 'ID_Model')->dropDownList($models ? $models : [], [
        'id'      => 'models',
        'onchange'=> '
            $.get( "'.Yii::$app->urlManager->createUrl('firms/get-bodys').'&id_models="+$(this).val(), function( data ) {
              $( "select#bodys" ).html( data );
            });
            $.get( "'.Yii::$app->urlManager->createUrl('firms/get-engines-by-model').'&id_model="+$(this).val(), function( data ) {
              $( "select#engines" ).html( data );
            });',
    ]) ?>

    <?= $form->field($model, 'ID_Body')->dropDownList($bodys ? $bodys : [], [
        'id'      => 'bodys',
        'onchange'=> '
            $.get( "'.Yii::$app->urlManager->createUrl('firms/get-engines-by-body').'&id_body="+$(this).val(), function( data ) {
              $( "select#engines" ).html( data );
            });',
    ]) ?>

    <?= $form->field($model, 'ID_Engine')->dropDownList($engines ? $engines : [], ['id' => 'engines']) ?>

    <?= $form->field($model, 'Cost')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'CarYear')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Comment')->textarea(['rows' => 3]) ?>

    <?= $form->field($model, 'TechNumber')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Catalog_Number')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <div class="col-sm-3"></div>
        <div class="col-sm-6">
            <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Ок', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            <?= Html::a('Отмена', 'javascript:history.back()', ['class' => 'btn btn-warning']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>
