$ = window.jQuery;
$(function () {
    const STATE = {};
    const URLS = {
        FIRMS_FIND: "index.php?r=site/search",
        FIRM_BY_ID: "index.php?r=site/get-firm",
        GET_MARKS: "index.php?r=site/get-marks",
        GET_MODELS: "index.php?r=site/get-models",
        GET_BODIES: "index.php?r=site/get-bodys",
        GET_ENGINES: "index.php?r=site/get-engine",
        GET_DETAILS: "index.php?r=site/get-details-name",
        PARTS_FIND: "index.php?r=site/search-parts",
        SERVICE_GET_CATEGORY: "index.php?r=site/get-service-group",
        SERVICE_FIND: "index.php?r=site/service-search",
        STATISTIC_FIRMS: "index.php?r=site/stat-firm-open-firm",
        STATISTIC_PARTS: "index.php?r=site/stat-part-open-firm",
        STATISTIC_SERVICE: "index.php?r=site/stat-service-open-firm",
    };
    const KEY = {
        TAB: 9, ENTER: 13, ESC: 27, SPACE: 32,
        LEFT: 37, UP: 38, RIGHT: 39, DOWN: 40,
        SHIFT: 16, CTRL: 17, ALT: 18, PAGE_UP: 33,
        PAGE_DOWN: 34, HOME: 36, END: 35, BACKSPACE: 8,
        DELETE: 46, F1: 112, F2: 113, F3: 114,
    };

    $(document).ready(function () {
        $("body").on("keydown", function (e) {
            if (!$(this).hasClass("modal-open")) {
                if (e.keyCode === KEY.F1) {
                    $("#search-line").focus();
                    e.preventDefault();
                }
                if (e.keyCode === KEY.F2) {
                    STATE.parts.detailInput.select2("focus");
                    e.preventDefault();
                }
                if (e.keyCode === KEY.F3) {
                    STATE.service.lastInput.focus();
                    e.preventDefault();
                }
            }
        });
        STATE.parts = {
            modal: $("#parts-search-result"),
            grid: $("#parts-result"),
            gridPager: "#parts-pager",
            detailInput: $("#detail-select"),
            markInput: $("#mark-select"),
            modelInput: $("#model-select"),
            bodyInput: $("#body-select"),
            engineInput: $("#engine-select"),
            numberInput: $("#number"),
            submitButton: $("#search-parts-button"),
            lastInput: undefined,
            cols: [
                {label: "Row", name: "Row", key: true, width: -1, hidden: true},
                {label: "Приоритет", name: "Priority", width: 7},
                {label: "ID", name: "id", width: 10},
                {label: "Марка", name: "MarkName", width: 30},
                {label: "Модель", name: "ModelName", width: 30},
                {label: "Деталь", name: "DetailName", width: 50},
                {label: "Год", name: "CarYear", width: 20},
                {label: "Кузов", name: "BodyName", width: 50},
                {label: "Двигатель", name: "EngineName", width: 30},
                {label: "Комментарий", name: "Comment", width: 50},
                {label: "Цена", name: "Cost", width: 20},
                {label: "Номер", name: "Catalog_Number", width: 20},
            ],
            highlightRow: function () {
                let rowInPage = this.grid.jqGrid("getGridParam", "rowNum");
                let totalPages = this.grid.jqGrid("getGridParam", "lastpage");
                let currentPage = this.grid.jqGrid("getGridParam", "page");
                let realRowInLasPage = this.grid.jqGrid("getGridParam", "records") - (rowInPage * (totalPages - 1));
                let firmID = this.grid.getCell(1, "id");
                let color = false;
                let i = 1;
                let totalRows = this.grid.jqGrid("getGridParam", "records");
                for (; i <= totalRows; i++) {
                    let newId = this.grid.getCell(i, "id");
                    if(newId) {
                        if (firmID !== newId) {
                            firmID = newId;
                            color = !color;
                        }
                        if (color) {
                            for (let j = 1; j <= 11; j++) {
                                this.grid.setCell(i, j, "", {background: "#dff0d8"});
                            }
                        }
                    }
                }
            },
            statisticOpenFirm: function (id) {
                $.get(URLS.STATISTIC_PARTS, {
                    id: id
                }, function (resp) {
                    if (!resp.success) {
                        console.log("Не пршла статистика. " + resp.message);
                    }
                });
            },
            filterSort: function (results, container, query) {
                if (query.term !== undefined && query.term.length > 0) {
                    return results.sort(function (a, b) {
                        let indexA = a.Name.toLowerCase().indexOf(query.term.toLowerCase().trim());
                        let indexB = b.Name.toLowerCase().indexOf(query.term.toLowerCase().trim());

                        let x = a.Name.toLowerCase();
                        let y = b.Name.toLowerCase();
                        if (indexA === indexB && indexA === 0) {
                            return x < y ? -1 : x > y ? 1 : 0;
                        } else {
                            if (indexA === 0) return -1;
                            if (indexB === 0) return 1;
                            return x < y ? -1 : x > y ? 1 : 0;
                        }
                    });
                } else {
                    return results;
                }
            },
            getDetail: function () {
                return STATE.parts.detailInput.select2("val");
            },
            getMark: function () {
                return STATE.parts.markInput.select2("val");
            },
            getModel: function () {
                return !STATE.parts.modelInput[0].disabled ? STATE.parts.modelInput.select2("val") : null;
            },
            getBody: function () {
                return !STATE.parts.bodyInput[0].disabled ? STATE.parts.bodyInput.select2("val") : null;
            },
            getEngine: function () {
                return !STATE.parts.engineInput[0].disabled ? STATE.parts.engineInput.select2("val") : null;
            },
            getNumber: function () {
                return STATE.parts.numberInput.val().trim() !== "" ? STATE.parts.numberInput.val().trim() : null;
            }
        };

        buildGrid(STATE.parts);
        initializePartsSelect(STATE.parts);

        STATE.firm = {
            modal: $("#firm-search-result"),
            grid: $("#firm-result"),
            gridPager: "#firm-pager",
            lastInput: $("#search-line"),
            cols: [
                {label: "Row", name: "Row", key: true, width: -1, hidden: true},
                {label: "ID", name: "id", width: -1, hidden: true},
                {label: "enabled", name: "enabled", width: -1, hidden: true},
                {label: "Фирма", name: "Name", width: 250},
                {label: "Профиль деятельности", name: "ActivityType", width: 250},
                {label: "Адерс", name: "Address", width: 250},
                {label: "Район", name: "District", width: 250},
                {label: "Примечание", name: "Comment", width: 250},
            ],
            highlightRow: function () {

            },
            statisticOpenFirm: function (id) {
                $.get(URLS.STATISTIC_FIRMS, {
                    id: id
                }, function (resp) {
                    if (!resp.success) {
                        console.log("Не пршла статистика. " + resp.message);
                    }
                });
            }
        };

        buildGrid(STATE.firm);
        initializeFirmsSearchInput(STATE.firm);

        STATE.service = {
            grid: $("#service-result"),
            gridPager: "#service-pager",
            lastInput: $("#service"),
            categories: undefined,
            lastCategory: undefined,
            inCategory: false,
            modal: $("#service-search-result"),
            cols: [
                {label: "Row", name: "Row", key: true, width: -1, hidden: true},
                {label: "id", name: "id", width: -1, hidden: true},
                {label: "Фирма", name: "Name", width: 150},
                {label: "Адрес", name: "Address", width: 100},
                {label: "Комментарий", name: "Comment", width: 100},
                {label: "Список авто", name: "CarList", width: 150},
                {label: "Район", name: "District", width: 50},
            ],
            highlightRow: function () {

            },
            statisticOpenFirm: function (id) {
                $.get(URLS.STATISTIC_SERVICE, {
                    id: id
                }, function (resp) {
                    if (!resp.success) {
                        console.log("Не пршла статистика. " + resp.message);
                    }
                });
            },
            openCategory: function (id) {
                $.get(URLS.SERVICE_GET_CATEGORY, {id: id}, function (resp) {
                    if (resp.success) {
                        STATE.service.lastCategory = id;
                        STATE.service.lastInput.html(resp.data);
                        STATE.service.lastInput[0].value = STATE.service.lastInput[0][0].value;
                        STATE.service.inCategory = true;
                    } else {
                        alert("Не удалось получить список сервисов в группе. " + resp.message);
                    }
                });
            },
            renderCategories: function () {
                STATE.service.lastInput.html(STATE.service.categories);
                STATE.service.inCategory = false;
                if (STATE.service.lastCategory) {
                    STATE.service.lastInput[0].value = STATE.service.lastCategory;
                }
            }
        };

        buildGrid(STATE.service);
        initializeService(STATE.service);

    });

    /**
     * Инициализация инпутов поиска фирм по строке
     * @param firm
     */
    function initializeFirmsSearchInput(firm) {
        /**
         * Навешиваем на инпут и кнопку эвенты поиск фирм по строке
         * @type {jQuery|HTMLElement}
         */
        let firmSearchInput = $("#search-line");

        firmSearchInput
            .on("keydown", searchFirm);

        $("#search-firm-button")
            .on("click", searchFirm);

        function searchFirm(e) {
            let value = firmSearchInput.val().toString().trim();
            if (value.length) {
                if (e.keyCode === KEY.ENTER || e.keyCode === undefined) {
                    firm.modal.modal({backdrop: false});
                    firm.grid[0].grid.beginReq();
                    findFirmsByString(value, function (data) {
                        renderGrid(firm.grid, data);
                    });
                }
            }
        }
    }

    /**
     * Инициализация поиска сервисов
     * @param service
     */
    function initializeService(service) {
        service.categories = service.lastInput[0].innerHTML;

        service.lastInput.on("keydown", function (e) {
            if (e.keyCode === KEY.ENTER) {
                if (service.inCategory) {
                    serviceSearch(this.value, function (data) {
                        service.modal.modal({backdrop: false});
                        renderGrid(service.grid, data);
                    });
                } else {
                    service.openCategory(this.value);
                }
            }
            if (e.keyCode === KEY.ESC && service.inCategory) {
                service.renderCategories();
            }
        });

        service.lastInput.dblclick(function () {
            if (service.inCategory) {
                serviceSearch(this.value, function (data) {
                    service.modal.modal({backdrop: false});
                    renderGrid(service.grid, data);
                });
            } else {
                service.openCategory(this.value);
            }
        });
    }

    function serviceSearch(id, cb) {
        $.get(
            URLS.SERVICE_FIND,
            {id: id},
            function (resp) {
                if (resp.success) {
                    cb(resp.data);
                } else {
                    alert("Не смог произвести поиск по сервисам. " + resp.message);
                }
            });
    }

    /**
     * Поиск фирм по строке
     * @param {string} str
     * @param {function} cb
     */
    function findFirmsByString(str, cb) {
        $.get(
            URLS.FIRMS_FIND,
            {
                "str": str
            },
            function (res) {
                if (res.success) {
                    if (cb) {
                        cb(res.data);
                    }
                } else {
                    alert("Произошла ошибка при запросе: " + res.message);
                }
            });
    }

    /**
     * Инициализирует благин таблицы результатов для объекта
     * @param obj
     */
    function buildGrid(obj) {
        obj.grid.jqGrid({
            colModel: obj.cols,
            viewrecords: true,
            autowidth: true,
            height: obj.modal.height() - 100,
            rowNum: 1000,
            pager: obj.gridPager,
            datatype: "local",
            styleUI: "Bootstrap",
            responsive: true,
            loadonce: true,
            cmTemplate: {sortable: false,},
            ondblClickRow: function (id) {
                openFirmCard(obj.grid.getCell(id, "id"), obj);
                obj.statisticOpenFirm(obj.grid.getCell(id, "id"));
            },
        });

        obj.grid.jqGrid("bindKeys", {
            "onEnter": function (id) {
                openFirmCard(obj.grid.getCell(id, "id"), obj);
                obj.statisticOpenFirm(obj.grid.getCell(id, "id"));
            }
        });

        obj.grid.bind("keydown", function (e) {
            gridKeyHandler(e, obj.grid, obj);
        });

        obj.grid.setGridWidth(window.innerWidth - 20);

        obj.modal.on("hidden.bs.modal", function () {
            if (obj.lastInput) {
                if (obj.lastInput[0].style.display === "none") {
                    obj.lastInput.select2("focus");
                } else {
                    obj.lastInput.focus();
                }
            }
        });
    }

    /**
     * Отображает данные в таблицу
     * @param grid
     * @param data
     */
    function renderGrid(grid, data) {
        grid.jqGrid("clearGridData");

        data = data.map(function (item, i) {
            item.Row = i + 1;
            return item;
        });

        grid.jqGrid("setGridParam", {data: data});
        grid[0].grid.endReq();
        grid.trigger("reloadGrid");
        grid.setSelection(1, true);
        grid.focus();
    }

    /**
     * Отображает карточку фирмы по её id
     * @param id
     * @param obj
     */
    function openFirmCard(id, obj) {
        $.get(URLS.FIRM_BY_ID, {
            firm_id: id,
        }, function (resp) {
            if (resp.success) {
                // мапим данные
                $("#firmName").html(resp.firm.Name);
                $("#firmOrganizationType").html(resp.firm.OrganizationType);
                $("#firmActivityType").html(resp.firm.ActivityType);
                $("#firmDistrict").html(resp.firm.District);
                $("#firmAddress").html(resp.firm.Address);
                $("#firmPhone").html(resp.firm.Phone);
                $("#firmFax").html(resp.firm.Fax);
                $("#firmEmail").html(resp.firm.Email);
                $("#firmURL").html(resp.firm.URL);
                $("#firmOperatingMode").html("<pre>" + resp.firm.OperatingMode + "</pre>");
                $("#firmComment").html(resp.firm.Comment);

                // открываем окно
                $("#modalFirm")
                    .draggable({
                        handle: ".modal-dialog"
                    })
                    .modal({backdrop: false})
                    .one("hidden.bs.modal", function () {
                        obj.grid.focus();
                    });
            } else {
                alert("Произошла ошибка: " + resp.message);
            }
        });
    }

    /**
     * Хоткеи в гридах результатов
     * @param e
     * @param grid
     * @param obj
     */
    function gridKeyHandler(e, grid, obj) {
        let rowInPage = parseInt(grid.jqGrid("getGridParam", "rowNum"));
        let totalPages = parseInt(grid.jqGrid("getGridParam", "lastpage"));
        let currentPage = parseInt(grid.jqGrid("getGridParam", "page"));
        let currentRow = parseInt(grid.jqGrid("getGridParam", "selrow"));
        let realRowInLasPage = parseInt(grid.jqGrid("getGridParam", "records")) - (rowInPage * (totalPages - 1));
        let totalRows = parseInt(grid.jqGrid("getGridParam", "records"));

        if (e.ctrlKey && ((e.keyCode === KEY.DOWN && !obj.pagerLastRow) || e.keyCode === KEY.UP)) {
            let i = currentRow - 1;
            let oldID = grid.getCell(i, "id"),
                newID = oldID;

            while (newID && newID === oldID && i < totalRows && i > 0) {
                e.keyCode === KEY.DOWN ? i++ : i--;
                newID = grid.getCell(i, "id");
            }

            if(!newID) {
                if(e.keyCode === KEY.DOWN) {
                    grid.jqGrid("setGridParam", {"page": currentPage + 1}).trigger("reloadGrid");
                    obj.highlightRow();
                    grid.jqGrid("setSelection", (currentPage * rowInPage) + 1, false);
                } else {
                    grid.jqGrid("setGridParam", {"page": currentPage - 1}).trigger("reloadGrid");
                    obj.highlightRow();
                    grid.jqGrid("setSelection", ((currentPage - 1) * rowInPage) + 1, false);
                    console.log('prev');
                    console.log(((currentPage - 1) * rowInPage) + 1);
                }
            } else {
                grid.jqGrid("setSelection", i, false);
            }

            grid.focus();
        }

        // если вниз и последняя строка
        if (e.keyCode === KEY.DOWN && currentPage < totalPages && obj.pagerToNext) {
            currentRow = (rowInPage * currentPage) + 1;
            currentPage = currentPage + 1;
            grid.jqGrid("setGridParam", {"page": currentPage}).trigger("reloadGrid");
            grid.jqGrid("setSelection", currentRow, false);
            obj.highlightRow();
            grid.focus();
        }
        // если вниз и последняя строка последней страницы
        if (e.keyCode === KEY.DOWN && totalPages === currentPage && isNaN(currentRow)) {
            currentRow = (totalPages - 1) * rowInPage + realRowInLasPage;
            grid.jqGrid("setSelection", currentRow, false);
            grid.focus();
        }
        if (e.keyCode === KEY.UP && currentPage > 1 && obj.pagerToBack) {
            currentPage = currentPage - 1;
            currentRow = (rowInPage * (currentPage - 1)) + rowInPage;
            grid.jqGrid("setGridParam", {"page": currentPage}).trigger("reloadGrid");
            grid.jqGrid("setSelection", currentRow, false);
            obj.highlightRow();
            grid.focus();
        }

        if (e.keyCode === KEY.UP && currentPage === 1 && isNaN(currentRow)) {
            grid.jqGrid("setSelection", 1, false);
            grid.focus();
        }

        if (e.keyCode === KEY.PAGE_DOWN || e.keyCode === KEY.PAGE_UP) {
            setTimeout(function () {
                document.elementFromPoint(200, grid.closest(".ui-jqgrid-bdiv").height() / 2).click();
            }, 500);
        }

        if (e.keyCode === KEY.HOME) {
            if (currentPage > 1) {
                grid.jqGrid("setGridParam", {"page": 1}).trigger("reloadGrid");
                obj.highlightRow();
            }
            grid.jqGrid("setSelection", 1, false);
            grid.focus();
        }

        if (e.keyCode === KEY.END) {
            grid.jqGrid("setGridParam", {"page": totalPages}).trigger("reloadGrid");
            if (totalPages === 1) {
                grid.jqGrid("setSelection", realRowInLasPage, false);
            } else {
                grid.jqGrid("setSelection", (totalPages - 1) * rowInPage + realRowInLasPage, false);
            }
            grid.focus();
        }

        obj.pagerLastRow = (!currentRow && currentPage === totalPages);
        obj.pagerToNext = currentRow === (rowInPage * currentPage);
        obj.pagerToBack = currentRow === (rowInPage * (currentPage - 1)) + 1 && currentPage > 1;
    }

    function findParts(parts) {
        parts.modal.modal({backdrop: false});
        if (parts.lastQuery) {
            if (
                parts.lastQuery.detail_id === parts.getDetail() &&
                parts.lastQuery.mark_id === parts.getMark() &&
                parts.lastQuery.model_id === parts.getModel() &&
                parts.lastQuery.body_id === parts.getBody() &&
                parts.lastQuery.engine_id === parts.getEngine() &&
                parts.lastQuery.number === parts.getNumber()
            ) {
                parts.grid.focus();
                return;
            }
        }

        parts.lastQuery = {
            detail_id: parts.getDetail(),
            mark_id: parts.getMark(),
            model_id: parts.getModel(),
            body_id: parts.getBody(),
            engine_id: parts.getEngine(),
            number: parts.getNumber(),
        };

        parts.grid[0].grid.beginReq();
        $.get(URLS.PARTS_FIND, {
            detail_id: parts.getDetail(),
            mark_id: parts.getMark(),
            model_id: parts.getModel(),
            body_id: parts.getBody(),
            engine_id: parts.getEngine(),
            number: parts.getNumber(),
        }, function (resp) {
            if (resp.success) {
                renderGrid(parts.grid, resp.data);
                parts.highlightRow();
            } else {
                alert("Произошла ошибка поиска запчастей. " + resp.message);
            }
        });
    }

    function initializePartsSelect(parts) {
        parts.numberInput
            .on("keydown", function (e) {
                if (e.keyCode === KEY.TAB) {
                    parts.detailInput.select2("focus");
                    e.preventDefault();
                }
                if (e.keyCode === KEY.ENTER) {
                    findParts(parts);
                }
            })
            .on("focus", function (e) {
                parts.lastInput = $(this);
            })
        ;

        parts.submitButton.parent("form")
            .on("submit", function (e) {
                findParts(parts);
                e.preventDefault();
            });
        parts.modelInput
            .select2({
                data: {},
                sortResults: parts.filterSort,
                openOnEnter: false,
                allowClear: true,
            })
            .select2("enable", false)
            .on("change", function (e) {
                if (e.val) {
                    parts.bodyInput.select2("val", "");
                    getBodies(parts);
                    getEngines(parts);
                } else {
                    parts.bodyInput.select2("enable", false);
                }
            })
            .on("select2-focus", function (e) {
                parts.lastInput = $(this);
            });

        parts.bodyInput
            .select2({
                data: {},
                sortResults: parts.filterSort,
                openOnEnter: false,
                allowClear: true,
            })
            .select2("enable", false)
            .on("change", function (e) {
                getEngines(parts);
            })
            .on("select2-focus", function (e) {
                parts.lastInput = $(this);
            });

        parts.engineInput
            .select2({
                data: {},
                sortResults: parts.filterSort,
                openOnEnter: false,
                allowClear: true,
            })
            .select2("enable", false)
            .on("select2-focus", function (e) {
                parts.lastInput = $(this);
            });

        $.get(URLS.GET_DETAILS, function (resp) {
            if (resp.success) {
                parts.detailInput
                    .select2({
                        data: {results: resp.data, text: "Name"},
                        sortResults: parts.filterSort,
                        openOnEnter: false,
                        allowClear: true,
                    })
                    .select2("val", "")
                    .on("select2-focus", function (e) {
                        parts.lastInput = $(this);
                    });
            } else {
                alert("Не смог получить список наименований деталей. " + resp.message);
            }
        });

        $.get(URLS.GET_MARKS, function (resp) {
            if (resp.success) {
                parts.markInput
                    .select2({
                        data: {results: resp.data, text: "Name"},
                        sortResults: parts.filterSort,
                        openOnEnter: false,
                        allowClear: true,
                    })
                    .on("change", function (e) {
                        if (e.val) {
                            parts.modelInput.select2("val", "");
                            parts.engineInput.select2("val", "");
                            getModels(parts);
                            getEngines(parts);
                        } else {
                            parts.modelInput.select2("enable", false);
                            parts.bodyInput.select2("enable", false);
                            parts.engineInput.select2("enable", false);
                            parts.modelInput.select2("val", "");
                            parts.bodyInput.select2("val", "");
                            parts.engineInput.select2("val", "");
                        }
                    })
                    .select2("val", "")
                    .on("select2-focus", function (e) {
                        parts.lastInput = $(this);
                    });
            } else {
                alert("Не смог получить список марок. " + resp.message);
            }
        });
    }

    function getModels(parts) {
        parts.modelInput
            .select2("data", {results: null})
            .select2("val", "")
            .select2("enable", true);
        parts.bodyInput
            .select2("enable", false)
            .select2("data", {results: null, text: null})
            .select2("val", "");
        $.get(URLS.GET_MODELS, {id: parts.getMark()}, function (resp) {
            if (resp.success) {
                let s = false;
                let input = $("#select2-drop").find("div > input");
                if (input.length) {
                    s = input.val();
                }
                parts.modelInput.select2({
                    data: {results: resp.data, text: "Name"},
                    sortResults: parts.filterSort,
                    openOnEnter: false,
                    allowClear: true,
                });
                if (parts.lastInput.select2) {
                    parts.lastInput.select2("focus");
                    if (s && input.length) {
                        parts.lastInput.select2("open");
                        $("#select2-drop").find("div > input").val(s);
                    }
                }
            } else {
                alert("Не смог получить список моделей. " + resp.message);
            }
        });
    }

    function getEngines(parts) {
        parts.engineInput.select2("enable", true);
        $.get(URLS.GET_ENGINES, {
            mark_id: parts.getMark(),
            model_id: parts.getModel(),
            body_id: parts.getBody()
        }, function (resp) {
            if (resp.success) {
                parts.engineInput.select2({
                    data: {results: resp.data, text: "Name"},
                    sortResults: parts.filterSort,
                    openOnEnter: false,
                    allowClear: true,
                });
            } else {
                alert("Не смог получить список двигателей. " + resp.message);
            }
        });
    }

    function getBodies(parts) {
        parts.bodyInput.select2("enable", true);
        $.get(URLS.GET_BODIES, {
            id: parts.getModel()
        }, function (resp) {
            if (resp.success) {
                let search = parts.bodyInput.select2("val");
                let flag = resp.data.reduce(function (flag, item) {
                    flag = item.id === search;
                    return flag;
                }, false);
                if (!flag) {
                    parts.bodyInput.select2("val", "");
                }
                parts.bodyInput.select2({
                    data: {results: resp.data, text: "Name"},
                    sortResults: parts.filterSort,
                    openOnEnter: false,
                    allowClear: true,
                });
            } else {
                alert("Не смог получить список кузовов. " + resp.message);
            }
        });
    }

});

