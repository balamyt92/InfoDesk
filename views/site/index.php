<?php

/* @var $this yii\web\View */

$this->title = 'InfoDesk';


list(, $url) = Yii::$app->assetManager->publish('@bower/jqgrid');
$this->registerJsFile($url.'/js/i18n/grid.locale-ru.js', ['depends' => [
    'yii\web\YiiAsset',
    'yii\bootstrap\BootstrapAsset', ],
]);
$this->registerJsFile($url.'/js/jquery.jqGrid.min.js', ['depends' => [
    'yii\web\YiiAsset',
    'yii\bootstrap\BootstrapAsset', ],
]);
$this->registerCssFile($url.'/css/ui.jqgrid.css');
$this->registerCssFile($url.'/css/ui.jqgrid-bootstrap.css');
$this->registerCssFile($url.'/css/ui.jqgrid-bootstrap-ui.css');
?>

<div class="row">
    <div class="col-md-3">
        <h3>Поиск фирм</h3>
        <div class="form-inline" style="margin-top: 35px;">
            <div class="form-group input-group">
                <input id="search-line" type="text" class="form-control" title="firm-search">
                <span class="input-group-btn"><button id="search-firm-button" class="btn btn-default" type="button" value="default action"><i class="fa">Поиск</i></button></span>
            </div>
        </div>
    </div>


    <div class="col-md-4" onmousedown="searchParts.submitForm = false;" onclick="searchParts.submitForm = false;">
        <h3>Поиск запчастей</h3>
        <div>
            <label>Деталь</label>
            <?php
            echo \kartik\select2\Select2::widget([
                'name'          => 'details',
                'value'         => '',
                'readonly'      => true,
                'pluginLoading' => false,
                'theme'         => \kartik\select2\Select2::THEME_BOOTSTRAP,
                'data'          => \yii\helpers\ArrayHelper::map(\app\models\CarENDetailNames::find()->orderBy('Name')->all(), 'id', 'Name'),
                'options'       => ['placeholder' => ''],
                'pluginOptions' => [
                    'allowClear' => true,
                ],
                'pluginEvents' => [
                    'select2:select' => 'function(data) {
                        searchParts.idDetail = data.params.data.id;
                        searchParts.submitForm = true;
                        searchParts.currentSelect = this;
                    }',
                    'select2:unselect' => 'function() {
                        searchParts.idDetail = false;
                        searchParts.submitForm = false;
                    }',
                    'select2:opening' => 'function() {
                        if(searchParts.submitForm) {
                            searchParts.idPage = 1;
                            searchParts.search();
                            searchParts.submitForm = false;
                            return false;
                        }
                    }',
                ],
            ]); ?>
            <label>Марка</label>
            <?php
            echo \kartik\select2\Select2::widget([
                'name'          => 'marks',
                'value'         => '',
                'pluginLoading' => false,
                'theme'         => \kartik\select2\Select2::THEME_BOOTSTRAP,
                'data'          => \yii\helpers\ArrayHelper::map(\app\models\CarMarksEN::find()->orderBy('Name')->all(), 'id', 'Name'),
                'options'       => ['placeholder' => ''],
                'pluginOptions' => [
                    'allowClear' => true,
                ],
                'pluginEvents' => [
                    'select2:select' => "function(data) {
                        searchParts.idMark = data.params.data.id;
                        $('#w2').select2(\"val\", \"\");
                        $('#w3').select2(\"val\", \"\");
                        $('#w4').select2(\"val\", \"\");

                        $('#w2').prop(\"disabled\", false);
                        $('#w3').prop(\"disabled\", true);
                        $('#w4').prop(\"disabled\", false);

                        searchParts.getModels();
                        searchParts.getEngine();
                        searchParts.idModel = false;
                        searchParts.idBody = false;
                        searchParts.idEngine = false;

                        searchParts.submitForm = true;
                        searchParts.currentSelect = this;
                    }",
                    'select2:unselect' => "function() {
                        searchParts.idMark = false;
                        searchParts.idModel = false;
                        searchParts.idBody = false;
                        searchParts.idEngine = false;

                        $('#w2').prop(\"disabled\", true);
                        $('#w3').prop(\"disabled\", true);
                        $('#w4').prop(\"disabled\", true);

                        $('#w2').select2(\"val\", \"\");
                        $('#w3').select2(\"val\", \"\");
                        $('#w4').select2(\"val\", \"\");
                        searchParts.submitForm = false;
                    }",
                    'select2:opening' => 'function() {
                        if(searchParts.submitForm) {
                            searchParts.idPage = 1;
                            searchParts.search();
                            searchParts.submitForm = false;
                            return false;
                        }
                    }',
                ],
            ]); ?>
            <label>Модель</label>
            <?php
            echo \kartik\select2\Select2::widget([
                'name'          => 'models',
                'value'         => '',
                'disabled'      => true,
                'pluginLoading' => false,
                'theme'         => \kartik\select2\Select2::THEME_BOOTSTRAP,
                // 'data' => \yii\helpers\ArrayHelper::map(\app\models\CarModelsEN::find()->orderBy("Name")->all(), 'id', 'Name'),
                'options'       => ['placeholder' => ''],
                'pluginOptions' => [
                    'allowClear' => true,
                ],
                'pluginEvents' => [
                    'select2:select' => "function(data) {
                        searchParts.idModel = data.params.data.id;

                        $('#w3').select2(\"val\", \"\");
                        $('#w4').select2(\"val\", \"\");

                        $('#w3').prop(\"disabled\", false);
                        searchParts.getBodys();
                        searchParts.getEngine();
                        searchParts.submitForm = true;
                        searchParts.idBody = false;
                        searchParts.idEngine = false;
                        searchParts.currentSelect = this;
                    }",
                    'select2:unselect' => "function() {
                        $('#w3').prop(\"disabled\", true);

                        $('#w3').select2(\"val\", \"\");
                        $('#w4').select2(\"val\", \"\");

                        searchParts.idModel = false;
                        searchParts.idBody = false;
                        searchParts.idEngine = false;
                        searchParts.getEngine();
                        searchParts.submitForm = false;
                    }",
                    'select2:opening' => 'function() {
                        if(searchParts.submitForm) {
                            searchParts.idPage = 1;
                            searchParts.search();
                            searchParts.submitForm = false;
                            return false;
                        }
                    }',
                ],
            ]); ?>
            <label>Кузов</label>
            <?php
            echo \kartik\select2\Select2::widget([
                'name'          => 'models',
                'value'         => '',
                'disabled'      => true,
                'pluginLoading' => false,
                'theme'         => \kartik\select2\Select2::THEME_BOOTSTRAP,
                // 'data' => \yii\helpers\ArrayHelper::map(\app\models\CarBodyModelsEN::find()->orderBy("Name")->all(), 'id', 'Name'),
                'options'       => ['placeholder' => ''],
                'pluginOptions' => [
                    'allowClear' => true,
                ],
                'pluginEvents' => [
                    'select2:select' => "function(data) {
                        searchParts.idBody = data.params.data.id;
                        $('#w4').select2(\"val\", \"\");
                        searchParts.getEngine();
                        searchParts.submitForm = true;
                        searchParts.idEngine = false;
                        searchParts.currentSelect = this;
                    }",
                    'select2:unselect' => "function() {
                        searchParts.idBody = false;
                        searchParts.idEngine = false;
                        $('#w4').select2(\"val\", \"\");
                        searchParts.getEngine();
                        searchParts.submitForm = false;
                    }",
                    'select2:opening' => 'function() {
                        if(searchParts.submitForm) {
                            searchParts.idPage = 1;
                            searchParts.search();
                            searchParts.submitForm = false;
                            return false;
                        }
                    }',
                ],
            ]); ?>
            <label>Двигатель</label>
            <?php
            echo \kartik\select2\Select2::widget([
                'name'          => 'models',
                'value'         => '',
                'disabled'      => true,
                'pluginLoading' => false,
                'theme'         => \kartik\select2\Select2::THEME_BOOTSTRAP,
                'options'       => ['placeholder' => ''],
                'pluginOptions' => [
                    'allowClear' => true,
                ],
                'pluginEvents' => [
                    'select2:select' => 'function(data) {
                        searchParts.submitForm = true;
                        searchParts.idEngine = data.params.data.id;
                        searchParts.currentSelect = this;
                    }',
                    'select2:unselect' => 'function() {
                        searchParts.submitForm = false;
                        searchParts.idEngine = false;
                    }',
                    'select2:opening' => 'function() {
                        if(searchParts.submitForm) {
                            searchParts.idPage = 1;
                            searchParts.search();
                            searchParts.submitForm = false;
                            return false;
                        }
                    }',
                ],
            ]); ?>
            <label>Номер</label>
            <input type="text" class="form-control" id="number">
        </div>
        <button type="button" class="btn btn-default" style="margin-top: 5px; float: right;" onclick="searchParts.idPage = 1; searchParts.search();">Поиск</button>
    </div>

    <div class="col-md-5">
        <h3>Поиск сервисов</h3>
        <select class="form-control" name="service-list" id="service" size="20" onkeydown="serviceSearch.open(event);">
            <?php
                $services = \app\models\Services::find()->where(['IS', 'ID_Parent', null])->orderBy(['Name' => SORT_ASC])->all();
                foreach ($services as $value) {
                    echo '<option style="border-bottom: solid 1px;" value="', $value['id'], '">', $value['Name'], '</option>';
                }
            ?>
        </select>
    </div>
</div>


<?php \yii\jui\Draggable::begin([]); ?>
<div class="modal" id="modalParts" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 id="partsName" class="modal-title"></h4>
            </div>
            <div class="modal-body row">
                <div class="col-md-8 nopadding">
                    <label>Телефоны:&nbsp;</label><span id="partsPhone"></span><br>
                    <label>Адрес:&nbsp</label><span id="partsAddress"></span><br>
                    <label>Район:&nbsp</label><span id="partsDistrict"></span><br>
                </div>
                <div class="col-md-4 nopadding">
                    <label>Режим работы:</label><pre id="partsOperatingMode"></pre>
                </div>
            </div>
            <div class="modal-footer" style="display: none">
                <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<?php \yii\jui\Draggable::end(); ?>

<?php \yii\jui\Draggable::begin([]); ?>
<div class="modal" id="modalFirm" tabindex="-1" role="dialog" style="">
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
        <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<?php \yii\jui\Draggable::end(); ?>

<div class="modal" id="modalResult" tabindex="-1" role="dialog" style="padding-left: 0">
    <div class="modal-diДетальalog modal-dialog-fullscreen">
        <div class="modal-content modal-content-fullscreen">
            <div class="modal-header" style="display: none">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Результаты</h4>
            </div>
            <div class="modal-body">
                <table id="firm-result-search"></table>
                <div id="firm-pager"></div>
                <table id="part-result-search"></table>
                <div id="part-pager"></div>
                <table id="service-result-search"></table>
                <div id="service-pager"></div>
            </div>
            <div class="modal-footer" style="display: none">
                <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
