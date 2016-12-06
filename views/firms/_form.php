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

    <?= $form->field($model, 'Enabled')->dropDownList([ 1 => 'Да',  0 => 'Нет']) ?>

    <?= $form->field($model, 'Identifier')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Priority')->textInput() ?>

    <?= $form->field($model, 'Comment')->textarea(['rows' => 2]) ?>

    <div class="form-group">
        <div class="col-sm-3"></div>
        <div class="col-sm-6">
            <?= Html::submitButton($model->isNewRecord ? 'Созать' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            <?= Html::a('Отмена', 'javascript:history.back()', ['class' => 'btn btn-warning']) ?>
            <?php
            \yii\bootstrap\Modal::begin([
                'header' => '<h1 id="firmName"></h1>',
                'toggleButton' => [
                    'label' => 'Предпросмотр',
                    'class' => 'btn btn-default',
                    'id'    => 'prew',
                ],
            ]);

            echo "
                <table class=\"table table-condensed\">
                <tbody>
                    <tr>
                        <td style=\"border-top: none\"><label>Организация</label></td>
                        <td  style=\"border-top: none\" id=\"firmOrganizationType\"></td>
                    </tr>
                    <tr>
                        <td><label>Профиль деятельности</label></td>
                        <td id=\"firmActivityType\"></td>
                    </tr>
                    <tr>
                        <td><label>Район</label></td>
                        <td id=\"firmDistrict\"></td>
                    </tr>
                    <tr>
                        <td><label>Адрес</label></td>
                        <td id=\"firmAddress\"></td>
                    </tr>
                    <tr>
                        <td><label>Телефон</label></td>
                        <td id=\"firmPhone\"></td>
                    </tr>
                    <tr>
                        <td><label>Факс</label></td>
                        <td id=\"firmFax\"></td>
                    </tr>
                    <tr>
                        <td><label>Email</label></td>
                        <td id=\"firmEmail\"></td>
                    </tr>
                    <tr>
                        <td><label>Сайт</label></td>
                        <td id=\"firmURL\"></td>
                    </tr>
                    <tr>
                        <td><label>Режим работы</label></td>
                        <td id=\"firmOperatingMode\"></td>
                    </tr>
                    <tr>
                        <td><label>Примечание</label></td>
                        <td id=\"firmComment\"></td>
                    </tr>
                </tbody>
            </table>
            ";
            \yii\bootstrap\Modal::end();?>
        </div>
    </div>

    <?php ActiveForm::end();

    $this->registerCss("
        .form-group {
            margin-bottom: 0px; 
        }");

    $this->registerJs("
    $('#prew')[0].onclick = function () {
        $('#firmName')[0].innerHTML             = $('#firms-name')[0].value;
        $('#firmOrganizationType')[0].innerHTML = $('#firms-organizationtype')[0].value;
        $('#firmActivityType')[0].innerHTML     = $('#firms-activitytype')[0].value;
        $('#firmDistrict')[0].innerHTML         = $('#firms-district')[0].value;
        $('#firmAddress')[0].innerHTML          = $('#firms-address')[0].value;
        $('#firmPhone')[0].innerHTML            = $('#firms-phone')[0].value;
        $('#firmFax')[0].innerHTML              = $('#firms-fax')[0].value;
        $('#firmEmail')[0].innerHTML            = $('#firms-email')[0].value;
        $('#firmURL')[0].innerHTML              = $('#firms-url')[0].value;
        $('#firmOperatingMode')[0].innerHTML    = $('#firms-operatingmode')[0].value;
        $('#firmComment')[0].innerHTML          = $('#firms-comment')[0].value;
    }
    ", yii\web\View::POS_READY);

    ?>

</div>
