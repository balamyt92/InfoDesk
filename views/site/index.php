<?php

/* @var $this yii\web\View */

$this->title = 'InfoDesk'; ?>

<!-- Nav tabs -->
<ul class="nav nav-tabs">
    <li class="active"><a href="#home" data-toggle="tab">Поиск</a></li>
    <li><a href="#profile" data-toggle="tab">Запчасти</a></li>
    <li><a href="#messages" data-toggle="tab">Сервисы</a></li>
</ul>

<!-- Tab panes -->
<div class="tab-content">
    <div class="tab-pane active" id="home">
        <h3>Поиск фирм по базе</h3>

        <div class="col-lg-12" style="padding-bottom: 20px">
            <form class="form-inline">
                <div class="form-group input-group col-md-4">
                    <input id="search-line" type="text" class="form-control">
                    <span class="input-group-btn"><button class="btn btn-default" type="button" onclick="searchFirm();"><i class="fa">Поиск</i></button></span>
                </div>
            </form>
        </div>

        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title" data-select-like-a-boss="1"><i class="fa fa-bar-chart-o fa-fw"></i> Рузультаты поиска</h3>
                </div>
                <div id="search-firm-result" class="panel-body">
                    рузельтаты
                </div>
            </div>
        </div>

    </div>
    <div class="tab-pane" id="profile">Поиск запчастей с помощью фильтра</div>
    <div class="tab-pane" id="messages">Поиск сервисов</div>
</div>