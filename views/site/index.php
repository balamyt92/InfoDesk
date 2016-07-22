<?php

/* @var $this yii\web\View */

$this->title = 'InfoDesk'; ?>

<div class="row">
    <div class="col-md-3">
        <h3>Поиск фирм</h3>
        <div class="form-inline">
            <div class="form-group input-group">
                <input id="search-line" type="text" class="form-control" onkeypress="return runSearch(event)">
                <span class="input-group-btn"><button class="btn btn-default" type="button" onclick="SearcherFirms.search();" value="default action"><i class="fa">Поиск</i></button></span>
            </div>
        </div>
    </div>


    <div class="col-md-4">
        <h3>Поиск запчастей</h3>
        <div>
            <label>Деталь</label>
            <?php
            echo \kartik\select2\Select2::widget([
                'name' => 'details',
                'value' => '',
                'data' => \yii\helpers\ArrayHelper::map(\app\models\CarENDetailNames::find()->orderBy("Name")->all(), 'id', 'Name'),
                'options' => ['placeholder' => 'Деталь'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);?>
            <label>Марка</label>
            <?php
            echo \kartik\select2\Select2::widget([
                'name' => 'marks',
                'value' => '',
                'data' => \yii\helpers\ArrayHelper::map(\app\models\CarMarksEN::find()->orderBy("Name")->all(), 'id', 'Name'),
                'options' => ['placeholder' => 'Марка'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);?>
            <label>Модель</label>
            <?php
            echo \kartik\select2\Select2::widget([
                'name' => 'models',
                'value' => '',
                'disabled' => true,
                'data' => \yii\helpers\ArrayHelper::map(\app\models\CarModelsEN::find()->orderBy("Name")->all(), 'id', 'Name'),
                'options' => ['placeholder' => 'Модель'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);?>
            <label>Кузов</label>
            <?php
            echo \kartik\select2\Select2::widget([
                'name' => 'models',
                'value' => '',
                'disabled' => true,
                'data' => \yii\helpers\ArrayHelper::map(\app\models\CarBodyModelsEN::find()->orderBy("Name")->all(), 'id', 'Name'),
                'options' => ['placeholder' => 'Кузов'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);?>
            <label>Двигатель</label>
            <?php
            echo \kartik\select2\Select2::widget([
                'name' => 'models',
                'value' => '',
                'disabled' => true,
                'data' => \yii\helpers\ArrayHelper::map(\app\models\CarEngineModelsEN::find()->orderBy("Name")->all(), 'id', 'Name'),
                'options' => ['placeholder' => 'Двигатель'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);?>
        </div>
    </div>

    <div class="col-md-5">
        <h3>Поиск сервисов</h3>
    </div>
</div>

<div class="col-md-12" style="padding-top: 20px;">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title" data-select-like-a-boss="1">Рузультаты поиска</h3>
        </div>
        <div id="search-result" class="panel-body">
            рузельтаты
        </div>
        <div id="loader" class="loader panel-body"></div>
    </div>
</div>



<div class="modal" id="modalFirm" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 id="firmName" class="modal-title"></h4>
      </div>
      <div class="modal-body">
        <table class="table table-condensed">
            <tbody>
                <tr>
                    <td style="border-top: none">Организация</td>
                    <td  style="border-top: none" id="firmOrganizationType"></td>
                </tr>
                <tr>
                    <td>Профиль деятельности</td>
                    <td id="firmActivityType"></td>
                </tr>
                <tr>
                    <td>Район</td>
                    <td id="firmDistrict"></td>
                </tr>
                <tr>
                    <td>Адрес</td>
                    <td id="firmAddress"></td>
                </tr>
                <tr>
                    <td>Телефон</td>
                    <td id="firmPhone"></td>
                </tr>
                <tr>
                    <td>Факс</td>
                    <td id="firmFax"></td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td id="firmEmail"></td>
                </tr>
                <tr>
                    <td>Сайт</td>
                    <td id="firmURL"></td>
                </tr>
                <tr>
                    <td>Режим работы</td>
                    <td id="firmOperatingMode"></td>
                </tr>
            </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->