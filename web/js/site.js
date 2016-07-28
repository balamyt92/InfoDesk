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
        $('#loader').show();
        $('#search-result').html('');
        let str = document.getElementById('search-line').value;
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

    // по Esc скролим наверх
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