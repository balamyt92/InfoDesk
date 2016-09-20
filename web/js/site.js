"use strict";
/**
 * Объект хранящий в результатах какого поиска мы находимся
 */
var result = {
    firms : false,
    parts : false,
    service : false,
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
                if (e.keyCode == 40 && totalPages != currentPage && this.pagerToNext) {
                    grid.jqGrid('setGridParam', {"page": currentPage + 1}).trigger("reloadGrid");
                    grid.jqGrid('setSelection', 1, false);
                    grid.focus();
                }
                // если вниз и последняя строка последней страницы
                if(e.keyCode == 40 && totalPages == currentPage && this.pagerLastRow){
                    grid.jqGrid('setGridParam', {"page": 1}).trigger("reloadGrid");
                    grid.jqGrid('setSelection', 1, false);
                    grid.focus();
                }
                // если вверх и первая строка
                if (e.keyCode == 38 && currentPage > 1 && this.pagerToBack) {
                    grid.jqGrid('setGridParam', {"page": currentPage - 1}).trigger("reloadGrid");
                    grid.jqGrid('setSelection', rowInPage, false);
                    grid.focus();
                }

                // 33 - page UP
                // 34 - page DOWN
                if (e.keyCode == 34 || e.keyCode == 33) {
                    setTimeout(function () {
                        document.elementFromPoint(100, grid.closest(".ui-jqgrid-bdiv").height() / 2).click();
                    }, 200);
                }

                currentRow == realRowInPage ? this.pagerLastRow = true : this.pagerLastRow = false;
                currentRow == rowInPage ? this.pagerToNext = true : this.pagerToNext = false;
                currentRow == 1 ? this.pagerToBack = true : this.pagerToBack = false;
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
        if (e.keyCode == 13) {
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


    currentSelect : false,
    pagerToNext : false,
    pagerToBack : false,
    pagerLastRow : false,
    gridCreate: false,
    modalWindow: $('#modalResult'),
    grid: $("#part-result-search"),

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

        result.firms = false;
        result.parts = true;
        result.service = false;
    },

    eventStatus : function(e) {
        if(e.keyCode == 13) {
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
                    {label: 'Коментарий', name: 'Comment', width: 50},
                    {label: 'Цена', name: 'Cost', width: 20},
                    {label: 'Номер', name: 'Catalog_Number', width: 20},
                ],
                viewrecords: true, // show the current page, data rang and total records on the toolbar
                autowidth: true,
                height: $('#modalResult').height() - 100,
                rowNum: 5000,
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
                let realRowInPage = grid.jqGrid ('getGridParam', 'records') - (rowInPage * (totalPages - 1));

                // если вниз и последняя строка
                if (e.keyCode == 40 && totalPages != currentPage && this.pagerToNext) {
                    grid.jqGrid('setGridParam', {"page": currentPage + 1}).trigger("reloadGrid");
                    grid.jqGrid('setSelection', 1, false);
                    grid.focus();
                }
                // если вниз и последняя строка последней страницы
                if(e.keyCode == 40 && totalPages == currentPage && this.pagerLastRow){
                    grid.jqGrid('setGridParam', {"page": 1}).trigger("reloadGrid");
                    grid.jqGrid('setSelection', 1, false);
                    grid.focus();
                }
                if (e.keyCode == 38 && currentPage > 1 && this.pagerToBack) {
                    grid.jqGrid('setGridParam', {"page": currentPage - 1}).trigger("reloadGrid");
                    grid.jqGrid('setSelection', rowInPage, false);
                    grid.focus();
                }

                // 33 - page UP
                // 34 - page DOWN
                if (e.keyCode == 34 || e.keyCode == 33) {
                    setTimeout(function () {
                        document.elementFromPoint(100, grid.closest(".ui-jqgrid-bdiv").height() / 2).click();
                    }, 200);
                }

                // 36 - Home
                if(e.keyCode == 36) {
                    if(currentPage > 1) {
                        grid.jqGrid('setGridParam', {"page": 1}).trigger("reloadGrid");
                    }
                    grid.jqGrid('setSelection', 1, false);
                    grid.focus();
                }

                currentRow == realRowInPage ? this.pagerLastRow = true : this.pagerLastRow = false;
                currentRow == rowInPage ? this.pagerToNext = true : this.pagerToNext = false;
                currentRow == 1 ? this.pagerToBack = true : this.pagerToBack = false;
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
                searchParts.idMark = e.choice.id;
                searchParts.getModels();
                searchParts.getEngine();
            }).on("select2-removed", function(e) {
                searchParts.idMark = false;
                searchParts.idModel = false;
                searchParts.idBody = false;
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
            });
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
            });
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
            });
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
        if ((event.keyCode == 13 || event.type == "dblclick") && (!this.inCategory)) {
            this.openCategory();
            this.inCategory = true;
            return false;
        } else if ((event.keyCode == 13 || event.type == "dblclick") && this.inCategory) {
            this.searchService();
            return false;
        }
        if (event.keyCode == 27 && this.inCategory) {
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
                    {label: 'Улица', name: 'Address', width: 100},
                    {label: 'Коментарий', name: 'Comment', width: 100},
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
    // перемещение по фильтрам по Ctrl + left - 37 и Ctrl + Right - 39
    if (event.keyCode == 39 && event.ctrlKey) {
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
    if (event.keyCode == 37 && event.ctrlKey) {
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
