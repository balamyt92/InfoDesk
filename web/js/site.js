// var id = 0;

// function importStatus() {
//     $.ajax({
//         method: "GET",
//         url: "index.php?r=import%2Fimport-status",
//         data: {last_id: id}
//     })
//         .done(function (data) {
//             alert("Data Saved: " + data);
//             id++;
//         });
// }


/**
 * Функция осуществляет поиск фирмы в базе по подстроке из инпута
 */
function searchFirm() {
    $(document.getElementById('loader')).show();
    $(document.getElementById('search-firm-result')).html('');
    var str = document.getElementById('search-line').value;
    console.log(str);
    $.ajax({
        method: "GET",
        url: "index.php?r=site%2Fsearch",
        data: {str: str}
    })
        .done(function (data) {
            var render = document.getElementById('search-firm-result');
            $(document.getElementsByClassName('panel-title')).html('Найдено фирм - ' + data.message.length);
            if(data.message.length > 0) {
                $(render).html('<table class="table table-striped"><thead><tr><th>Название</th><th>Адрес</th><th>Телефон</th><th>Район</th></tr></thead><tbody></tbody></table>');
                data.message.forEach(
                    function (item, i, arr) {
                        $(render).children().children('tbody').append('<tr><td><a href="#" onclick="openFirm('+ JSON.stringify(item) +');">'+
                                                                                item.Name + '</a></td><td>' + 
                                                                                item.Address + '</td><td>' + 
                                                                                item.Phone + '</td><td>' + 
                                                                                item.District + '</td></tr>');
                        if(i + 1 == arr.length) {
                            $(document.getElementById('loader')).hide();

                            // Делаем так что бы при клике на строку открывалась ссылка
                            $('tr').click( function() {
                                window.location = $(this).find('a').attr('href');
                            }).hover( function() {
                                $(this).toggleClass('hover');
                            });
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

function navTab(event){
    var tabs = $(document.getElementsByClassName('nav-tabs')[0]).children();
    if(event.keyCode == 39 && event.ctrlKey) {
        if(tabs[0].className == 'active') {
            $(tabs[1]).children().click();
        } else if (tabs[1].className == 'active') {
            $(tabs[2]).children().click();
        } else if (tabs[2].className == 'active') {
            $(tabs[0]).children().click();
        }
    }
    if(event.keyCode == 37 && event.ctrlKey) {
        if(tabs[0].className == 'active') {
            $(tabs[2]).children().click();
        } else if (tabs[1].className == 'active') {
            $(tabs[0]).children().click();
        } else if (tabs[2].className == 'active') {
            $(tabs[1]).children().click();
        }
    }
    
}

// $('#firmModal').on('show.bs.modal', function (event) {
//   var button = $(event.relatedTarget);
//   var recipient = button.data('whatever') ;
//   var modal = $(this);
//   modal.find('.modal-title').text('Фирма ' + recipient)
//   modal.find('.modal-body input').val(recipient)
// })

// function openFirm(string) {
//     data = JSON.parse(string);
//     console.log();
// }