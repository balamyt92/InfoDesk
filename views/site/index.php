<?php
/* @var $this yii\web\View */

$this->title = 'Call-центр';

use app\assets\CallCenterAsset;
use app\models\Services;
use app\models\TextBlock;
use yii\helpers\Url;
use yii\jui\Draggable;

CallCenterAsset::register($this);
?>

<div class="row">
    <div class="col-md-3">
        <label><?= TextBlock::find()->where(['name' => 'label_firms'])->one()->text ?></label>
        <div class="form-inline search_firm_wrap">
            <div class="form-group input-group">
                <input id="search-line" type="text" class="form-control" title="firm-search">
                <span class="input-group-btn">
                    <button id="search-firm-button"
                            class="btn btn-default"
                            type="button"
                            value="default action">Поиск</button></span>
            </div>
        </div>

        <div class="inform_message">
            <?= TextBlock::find()->where(['name' => 'inform_message'])->one()->text ?>
        </div>
    </div>


    <div class="col-md-4">
        <form action="<?= Url::toRoute('site/parts-search') ?>">
            <label><?= TextBlock::find()->where(['name' => 'label_parts'])->one()->text ?></label>
            <div class="parts-form">
                <label for="detail-select">Деталь</label>
                <input type="text" id="detail-select" class="form-control"/>
                <label for="mark-select">Марка</label>
                <input type="text" id="mark-select" class="form-control"/>
                <label for="model-select">Модель</label>
                <input type="text" id="model-select" class="form-control"/>
                <label for="body-select">Кузов</label>
                <input type="text" id="body-select" class="form-control"/>
                <label for="engine-select">Двигатель</label>
                <input type="text" id="engine-select" class="form-control"/>
                <label for="number">Номер / Комментарий</label>
                <input type="text" class="form-control" id="number">
            </div>
            <button id="search-parts-button"
                    type="submit"
                    class="btn btn-default parts-submit">Поиск
            </button>
        </form>
    </div>

    <div class="col-md-5">
        <label for="service"><?= TextBlock::find()->where(['name' => 'label_services'])->one()->text ?></label>
        <select class="form-control"
                name="service-list"
                id="service"
                size="37">
            <?php
            $services = Services::find()
                ->where(['IS', 'ID_Parent', null])
                ->orderBy(['Name' => SORT_ASC])
                ->all();
            ?>
            <?php foreach ($services as $value): ?>
                <option value="<?= $value['id'] ?>"><?= $value['Name'] ?></option>
            <?php endforeach; ?>
        </select>
    </div>
</div>

<?php Draggable::begin([]); ?>
<div class="modal" id="modalFirm" tabindex="-1" role="dialog" style="">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="padding: 2px;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 id="firmName" class="modal-title"></h4>
            </div>
            <div class="modal-body" style="padding: 2px;">
                <table class="table table-condensed">
                    <tbody>
                    <tr>
                        <td style="border-top: none">
                            <label>Организация</label>
                        </td>
                        <td style="border-top: none" id="firmOrganizationType"></td>
                    </tr>
                    <tr>
                        <td>
                            <label>Профиль деятельности</label>
                        </td>
                        <td id="firmActivityType"></td>
                    </tr>
                    <tr>
                        <td>
                            <label>Район</label>
                        </td>
                        <td id="firmDistrict"></td>
                    </tr>
                    <tr>
                        <td>
                            <label>Адрес</label>
                        </td>
                        <td id="firmAddress"></td>
                    </tr>
                    <tr>
                        <td>
                            <label>Телефон</label>
                        </td>
                        <td id="firmPhone"></td>
                    </tr>
                    <tr>
                        <td>
                            <label>Факс</label>
                        </td>
                        <td id="firmFax"></td>
                    </tr>
                    <tr>
                        <td>
                            <label>Email</label>
                        </td>
                        <td id="firmEmail"></td>
                    </tr>
                    <tr>
                        <td>
                            <label>Сайт</label>
                        </td>
                        <td id="firmURL"></td>
                    </tr>
                    <tr>
                        <td>
                            <label>Режим работы</label>
                        </td>
                        <td id="firmOperatingMode"></td>
                    </tr>
                    <tr>
                        <td>
                            <label>Примечание</label>
                        </td>
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
<?php Draggable::end(); ?>

<div class="modal" id="firm-search-result" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-fullscreen">
        <div class="modal-content modal-content-fullscreen">
            <div class="modal-body">
                <table id="firm-result"></table>
                <div id="firm-pager"></div>
            </div>
        </div>
    </div>
</div>
<div class="modal" id="parts-search-result" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-fullscreen">
        <div class="modal-content modal-content-fullscreen">
            <div class="modal-body">
                <table id="parts-result"></table>
                <div id="parts-pager"></div>
            </div>
        </div>
    </div>
</div>
<div class="modal" id="service-search-result" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-fullscreen">
        <div class="modal-content modal-content-fullscreen">
            <div class="modal-body">
                <table id="service-result"></table>
                <div id="service-pager"></div>
            </div>
        </div>
    </div>
</div>
