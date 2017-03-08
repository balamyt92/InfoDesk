<?php
/* @var $this yii\web\View */

$this->title = 'Call-центр';

use app\assets\CallCenterAsset;

CallCenterAsset::register($this);

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
        <label><?= \app\models\TextBlock::find()->where(['name' => 'label_firms'])->one()->text ?></label>
        <div class="form-inline" style="margin-top: 35px;">
            <div class="form-group input-group" style="width: 100%;">
                <input id="search-line" type="text" class="form-control" title="firm-search">
                <span class="input-group-btn"><button id="search-firm-button" class="btn btn-default" type="button" value="default action"><i class="fa">Поиск</i></button></span>
            </div>
        </div>
        
        <div style="width: 100%; font-size: large; margin-top: 30px; padding: 3px">
            <?= \app\models\TextBlock::find()->where(['name' => 'inform_message'])->one()->text ?>
        </div>
    </div>


    <div class="col-md-4" onkeydown="searchParts.eventStatus(event);">
        <label><?= \app\models\TextBlock::find()->where(['name' => 'label_parts'])->one()->text ?></label>
        <div>
            <label>Деталь</label>
            <input type="text" id="detail-select" style="width: 100%"/>
            <label>Марка</label>
            <input type="text" id="mark-select" style="width: 100%"/>
            <label>Модель</label>
            <input type="text" id="model-select" style="width: 100%"/>
            <label>Кузов</label>
            <input type="text" id="body-select" style="width: 100%"/>
            <label>Двигатель</label>
            <input type="text" id="engine-select" style="width: 100%"/>
            <label>Номер</label>
            <input type="text" class="form-control" id="number">
        </div>
        <button id="search-parts-button" type="button" class="btn btn-default" style="margin-top: 5px; float: right;" onclick="searchParts.idPage = 1; searchParts.search();">Поиск</button>
    </div>

    <div class="col-md-5">
        <label><?= \app\models\TextBlock::find()->where(['name' => 'label_services'])->one()->text ?></label>
        <select class="form-control" name="service-list" id="service" size="37" onkeydown="serviceSearch.open(event);" ondblclick="serviceSearch.open(event);">
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
            <div class="modal-header" style="padding: 6px">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 id="partsName" class="modal-title"></h4>
            </div>
            <div class="modal-body row" style="padding: 4px">
                <div class="col-md-8">
                    <label>Телефоны:&nbsp;</label><span id="partsPhone"></span><br>
                    <label>Адрес:&nbsp</label><span id="partsAddress"></span><br>
                    <label>Район:&nbsp</label><span id="partsDistrict"></span><br>
                </div>
                <div class="col-md-4">
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
      <div class="modal-header" style="padding: 2px;">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 id="firmName" class="modal-title"></h4>
      </div>
      <div class="modal-body" style="padding: 2px;">
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
      <div class="modal-footer" style="display: none;">
        <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<?php \yii\jui\Draggable::end(); ?>

<div class="modal" id="modalResult" tabindex="-1" role="dialog" style="padding-left: 0">
    <div class="modal-dialog modal-dialog-fullscreen">
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
