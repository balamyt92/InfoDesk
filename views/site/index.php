<?php

/* @var $this yii\web\View */

$this->title = 'InfoDesk'; ?>

<div class="row">
    <div class="col-md-3">
        <h3>Поиск фирм</h3>
        <div class="form-inline" style="margin-top: 35px;">
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
                'readonly' => true,
                'pluginLoading' => false,
                'theme' => \kartik\select2\Select2::THEME_BOOTSTRAP,
                'data' => \yii\helpers\ArrayHelper::map(\app\models\CarENDetailNames::find()->orderBy("Name")->all(), 'id', 'Name'),
                'options' => ['placeholder' => 'Деталь'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
                'pluginEvents' => [
                    "select2:select" => "function(data) {  searchParts.idDetail = data.params.data.id; }",
                    "select2:unselect" => "function() { searchParts.idDetail = false; }"
                ],
            ]);?>
            <label>Марка</label>
            <?php
            echo \kartik\select2\Select2::widget([
                'name' => 'marks',
                'value' => '',
                'pluginLoading' => false,
                'theme' => \kartik\select2\Select2::THEME_BOOTSTRAP,
                'data' => \yii\helpers\ArrayHelper::map(\app\models\CarMarksEN::find()->orderBy("Name")->all(), 'id', 'Name'),
                'options' => ['placeholder' => 'Марка'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
                'pluginEvents' => [
                    "select2:select" => "function(data) {  
                        searchParts.idMark = data.params.data.id; 
                        $('#w2').next().removeClass('select2-container--disabled');
                        searchParts.getModels();
                        $('#w4').next().removeClass('select2-container--disabled');
                        searchParts.getEngine();
                    }",
                    "select2:unselect" => "function() { 
                        searchParts.idMark = false;
                        $('#w2').next().addClass('select2-container--disabled');
                        $('#w4').next().addClass('select2-container--disabled');
                    }"
                ],
            ]);?>
            <label>Модель</label>
            <?php
            echo \kartik\select2\Select2::widget([
                'name' => 'models',
                'value' => '',
                'disabled' => true,
                'pluginLoading' => false,
                'theme' => \kartik\select2\Select2::THEME_BOOTSTRAP,
                // 'data' => \yii\helpers\ArrayHelper::map(\app\models\CarModelsEN::find()->orderBy("Name")->all(), 'id', 'Name'),
                'options' => ['placeholder' => 'Модель'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
                'pluginEvents' => [
                    "select2:select" => "function(data) {  
                        searchParts.idModel = data.params.data.id; 
                        $('#w3').next().removeClass('select2-container--disabled');
                        searchParts.getBodys();
                        searchParts.getEngine();
                    }",
                    "select2:unselect" => "function() { 
                        searchParts.idModel = false;
                        $('#w3').next().addClass('select2-container--disabled');
                    }"
                ],
            ]);?>
            <label>Кузов</label>
            <?php
            echo \kartik\select2\Select2::widget([
                'name' => 'models',
                'value' => '',
                'disabled' => true,
                'pluginLoading' => false,
                'theme' => \kartik\select2\Select2::THEME_BOOTSTRAP,
                // 'data' => \yii\helpers\ArrayHelper::map(\app\models\CarBodyModelsEN::find()->orderBy("Name")->all(), 'id', 'Name'),
                'options' => ['placeholder' => 'Кузов'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
                'pluginEvents' => [
                    "select2:select" => "function(data) {  
                        searchParts.idBody = data.params.data.id; 
                        searchParts.getEngine();
                    }",
                    "select2:unselect" => "function() { 
                        searchParts.idBody = false;
                    }"
                ],
            ]);?>
            <label>Двигатель</label>
            <?php
            echo \kartik\select2\Select2::widget([
                'name' => 'models',
                'value' => '',
                'disabled' => true,
                'pluginLoading' => false,
                'theme' => \kartik\select2\Select2::THEME_BOOTSTRAP,
                // 'data' => \yii\helpers\ArrayHelper::map(\app\models\CarEngineModelsEN::find()->orderBy("Name")->all(), 'id', 'Name'),
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
                    <td style="border-top: none"><label>Организация</label></td>
                    <td  style="border-top: none" id="firmOrganizationType"></td>
                </tr>
                <tr>
                    <td><label>Профиль деятельности</label></td>
                    <td id="firmActivityType"></td>
                </tr>
                <tr>
                    <td><label>Район</label></td>
                    <td id="firmDistrict"></td>
                </tr>
                <tr>
                    <td><label>Адрес</label></td>
                    <td id="firmAddress"></td>
                </tr>
                <tr>
                    <td><label>Телефон</label></td>
                    <td id="firmPhone"></td>
                </tr>
                <tr>
                    <td><label>Факс</label></td>
                    <td id="firmFax"></td>
                </tr>
                <tr>
                    <td><label>Email</label></td>
                    <td id="firmEmail"></td>
                </tr>
                <tr>
                    <td><label>Сайт</label></td>
                    <td id="firmURL"></td>
                </tr>
                <tr>
                    <td><label>Режим работы</label></td>
                    <td id="firmOperatingMode"></td>
                </tr>
                <tr>
                    <td><label>Примечание</label></td>
                    <td id="firmComment"></td>
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