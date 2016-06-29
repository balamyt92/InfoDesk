<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\FirmsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="firms-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'Name') ?>

    <?= $form->field($model, 'Address') ?>

    <?= $form->field($model, 'Phone') ?>

    <?= $form->field($model, 'Comment') ?>

    <?php // echo $form->field($model, 'Enabled')?>

    <?php // echo $form->field($model, 'ActivityType')?>

    <?php // echo $form->field($model, 'OrganizationType')?>

    <?php // echo $form->field($model, 'District')?>

    <?php // echo $form->field($model, 'Fax')?>

    <?php // echo $form->field($model, 'Email')?>

    <?php // echo $form->field($model, 'URL')?>

    <?php // echo $form->field($model, 'OperatingMode')?>

    <?php // echo $form->field($model, 'Identifier')?>

    <?php // echo $form->field($model, 'Priority')?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
