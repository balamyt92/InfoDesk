"use strict";

/**
 * Объект хранящий рузультаты поиска по фирмам
 */
var result = {
    index : 0,
    row : {},
    openModelWindow : false,
    paginate : false,
    firms : false,
    parts : false,
    service : false,
    loading : false,
    toBack : false,
    toNext : true,
};

/**
 * Объект отвечающий за запрос к серверу о поиске фирмы и рендере результата
 */
var SearcherFirms = {
    submitForm : false,
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
                    $('#loader').hide();
                }
            });
        } else {
            resultData = "<h3>Нет таких фирм</h3>";
            renderLayout.html(resultData);
            $("#loader").hide();
        }
        $($(renderLayout.siblings()[0]).children()[0]).html("Найдено фирм - " + data.message.length);
        result.firms = true;
        result.parts = false;
        result.service = false;
        result.paginate = false;

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
};

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
    idPage      : 1,
    limitResult : 50,

    // функция вывода результата запроса
    render : function(data) {
        let resultData = `<table class='table table-hover'>
                            <thead>
                                <tr><th>ID Фирмы</th><th>Приоритет</th><th>Наименование</th><th>Марка</th><th>Модель</th>
                                <th>Кузов</th><th>Двигатель</th><th>Год</th><th>Цена</th>
                                <th>Примечание</th><th>Номер</th></tr>
                            </thead>
                          <tbody>`;
        let renderLayout = $("#search-result");
        
        if(data.message.length > 0){
            result.paginate = data.message.length >= searchParts.limitResult;
            data.message.forEach( function (item, i, arr){
                resultData +=  '<tr><td>' + item.ID_Firm + '</td><td>'+ item.Priority +'</td>' +
                                       '<td><a href="javascript:void(0);">'+
                                        item.DetailName + '</a></td><td>' + 
                                        item.MarkName + '</td><td>' + 
                                        item.ModelName + '</td><td>' + 
                                        item.BodyName + '</td><td>' + 
                                        item.EngineName + '</td><td>' + 
                                        item.CarYear + '</td><td>' + 
                                        item.Cost + '</td><td>' + 
                                        item.Comment + '</td><td>' + 
                                        item.Catalog_Number + '</td></tr>';
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
        $($(renderLayout.siblings()[0]).children()[0]).html("Найдено запчастей - " + data.message.length +
                                                            " (Старница " + searchParts.idPage + ")");
        result.firms = false;
        result.parts = true;
        result.service = false;
        result.loading = false;

        // если возвращаемся назат то нашинаем с конца списка
        if(result.toBack) {
            result.toBack = false;
            result.index = data.message.length;
            $(result.row[result.index]).addClass("hover");
        }
        if(result.toNext) {
            result.toNext = false;
            result.index = 1;
            $(result.row[result.index]).addClass("hover");
        }
    },

    search : function() {
        result.loading = true;
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
                engine_id : searchParts.idEngine,
                page      : searchParts.idPage,
                limit     : searchParts.limitResult,
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
                data.message.forEach(function (item, i) {
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
                data.message.forEach(function (item, i) {
                    list += `<option value="${item.id}">${item.Name}</option>`;
                    if(data.message.length == i+1) {
                        $('#w4').html(list);
                    }
                })
            }
        });
    },
};

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
    // подгрузка следующей страницы
    if(((event.keyCode == 40 && result.index >= searchParts.limitResult)
        || event.keyCode == 34)  &&
        result.paginate &&
        result.parts &&
        !result.loading )
    {
        searchParts.idPage += 1;
        result.toNext = true;
        searchParts.search();
    }
    // подгрузка предидущей страницы
    if(((event.keyCode == 38 && result.index < 2) || event.keyCode == 33)&&
        result.parts &&
        searchParts.idPage > 1 &&
        !result.loading )
    {
        searchParts.idPage -= 1;
        result.toBack = true;
        searchParts.search();
    }

    if(event.keyCode == 40 && event.ctrlKey && result.index < result.row.length - 1) {
        //40 низ
        result.index = result.index + 1;
        if(result.parts) {
            $($($(result.row[result.index]).children()[2]).children()[0]).focus();
        } else {
            $($($(result.row[result.index]).children()[0]).children()[0]).focus();
        }
        $(result.row[result.index]).addClass("hover");
        if(result.index > 1)
            $(result.row[result.index - 1]).removeClass("hover");

    } else if(event.keyCode == 38 && event.ctrlKey && result.index > 1) {
        //38 верх
        result.index = result.index - 1;
        if(result.parts) {
            $($($(result.row[result.index]).children()[2]).children()[0]).focus();
        } else {
            $($($(result.row[result.index]).children()[0]).children()[0]).focus();
        }
        $(result.row[result.index]).addClass("hover");
        $(result.row[result.index + 1]).removeClass("hover");

    }
    // отключил переход в поле поиска по ктрл + вверх ибо так удобнее, еще будем тестить
    // else if(event.keyCode == 38 && event.ctrlKey && result.index == 1 && !result.paginate) {
    //     $($('#search-line').focus()).select();
    //     window.scrollTo(0, 0);
    //     $(result.row[result.index]).removeClass("hover");
    //     result.index = 0;
    // }

    // в результатах по Esc скролим наверх если не открыто модальное окно
    if(event.keyCode == 27 && result.index > 0) {
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

    // для того что бы работол поиск по энетеру в запчастях
    if(event.keyCode != 13) {
        searchParts.submitForm = false;
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