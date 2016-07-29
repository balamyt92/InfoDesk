"use strict";

/**
 * Объект хранящий рузультаты поиска по фирмам
 */

var result = {
    index : 0,
    row : {},
    openModelWindow : false
};

/**
 * Объект отвечающий за запрос к серверу о поиске фирмы и рендере результата
 */
var SearcherFirms = {
    render : function(data) {
        let resultData = `<table class='table table-hover'>
                            <thead>
                                <tr><th style="width:25%;">Название<br>Телефон</th><th style="width:25%;">Адрес<br>Район</th>
                                <th>Профиль деятельсности</th><th style="width:20%;">Режим работы<br>Коментарий</th></tr>
                            </thead>
                          <tbody>`;
        let renderLayout = $("#search-result");
        
        if(data.message.length > 0){
            data.message.forEach( function (item, i, arr){
                resultData +=  '<tr><td><a href="javascript:void(0);" onclick=\'openFirm('+ 
                                        JSON.stringify(item) +');\'>'+ 
                                        item.Name + '</a><br><br>' + item.Phone + '</td><td>' + 
                                        item.Address + '<br><br>' + item.District + '</td><td>' + 
                                        item.ActivityType + '</td><td><pre>' + 
                                        item.OperatingMode + '</pre><br>' + item.Comment + '</td></tr>';
                if(i + 1 == arr.length) {
                    resultData += "</tbody></table>";
                    renderLayout.html(resultData);
                    result.index = 0;
                    result.row = renderLayout.children().children().children();
                    $("#loader").hide();
                }
            });
        } else {
            resultData = "<h3>Нет таких фирм</h3>";
            renderLayout.html(resultData);
            $("#loader").hide();
        }
        $($(renderLayout.siblings()[0]).children()[0]).html("Найдено фирм - " + data.message.length);
    },
    search : function() {
        let str = document.getElementById('search-line').value;
        if(str == '') {
            alert("Введите искомую строку");
            return false;
        }

        $('#loader').show();
        $('#search-result').html('');
        $.ajax({
            method: "GET",
            url: "index.php?r=site%2Fsearch",
            data: {str: str}
        }).done(function(data){
            SearcherFirms.render(data);
        });
    },
}

/**
 * Объект отвечающий за работу с фильтром запчастей
 */
var searchParts = {
    idDetail : false,
    idMark   : false,
    idModel  : false,
    idBody   : false,
    idEngine : false,
    idNumber : false,

    // функция вывода результата запроса
    render : function(data) {
        let resultData = `<table class='table table-hover'>
                            <thead>
                                <tr><th>Наименование</th><th>Марка</th><th>Модель</th>
                                <th>Кузов</th><th>Двигатель</th><th>Год</th><th>Цена</th>
                                <th>Примечание</th><th>Номер</th><th>Код</th></tr>
                            </thead>
                          <tbody>`;
        let renderLayout = $("#search-result");
        
        if(data.message.length > 0){
            data.message.forEach( function (item, i, arr){
                resultData +=  '<tr><td><a href="javascript:void(0);">'+ 
                                        item.DetailName + '</a></td><td>' + 
                                        item.MarkName + '</td><td>' + 
                                        item.ModelName + '</td><td>' + 
                                        item.BodyName + '</td><td>' + 
                                        item.EngineName + '</td><td>' + 
                                        item.CarYear + '</td><td>' + 
                                        item.Cost + '</td><td>' + 
                                        item.Comment + '</td><td>' + 
                                        item.Catalog_Number + '</td><td>' + 
                                        item.TechNumber + '</td></tr>';
                if(i + 1 == arr.length) {
                    resultData += "</tbody></table>";
                    renderLayout.html(resultData);
                    result.index = 0;
                    result.row = renderLayout.children().children().children();
                    $("#loader").hide();
                }
            });
        } else {
            resultData = "<h3>Нет запчастей</h3>";
            renderLayout.html(resultData);
            $("#loader").hide();
        }
        $($(renderLayout.siblings()[0]).children()[0]).html("Найдено запчастей - " + data.message.length);
    },

    search : function() {
        if(!searchParts.idDetail) {
            alert('Выберите деталь');
            return false;
        }
        if(!searchParts.idMark) {
            alert('Выберите марку');
            return false;
        }
        $('#loader').show();
        $('#search-result').html('');
        $.ajax({
            method: "GET",
            url: "index.php?r=site%2Fsearch-parts",
            data: {
                detail_id : searchParts.idDetail,
                mark_id   : searchParts.idMark,
                model_id  : searchParts.idModel,
                body_id   : searchParts.idBody,
                engine_id : searchParts.idEngine
            }
        }).done(function(data){
            searchParts.render(data);
        });
    },

    getModels : function() {
        $.ajax({
            method: "GET",
            url: "index.php?r=site%2Fget-models",
            data: {id: searchParts.idMark}
        }).done(function(data){
            // рисуем модели
            let list = '<option value="">Модель</option>';
            if(data.message.length > 0){
                data.message.forEach(function (item, i, arr) {
                    list += `<option value="${item.id}">${item.Name}</option>`;
                    if(data.message.length == i+1) {
                        $('#w2').html(list);
                    }
                })
            }
        });
    },

    getBodys : function() {
        $.ajax({
            method: "GET",
            url: "index.php?r=site%2Fget-bodys",
            data: {id: searchParts.idModel}
        }).done(function(data){
            // рисуем кузова
            let list = '<option value="">Кузов</option>';
            if(data.message.length > 0){
                data.message.forEach(function (item, i, arr) {
                    list += `<option value="${item.id}">${item.Name}</option>`;
                    if(data.message.length == i+1) {
                        $('#w3').html(list);
                    }
                })
            }
        });
    },

    getEngine : function() {
        $.ajax({
            method: "GET",
            url: "index.php?r=site%2Fget-engine",
            data: {
                mark_id: searchParts.idMark,
                model_id: searchParts.idModel,
                body_id: searchParts.idBody,
            }
        }).done(function(data){
            // рисуем двигателя
            let list = '<option value="">Двигатель</option>';
            if(data.message.length > 0){
                data.message.forEach(function (item, i, arr) {
                    list += `<option value="${item.id}">${item.Name}</option>`;
                    if(data.message.length == i+1) {
                        $('#w4').html(list);
                    }
                })
            }
        });
    },
}

/**
 * По энтеру в поле запускаем поиск фирм
 */
function runSearch(e) {
    if (e.keyCode == 13) {
        SearcherFirms.search();
        $($('#search-line').focus()).select();
        return false;
    }
}

/**
 * Функция обработки хоткеев навигации
 */
function keyNavigate(event){
    if(event.keyCode == 40 && event.ctrlKey && result.index < result.row.length - 1) {
        //40 низ
        result.index = result.index + 1;
        console.log(result.index);
        $($($(result.row[result.index]).children()[0]).children()[0]).focus();
        $(result.row[result.index]).addClass("hover");
        if(result.index > 1)
            $(result.row[result.index - 1]).removeClass("hover");

    } else if(event.keyCode == 38 && event.ctrlKey && result.index > 1) {
        //38 верх
        result.index = result.index - 1;
        $($($(result.row[result.index]).children()[0]).children()[0]).focus();
        $(result.row[result.index]).addClass("hover");
        $(result.row[result.index + 1]).removeClass("hover");

    } else if(event.keyCode == 38 && event.ctrlKey && result.index == 1) {
        $($('#search-line').focus()).select();
        window.scrollTo(0, 0);
        $(result.row[result.index]).removeClass("hover");
        result.index = 0;
    }

    // по Esc скролим наверх если не открыто модальное окно
    if(event.keyCode == 27) {
        // добавить проверку какой был запрос
        // для выделения соответсвующего элемента
        if(!result.openModelWindow) {
            $($('#search-line').focus()).select();
            window.scrollTo(0,0);
            $(result.row[result.index]).removeClass("hover");
            result.index = 0;
        } else {
            result.openModelWindow = false;
        }
    }
}

function openFirm(data) {
    $('#firmName').html(data.Name);
    $('#firmOrganizationType').html(data.OrganizationType);
    $('#firmActivityType').html(data.ActivityType);
    $('#firmDistrict').html(data.District);
    $('#firmAddress').html(data.Address);
    $('#firmPhone').html(data.Phone);
    $('#firmFax').html(data.Fax);
    $('#firmEmail').html(data.Email);
    $('#firmURL').html(data.URL);
    $('#firmOperatingMode').html(data.OperatingMode);
    $('#firmComment').html(data.Comment);
    $('#modalFirm').modal();
    $($($(result.row[result.index]).children()[0]).children()[0]).focus();
    result.openModelWindow = true;
}

$('#modalFirm').on('hidden.bs.modal', function () {
    $($($(result.row[result.index]).children()[0]).children()[0]).focus();
});