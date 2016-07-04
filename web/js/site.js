/**
 * Функция осуществляет поиск фирмы в базе по подстроке из инпута
 */
var result = {
    index : 0,
    row : {}
};

function searchFirm() {
    $('#loader').show();
    $('#search-firm-result').html('');
    var str = document.getElementById('search-line').value;
    $.ajax({
        method: "GET",
        url: "index.php?r=site%2Fsearch",
        data: {str: str}
    })
        .done(function (data) {
            var render = document.getElementById('search-firm-result');
            $('.panel-title').html('Найдено фирм - ' + data.message.length);
            if(data.message.length > 0) {
                $(render).html('<table class="table table-hover"><thead><tr><th>Название</th><th>Адрес</th><th>Телефон</th><th>Район</th></tr></thead><tbody></tbody></table>');
                data.message.forEach(
                    function (item, i, arr) {
                        $(render)
                        .children()
                        .children('tbody')
                        .append('<tr><td><a href="javascript:void(0);" onclick=\'openFirm('+ JSON.stringify(item) +');\'>'+
                                                                        item.Name + '</a></td><td>' + 
                                                                        item.Address + '</td><td>' + 
                                                                        item.Phone + '</td><td>' + 
                                                                        item.District + '</td></tr>');
                        if(i + 1 == arr.length) {
                            $('#loader').hide();

                            result.index = 0;
                            result.row = $(render).children().children().children();
                            // Делаем так что бы при клике на строку открывалась ссылка
                            // TODO: придумать как при клике на заголовок таблицы ничего не происходило
                            // $('tr').click( function() {
                            //     window.location = $(this).find('a').attr('href');
                            // }).hover( function() {
                            //     $(this).toggleClass('hover');
                            // });

                        }
                    }
                );
            } else {
                $(render).html('<h3>Нет таких фирм</h3>');
                $(document.getElementById('loader')).hide();
            }

        });
}

/**
 * По энтеру в поле запускаем поиск фирм
 */
function runSearch(e) {
    if (e.keyCode == 13) {
        searchFirm();
        return false;
    }
}

/**
 * Функция обработки хоткеев навигации
 */
function keyNavigate(event){
    var tabs = $(document.getElementsByClassName('nav-tabs')[0]).children();
    // перемещение по табу в право
    if(event.keyCode == 39 && event.ctrlKey) {
        if(tabs[0].className == 'active') {
            $(tabs[1]).children().click();
        } else if (tabs[1].className == 'active') {
            $(tabs[2]).children().click();
        } else if (tabs[2].className == 'active') {
            $(tabs[0]).children().click();
        }
    }
    // перемещение по табу в лево
    if(event.keyCode == 37 && event.ctrlKey) {
        if(tabs[0].className == 'active') {
            $(tabs[2]).children().click();
        } else if (tabs[1].className == 'active') {
            $(tabs[0]).children().click();
        } else if (tabs[2].className == 'active') {
            $(tabs[1]).children().click();
        }
    }
    
    if(event.keyCode == 40 && event.ctrlKey && result.index < result.row.length) {
        //40 низ
        result.index = result.index + 1;
        $($($(result.row[result.index]).children()[0]).children()[0]).focus();

    } else if(event.keyCode == 38 && event.ctrlKey && result.index >= 1) {
        //38 верх
        result.index = result.index - 1;
        $($($(result.row[result.index]).children()[0]).children()[0]).focus();

    } else if(result.index <= 1) {
        $($('#search-line').focus()).select();
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
    $('#modalFirm').modal();
}