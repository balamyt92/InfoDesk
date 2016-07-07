<?php

/* @var $this yii\web\View */

$this->title = 'InfoDesk'; ?>

<div class="col-md-12">
    <!-- Nav tabs -->
    <ul class="nav nav-tabs">
        <li class="active"><a href="#search" data-toggle="tab">Поиск</a></li>
        <li><a href="#parts" data-toggle="tab">Запчасти</a></li>
        <li><a href="#service" data-toggle="tab">Сервисы</a></li>
    </ul>

    <!-- Tab panes -->
    <div class="tab-content">
        <div class="tab-pane active" id="search">
           <div style="padding-bottom: 20px">
                <h3>Поиск фирм по базе</h3>
                <div class="form-inline">
                    <div class="form-group input-group col-md-4">
                        <input id="search-line" type="text" class="form-control" onkeypress="return runSearch(event)">
                        <span class="input-group-btn"><button class="btn btn-default" type="button" onclick="searchFirm();" value="default action"><i class="fa">Поиск</i></button></span>
                    </div>
                </div>
            </div>

            <div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title" data-select-like-a-boss="1">Рузультаты поиска</h3>
                    </div>
                    <div id="search-firm-result" class="panel-body">
                        рузельтаты
                    </div>
                    <div id="loader" class="loader panel-body"></div>
                </div>
            </div> 
        </div>
        <div class="tab-pane" id="parts">Поиск запчастей с помощью фильтра</div>
        <div class="tab-pane" id="service">Поиск сервисов</div>
    </div>

    <div class="modal fade" id="modalFirm" tabindex="-1" role="dialog">
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
</div>