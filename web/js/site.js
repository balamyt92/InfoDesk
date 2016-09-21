"use strict";
/**
 * Объект хранящий в результатах какого поиска мы находимся
 */
var result = {
    firms : false,
    parts : false,
    service : false,
};

var KEY = {
    TAB: 9,
    ENTER: 13,
    ESC: 27,
    SPACE: 32,
    LEFT: 37,
    UP: 38,
    RIGHT: 39,
    DOWN: 40,
    SHIFT: 16,
    CTRL: 17,
    ALT: 18,
    PAGE_UP: 33,
    PAGE_DOWN: 34,
    HOME: 36,
    END: 35,
    BACKSPACE: 8,
    DELETE: 46,
};

/**
 * Объект отвечающий за запрос к серверу о поиске фирмы и рендере результата
 */
var searcherFirms = {
    input : $('#search-line'),
    gridCreate : false,
    pagerToNext: false,
    pagerToBack: false,
    pagerLastRow : false,
    modalWindow: $('#modalResult'),
    grid: $("#firm-result-search"),
    render : function(data) {
        let grid = this.grid;

        grid.jqGrid('setGridParam', {data: data});
        // hide the show message
        grid[0].grid.endReq();
        // refresh the grid
        grid.trigger('reloadGrid');
        grid.setSelection(1, true);
        grid.focus();

        result.firms = true;
        result.parts = false;
        result.service = false;
    },
    search : function() {
        this.modalWindow.modal({backdrop: false});
        let str = document.getElementById('search-line').value.toString().trim();

        if(str == '') {
            alert("Введите искомую строку");
            return false;
        }

        let grid = this.grid;
        $('#gbox_firm-result-search').show();
        if(!this.gridCreate) {
            grid.jqGrid({
                colModel: [
                    {label: 'Row', name: 'Row', key: true, width: -1, hidden: true},
                    {label: 'ID', name: 'id', width: -1, hidden: true},
                    {label: 'Фирма', name: 'Name', width: 250},
                    {label: 'Телефон', name: 'Phone', width: 250},
                    {label: 'Адерс', name: 'Address', width: 250},
                    {label: 'Район', name: 'District', width: 250},
                    {label: 'Примечание', name: 'Comment', width: 250},
                ],
                viewrecords: true, // show the current page, data rang and total records on the toolbar
                autowidth: true,
                height: $('#modalResult').height() - 100,
                rowNum: 100,
                pager: "#firm-pager",
                datatype: 'local',
                styleUI: 'Bootstrap',
                responsive: true,
                loadonce: true,
                cmTemplate: {sortable: false,},
                ondblClickRow: function(id) {
                    openFirm(grid.getCell(id, 'id'));
                },
            });

            grid.jqGrid('bindKeys', {
                "onEnter": function (id) {
                    openFirm(grid.getCell(id, 'id'));
                }
            });

            grid.bind('keydown', function (e) {
                let rowInPage = grid.jqGrid('getGridParam','rowNum');
                let totalPages = grid.jqGrid('getGridParam','lastpage');
                let currentPage = grid.jqGrid('getGridParam','page');
                let currentRow = grid.jqGrid ('getGridParam', 'selrow');
                let realRowInPage = grid.jqGrid ('getGridParam', 'records') - (rowInPage * (totalPages - 1));

                // если вниз и последняя строка
                if (e.keyCode == KEY.DOWN && totalPages != currentPage && searcherFirms.pagerToNext) {
                    grid.jqGrid('setGridParam', {"page": currentPage + 1}).trigger("reloadGrid");
                    grid.jqGrid('setSelection', 1, false);
                    grid.focus();
                }
                // если вниз и последняя строка последней страницы
                if(e.keyCode == KEY.DOWN && totalPages == currentPage && searcherFirms.pagerLastRow){
                    grid.jqGrid('setGridParam', {"page": 1}).trigger("reloadGrid");
                    grid.jqGrid('setSelection', 1, false);
                    grid.focus();
                }
                // если вверх и первая строка
                if (e.keyCode == KEY.UP && currentPage > 1 && searcherFirms.pagerToBack) {
                    grid.jqGrid('setGridParam', {"page": currentPage - 1}).trigger("reloadGrid");
                    grid.jqGrid('setSelection', rowInPage, false);
                    grid.focus();
                }

                if (e.keyCode == KEY.PAGE_DOWN || e.keyCode == KEY.PAGE_UP) {
                    setTimeout(function () {
                        document.elementFromPoint(100, grid.closest(".ui-jqgrid-bdiv").height() / 2).click();
                    }, 500);
                }

                currentRow == realRowInPage
                ? searcherFirms.pagerLastRow = true : searcherFirms.pagerLastRow = false;

                currentRow == rowInPage
                ? searcherFirms.pagerToNext = true : searcherFirms.pagerToNext = false;

                currentRow == 1
                ? searcherFirms.pagerToBack = true : searcherFirms.pagerToBack = false;
            });

            this.gridCreate = true;
        }

        grid.jqGrid("clearGridData");
        grid[0].grid.beginReq();

        $.ajax({
            method: "GET",
            url: "index.php?r=site/search",
            data: {str: str}
        }).done(function(data){
            searcherFirms.render(data.message);
        });
    },


    /**
     * По энтеру в поле запускаем поиск фирм
     */
    runSearch: function(e) {
        if (e.keyCode == KEY.ENTER) {
            this.search();
            $($('#search-line').focus()).select();
            return false;
        }
    }
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
    lastQuery : {
        idDetail : false,
        idMark   : false,
        idModel  : false,
        idBody   : false,
        idEngine : false,
        idNumber : false,
    },


    currentSelect : $('#detail-select'),
    pagerToNext : false,
    pagerToBack : false,
    pagerLastRow : false,
    gridCreate: false,
    modalWindow: $('#modalResult'),
    grid: $("#part-result-search"),

    highlightRow : function () {
        let rowInPage = this.grid.jqGrid('getGridParam','rowNum');
        let totalPages = this.grid.jqGrid('getGridParam','lastpage');
        let currentPage = this.grid.jqGrid('getGridParam','page');
        let realRowInLasPage = this.grid.jqGrid ('getGridParam', 'records') - (rowInPage * (totalPages - 1));
        let firmID = this.grid.getCell(1, 'ID_Firm');
        let color = false;
        for(let i = 2; currentPage < totalPages ? i < rowInPage : i < realRowInLasPage; i++) {
            let newId = this.grid.getCell(i, 'ID_Firm');
            if (firmID != newId) {
                firmID = newId;
                color = !color;
            }
            if(color) {
                this.grid.setCell(i,1,'',{ background:'#d8cfcf'});
                this.grid.setCell(i,2,'',{ background:'#d8cfcf'});
                this.grid.setCell(i,3,'',{ background:'#d8cfcf'});
                this.grid.setCell(i,4,'',{ background:'#d8cfcf'});
                this.grid.setCell(i,5,'',{ background:'#d8cfcf'});
                this.grid.setCell(i,6,'',{ background:'#d8cfcf'});
                this.grid.setCell(i,7,'',{ background:'#d8cfcf'});
                this.grid.setCell(i,8,'',{ background:'#d8cfcf'});
                this.grid.setCell(i,9,'',{ background:'#d8cfcf'});
                this.grid.setCell(i,10,'',{ background:'#d8cfcf'});
                this.grid.setCell(i,11,'',{ background:'#d8cfcf'});
            }
        }
    },

    // функция вывода результата запроса
    render : function(data) {
        let grid = this.grid;

        grid.jqGrid('setGridParam', {data: data});
        // hide the show message
        grid[0].grid.endReq();
        // refresh the grid
        grid.trigger('reloadGrid');
        grid.setSelection(1, true);
        grid.focus();

        this.highlightRow();

        result.firms = false;
        result.parts = true;
        result.service = false;
    },

    eventStatus : function(e) {
        if(e.keyCode == KEY.ENTER) {
            this.search();
        }
    },
    search : function() {
        if(!this.idBody
            && !this.idDetail
            && !this.idEngine
            && !this.idMark
            && !this.idModel) {
            console.log('Выберите хотябы один из пунктов фильтра');
            return false;
        }

        $('#gbox_part-result-search').show();
        this.modalWindow.modal({backdrop: false});

        // "кешируем" запрос
        if(this.lastQuery.idBody    == this.idBody      &&
           this.lastQuery.idDetail  == this.idDetail    &&
           this.lastQuery.idModel   == this.idModel     &&
           this.lastQuery.idMark    == this.idMark      &&
           this.lastQuery.idEngine  == this.idEngine    &&
           this.lastQuery.idNumber  == this.idNumber    && this.gridCreate) {
            this.grid.focus();
            return false;
        }
        this.lastQuery.idBody    = this.idBody;
        this.lastQuery.idDetail  = this.idDetail;
        this.lastQuery.idModel   = this.idModel;
        this.lastQuery.idMark    = this.idMark;
        this.lastQuery.idEngine  = this.idEngine;
        this.lastQuery.idNumber  = this.idNumber;

        let grid = this.grid;

        if(!this.gridCreate) {
            grid.jqGrid({
                colModel: [
                    {label: 'Row', name: 'Row', key: true, width: -1, hidden: true},
                    {label: 'Приоритет', name: 'Priority', width: 5},
                    {label: 'ID', name: 'ID_Firm', width: 10},
                    {label: 'Марка', name: 'MarkName', width: 30},
                    {label: 'Модель', name: 'ModelName', width: 30},
                    {label: 'Деталь', name: 'DetailName', width: 50},
                    {label: 'Год', name: 'CarYear', width: 20},
                    {label: 'Кузов', name: 'BodyName', width: 50},
                    {label: 'Двигатель', name: 'EngineName', width: 30},
                    {label: 'Комментарий', name: 'Comment', width: 50},
                    {label: 'Цена', name: 'Cost', width: 20},
                    {label: 'Номер', name: 'Catalog_Number', width: 20},
                ],
                viewrecords: true, // show the current page, data rang and total records on the toolbar
                autowidth: true,
                height: $('#modalResult').height() - 100,
                rowNum: 500,
                datatype: 'local',
                pager: "#part-pager",
                styleUI: 'Bootstrap',
                responsive: true,
                cmTemplate: {sortable: false,},
                ondblClickRow: function(id) {
                    openFirmInParts(grid.getCell(id, 'ID_Firm'));
                },
            });

            grid.jqGrid('bindKeys', {
                "onEnter": function (id) {
                    openFirmInParts(grid.getCell(id, 'ID_Firm'));
                }
            });

            grid.bind('keydown', function (e) {
                let rowInPage = grid.jqGrid('getGridParam','rowNum');
                let totalPages = grid.jqGrid('getGridParam','lastpage');
                let currentPage = grid.jqGrid('getGridParam','page');
                let currentRow = grid.jqGrid ('getGridParam', 'selrow');
                let realRowInLasPage = grid.jqGrid ('getGridParam', 'records') - (rowInPage * (totalPages - 1));

                if(e.ctrlKey && (e.keyCode == KEY.DOWN || e.keyCode == KEY.UP)){
                    let i = currentRow;
                    let oldID = grid.getCell(i, 'ID_Firm');
                    let newID = oldID;

                    if(e.keyCode == KEY.DOWN && e.ctrlKey)
                        newID = grid.getCell( Math.abs(i - 1), 'ID_Firm');
                    else
                        newID = grid.getCell( Math.abs(i - 1), 'ID_Firm');

                    while(newID == oldID && i < (currentPage < totalPages ? rowInPage : realRowInLasPage) && i > 0){
                        if(e.keyCode == KEY.DOWN && e.ctrlKey)
                            i++;
                        else
                            i--;
                        newID = grid.getCell(i, 'ID_Firm');
                    }
                    if(e.keyCode == KEY.DOWN && e.ctrlKey)
                        grid.jqGrid('setSelection', i, false);
                    else
                        grid.jqGrid('setSelection', i, false);
                    grid.focus();
                }

                // если вниз и последняя строка
                if (e.keyCode == KEY.DOWN && totalPages != currentPage && searchParts.pagerToNext) {
                    grid.jqGrid('setGridParam', {"page": currentPage + 1}).trigger("reloadGrid");
                    grid.jqGrid('setSelection', 1, false);
                    searchParts.highlightRow();
                    grid.focus();
                }
                // если вниз и последняя строка последней страницы
                if(e.keyCode == KEY.DOWN && totalPages == currentPage && searchParts.pagerLastRow && totalPages > 1){
                    grid.jqGrid('setGridParam', {"page": 1}).trigger("reloadGrid");
                    grid.jqGrid('setSelection', 1, false);
                    searchParts.highlightRow();
                    grid.focus();
                }
                if (e.keyCode == KEY.UP && currentPage > 1 && searchParts.pagerToBack) {
                    grid.jqGrid('setGridParam', {"page": currentPage - 1}).trigger("reloadGrid");
                    grid.jqGrid('setSelection', rowInPage, false);
                    searchParts.highlightRow();
                    grid.focus();
                }

                if (e.keyCode == KEY.PAGE_DOWN || e.keyCode == KEY.PAGE_UP) {
                    setTimeout(function () {
                        document.elementFromPoint(200, grid.closest(".ui-jqgrid-bdiv").height() / 2).click();
                    }, 500);
                }

                if(e.keyCode == KEY.HOME) {
                    if(currentPage > 1) {
                        grid.jqGrid('setGridParam', {"page": 1}).trigger("reloadGrid");
                        searchParts.highlightRow();
                    }
                    grid.jqGrid('setSelection', 1, false);
                    grid.focus();
                }

                if(e.keyCode == KEY.END) {
                    if(currentPage == totalPages) {
                    	grid.jqGrid('setSelection', realRowInLasPage, false);
                    } else {
                    	grid.jqGrid('setSelection', rowInPage, false);
                    }
                    grid.focus();
                }

                (currentRow == realRowInLasPage && currentPage == totalPages)
                ? searchParts.pagerLastRow = true : searchParts.pagerLastRow = false;

                currentRow == rowInPage
                ? searchParts.pagerToNext = true : searchParts.pagerToNext = false;

                currentRow == 1
                ? searchParts.pagerToBack = true : searchParts.pagerToBack = false;
            });
            this.gridCreate = true;
        }

        grid.jqGrid("clearGridData");
        grid[0].grid.beginReq();

        $.ajax({
            method: "GET",
            url: "index.php?r=site/search-parts",
            data: {
                detail_id : searchParts.idDetail,
                mark_id   : searchParts.idMark,
                model_id  : searchParts.idModel,
                body_id   : searchParts.idBody,
                engine_id : searchParts.idEngine,
                number    : document.getElementById('number').value,
            }
        }).done(function(data){
            searchParts.render(data.message);
        });
    },

    getDetails :  function () {
        $.ajax({
            method: "GET",
            url: "index.php?r=site/get-details-name",
            data: {}
        }).done(function(data){
            $('#detail-select').select2({
                data : { results: data, text: 'Name' },
                sortResults : function(results, container, query) {
                    if(query.term != undefined && query.term.length > 0) {
                        return results.sort(function(a, b) {
                            let index = a.Name.toLowerCase().indexOf(query.term.toLowerCase().trim()) -
                                b.Name.toLowerCase().indexOf(query.term.toLowerCase().trim());

                            return index > 0 ? index : a.Name.length - b.Name.length;
                        });
                    } else {
                        return results;
                    }
                },
                openOnEnter : false,
                allowClear : true,
            }).on("select2-selecting", function(e) {
                searchParts.idDetail = e.choice.id;
            }).on("select2-removed", function(e) {
                searchParts.idDetail = false;
            }).on("select2-focus", function (e) {
                searchParts.currentSelect = this;
            });
        });
    },

    getMarks :  function () {
        $.ajax({
            method: "GET",
            url: "index.php?r=site/get-marks",
            data: {}
        }).done(function(data){
            $('#mark-select').select2({
                data : { results: data, text: 'Name' },
                sortResults : function(results, container, query) {
                    if(query.term != undefined && query.term.length > 0) {
                        return results.sort(function(a, b) {
                            let index = a.Name.toLowerCase().indexOf(query.term.toLowerCase()) -
                                b.Name.toLowerCase().indexOf(query.term.toLowerCase());

                            return index > 0 ? index : a.Name.length - b.Name.length;
                        });
                    } else {
                        return results;
                    }
                },
                openOnEnter : false,
                allowClear : true,
            }).on("select2-selecting", function(e) {
                $('#model-select').select2("enable", true);
                $('#engine-select').select2("enable", true);
                $('#body-select').select2("enable", false);
                searchParts.idMark = e.choice.id;
                searchParts.idModel = false;
                searchParts.idBody = false;
                searchParts.idEngine = false;
                searchParts.getModels();
                searchParts.getEngine();
            }).on("select2-removed", function(e) {
                searchParts.idMark = false;
                searchParts.idModel = false;
                searchParts.idBody = false;
                searchParts.idEngine = false;
                $('#model-select').select2("enable", false);
                $('#body-select').select2("enable", false);
                $('#engine-select').select2("enable", false);
            }).on("select2-focus", function (e) {
                searchParts.currentSelect = this;
            });
        });
    },

    getModels : function() {
        $.ajax({
            method: "GET",
            url: "index.php?r=site/get-models",
            data: {id : searchParts.idMark}
        }).done(function(data){
            $('#model-select').select2({
                data : { results: data, text: 'Name' },
                sortResults : function(results, container, query) {
                    if(query.term != undefined && query.term.length > 0) {
                        return results.sort(function(a, b) {
                            let index = a.Name.toLowerCase().indexOf(query.term.toLowerCase()) -
                                b.Name.toLowerCase().indexOf(query.term.toLowerCase());

                            return index > 0 ? index : a.Name.length - b.Name.length;
                        });
                    } else {
                        return results;
                    }
                },
                openOnEnter : false,
                allowClear : true,
            }).select2("val", "");
        });
    },

    getBodys : function() {
        $.ajax({
            method: "GET",
            url: "index.php?r=site/get-bodys",
            data: {id : searchParts.idModel}
        }).done(function(data){
            $('#body-select').select2({
                data : { results: data, text: 'Name' },
                sortResults : function(results, container, query) {
                    if(query.term != undefined && query.term.length > 0) {
                        return results.sort(function(a, b) {
                            let index = a.Name.toLowerCase().indexOf(query.term.toLowerCase()) -
                                b.Name.toLowerCase().indexOf(query.term.toLowerCase());

                            return index > 0 ? index : a.Name.length - b.Name.length;
                        });
                    } else {
                        return results;
                    }
                },
                openOnEnter : false,
                allowClear : true,
            }).select2("val", "");
        });
    },

    getEngine : function() {
        $.ajax({
            method: "GET",
            url: "index.php?r=site/get-engine",
            data: {
                mark_id: searchParts.idMark,
                model_id: searchParts.idModel,
                body_id: searchParts.idBody,
            }
        }).done(function(data){
            $('#engine-select').select2({
                data : { results: data, text: 'Name' },
                sortResults : function(results, container, query) {
                    if(query.term != undefined && query.term.length > 0) {
                        return results.sort(function(a, b) {
                            let index = a.Name.toLowerCase().indexOf(query.term.toLowerCase()) -
                                b.Name.toLowerCase().indexOf(query.term.toLowerCase());

                            return index > 0 ? index : a.Name.length - b.Name.length;
                        });
                    } else {
                        return results;
                    }
                },
                openOnEnter : false,
                allowClear : true,
            }).select2("val", "");
        });
    },
};
/**
 * Объект отвечает за работу с поиском сервисов
 */
var serviceSearch = {
    input: $('#service'),
    groupList: $('#service')[0].innerHTML,
    lastGroupId: $('#service')[0][0].value,
    gridCreate: false,
    inCategory: false,
    modalWindow: $('#modalResult'),
    grid: $("#service-result-search"),
    open: function (event) {
        if ((event.keyCode == KEY.ENTER || event.type == "dblclick") && (!this.inCategory)) {
            this.openCategory();
            this.inCategory = true;
            return false;
        } else if ((event.keyCode == KEY.ENTER || event.type == "dblclick") && this.inCategory) {
            this.searchService();
            return false;
        }
        if (event.keyCode == KEY.ESC && this.inCategory) {
            this.renderGroups();
            this.inCategory = false;
            this.input[0].value = this.lastGroupId;
            return false;
        }
    },
    openCategory: function () {
        this.lastGroupId = this.input[0].value;
        $.ajax({
            method: "GET",
            url: "index.php?r=site%2Fget-service-group",
            data: {id: this.input[0].value}
        }).done(function (data) {
            serviceSearch.input.html(data.message);
            serviceSearch.input[0].value = serviceSearch.input[0][0].value;
        });

    },
    searchService: function () {
        this.modalWindow.modal({backdrop: false});
        let grid = this.grid;
        $('#gbox_service-result-search').show();
        // настраиваем грид для результатов
        // делаем это здесь что бы ширина соотвествала экрану
        if (!this.gridCreate) {
            grid.jqGrid({
                colModel: [
                    {label: 'Row', name: 'Row', key: true, width: -1, hidden: true},
                    {label: 'ID_Firm', name: 'ID_Firm', width: -1, hidden: true},
                    {label: 'Фирма', name: 'Name', width: 150},
                    {label: 'Адрес', name: 'Address', width: 100},
                    {label: 'Комментарий', name: 'Comment', width: 100},
                    {label: 'Список авто', name: 'CarList', width: 150},
                    {label: 'Район', name: 'District', width: 50},
                ],
                viewrecords: true, // show the current page, data rang and total records on the toolbar
                autowidth: true,
                height: $('#modalResult').height() - 100,
                rowNum: 100,
                datatype: 'local',
                pager: "#service-pager",
                styleUI: 'Bootstrap',
                responsive: true,
                cmTemplate: {sortable: false,},
                ondblClickRow: function(id) {
                    openFirmInParts(grid.getCell(id, 'ID_Firm'));
                },
            });

            grid.jqGrid('bindKeys', {
                "onEnter": function (id) {
                    openFirmInParts(grid.getCell(id, 'ID_Firm'));
                }
            });

            this.gridCreate = true; // для того что бы делать это единожды
        }

        grid.jqGrid("clearGridData");
        grid[0].grid.beginReq();
        $.ajax({
            method: "GET",
            url: "index.php?r=site/service-search",
            data: {id: this.input[0].value}
        }).done(function (data) {
            grid.jqGrid('setGridParam', {data: data.rows});
            // hide the show message
            grid[0].grid.endReq();
            // refresh the grid
            grid.trigger('reloadGrid');
            grid.setSelection(1, true);
            grid.focus();
            result.service = true;
            result.firms = false;
            result.parts = false;
        });
    },
    renderGroups: function () {
        this.input.html(this.groupList);
    },
};

/**
 * Функция открытия карточки фирмы в результатах поиска фирм
 * @param id
 */
function openFirm(id) {
    $.ajax({
        method: "GET",
        url: "index.php?r=site/get-firm",
        data: {
            firm_id : id,
        }
    }).done(function(data) {
        // мапим данные
        $('#firmName').html(data.message[0].Name);
        $('#firmOrganizationType').html(data.message[0].OrganizationType);
        $('#firmActivityType').html(data.message[0].ActivityType);
        $('#firmDistrict').html(data.message[0].District);
        $('#firmAddress').html(data.message[0].Address);
        $('#firmPhone').html(data.message[0].Phone);
        $('#firmFax').html(data.message[0].Fax);
        $('#firmEmail').html(data.message[0].Email);
        $('#firmURL').html(data.message[0].URL);
        $('#firmOperatingMode').html(data.message[0].OperatingMode);
        $('#firmComment').html(data.message[0].Comment);

        // открываем окно
        $('#modalFirm').draggable({
            handle: ".modal-dialog"
        }).modal({backdrop: false});
    });
}

/**
 * функция открытия "урезаной" карточки фирмы в результатах поиска запчастей и сервисов
 * @param id
 */
function openFirmInParts(id) {
    $.ajax({
        method: "GET",
        url: "index.php?r=site/get-firm",
        data: {
            firm_id : id,
        }
    }).done(function(data){
        $('#partsName').html(data.message[0].Name);
        $('#partsDistrict').html(data.message[0].District);
        $('#partsAddress').html(data.message[0].Address);
        $('#partsPhone').html(data.message[0].Phone);
        $('#partsOperatingMode').html(data.message[0].OperatingMode);

        $('#modalParts').draggable({
            handle: ".modal-dialog"
        }).modal({backdrop : false});
    });
}

/**
 * Функция обработки хоткеев навигации
 */
function keyNavigate(event) {
    // перемещение по фильтрам
    if (event.keyCode == KEY.RIGHT && event.ctrlKey) {
        if (result.firms) {
            $(searchParts.currentSelect).select2("open");
            $(searchParts.currentSelect).select2("close");
            result.parts = true;
            result.firms = false;
        } else if (result.parts) {
            $('#service').focus();
            result.service = true;
            result.parts = false;
        } else if (result.service) {
            $($('#search-line').focus()).select();
            result.firms = true;
            result.service = false;
        }
    }
    if (event.keyCode == KEY.LEFT && event.ctrlKey) {
        if (result.firms) {
            $('#service').focus();
            result.service = true;
            result.firms = false;
        } else if (result.parts) {
            $($('#search-line').focus()).select();
            result.firms = true;
            result.parts = false;
        } else if (result.service) {
            $(searchParts.currentSelect).select2("open");
            $(searchParts.currentSelect).select2("close");
            result.parts = true;
            result.service = false;
        }
    }
}


function ready() {
    // Инициализация
    
    $('body').on("keydown", keyNavigate);

    let search = $('#search-line');
    $(search.focus()).select();
    search.keypress(function(e) { searcherFirms.runSearch(e) });
    $('#search-firm-button').on( "click", function () {
        searcherFirms.search();
    });

    result.firms = true;
    result.parts = false;
    result.service = false;

    $('#modalFirm').on('hidden.bs.modal', function () {
        $("#firm-result-search").focus();
    });

    $('#modalParts').on('hidden.bs.modal', function () {
        if(result.service) {
            $("#service-result-search").focus();
        } else if (result.parts) {
            $("#part-result-search").focus();
        }
    });

    $('#modalResult').on('hidden.bs.modal', function () {
        if(result.service) {
            $('#service').focus();
            $('#gbox_service-result-search').hide();
        }
        if(result.firms) {
            $('#search-line').focus();
            $('#gbox_firm-result-search').hide();
        }
        if(result.parts) {
            $('#gbox_part-result-search').hide();
            $(searchParts.currentSelect).select2("open");
            $(searchParts.currentSelect).select2("close");
        }
    });

    searchParts.getDetails();
    searchParts.getMarks();


    $('#model-select').select2({
        data : { results: [{id : 1, Name : 'new'}], text: 'Name' },
        sortResults : function(results, container, query) {
            if(query.term != undefined && query.term.length > 0) {
                return results.sort(function(a, b) {
                    let index = a.Name.toLowerCase().indexOf(query.term.toLowerCase()) -
                        b.Name.toLowerCase().indexOf(query.term.toLowerCase());

                    return index > 0 ? index : a.Name.length - b.Name.length;
                });
            } else {
                return results;
            }
        },
        openOnEnter : false,
        allowClear : true,
    }).on("select2-selecting", function(e) {
        searchParts.idModel = e.choice.id;
        $('#body-select').select2("enable", true);
        searchParts.getBodys();
        searchParts.getEngine();
    }).on("select2-removed", function(e) {
        searchParts.idModel = false;
        searchParts.idBody = false;
        $('#body-select').select2("enable", false);
        searchParts.getEngine();
    }).on("select2-focus", function (e) {
        searchParts.currentSelect = this;
    });


    $('#body-select').select2({
        data : [],
        sortResults : function(results, container, query) {
            if(query.term != undefined && query.term.length > 0) {
                return results.sort(function(a, b) {
                    let index = a.Name.toLowerCase().indexOf(query.term.toLowerCase()) -
                        b.Name.toLowerCase().indexOf(query.term.toLowerCase());

                    return index > 0 ? index : a.Name.length - b.Name.length;
                });
            } else {
                return results;
            }
        },
        openOnEnter : false,
        allowClear : true,
    }).on("select2-selecting", function(e) {
        searchParts.idBody = e.choice.id;
        searchParts.getEngine();
    }).on("select2-removed", function(e) {
        searchParts.idBody = false;
        searchParts.getEngine();
    }).on("select2-focus", function (e) {
        searchParts.currentSelect = this;
    });

    $('#engine-select').select2({
        data : [],
        sortResults : function(results, container, query) {
            if(query.term != undefined && query.term.length > 0) {
                return results.sort(function(a, b) {
                    let index = a.Name.toLowerCase().indexOf(query.term.toLowerCase()) -
                        b.Name.toLowerCase().indexOf(query.term.toLowerCase());

                    return index > 0 ? index : a.Name.length - b.Name.length;
                });
            } else {
                return results;
            }
        },
        openOnEnter : false,
        allowClear : true,
    }).on("select2-selecting", function(e) {
        searchParts.idEngine = e.choice.id;
    }).on("select2-removed", function(e) {
        searchParts.idEngine = false;
    }).on("select2-focus", function (e) {
        searchParts.currentSelect = this;
    });

    $('#model-select').select2("enable", false);
    $('#body-select').select2("enable", false);
    $('#engine-select').select2("enable", false);
}

document.addEventListener("DOMContentLoaded", ready);
