BX.namespace('BaseComponent.List')

BX.BaseComponent.List = {
    gridId: null,
    messages: null,
    signedParameters: null,
    activeTab: null,

    init: function (params) {
        this.gridId = params.gridId;
        this.messages = JSON.parse(params.messages);
        this.signedParameters = params.signedParameters;

        this.addCustomEvents();

        let tabs = document.querySelectorAll('.tab')

        if (tabs.length > 0) {
            for (let tab of tabs) {
                // Переключение вкладок
                tab.addEventListener('click', (e) => {

                    for (let tab of tabs) {
                        tab.classList.remove('ui-btn-active')
                    }

                    e.target.classList.add('ui-btn-active')

                    let tabID = e.target.id
                    this.setActiveTab(tabID)
                })
            }

            tabs[0].dispatchEvent(new Event('click'));
            this.enableTabDragScroll();
        }
    },

    // Подписываемся на события
    addCustomEvents: function(){
        // При изменении выбранной вкладки
        BX.addCustomEvent('Grid::beforeRequest', (gridData, argse) => {
            if (argse.gridId !== this.gridId) {
                return;
            }
            argse.method = 'POST'
            argse.data.activeTab = this.activeTab
        });
    },

    // Установка активной вкладки
    setActiveTab: function (tab) {
        this.activeTab = tab
        this.reloadGrid()
    },

    // Перелистывание вкладок зажатием ЛКМ
    enableTabDragScroll: function () {

        const slider =document.querySelector('.tabs-menu');
        let mouseDown = false;
        let startX, scrollLeft;

        let startDragging = function (e) {
            mouseDown = true;
            startX = e.pageX - slider.offsetLeft;
            scrollLeft = slider.scrollLeft;
        };
        let stopDragging = function (event) {
            mouseDown = false;
        };

        slider.addEventListener('mousemove', (e) => {
            e.preventDefault();
            if(!mouseDown) { return; }
            const x = e.pageX - slider.offsetLeft;
            const scroll = x - startX;
            slider.scrollLeft = scrollLeft - scroll;
        });

        slider.addEventListener('mousedown', startDragging, false);
        slider.addEventListener('mouseup', stopDragging, false);
        slider.addEventListener('mouseleave', stopDragging, false);
    },

    // По нажатию на действие строки
    openDetailPage: function (detailPage) {
        if (detailPage["IN_SLIDER"] === true) {
            BX.SidePanel.Instance.open(detailPage["URL"], {
                cacheable: true,
                width: 1100,
            });
        } else {
            location.href = detailPage["URL"];
        }
    },

    // По нажатию на кнопку панели групповых действий
    openPopUp: function (itemIDs, tabs = [this.activeTab]) {
        let content = document.getElementsByClassName("apply-form")[0];
        let popup = new BX.PopupWindow(
            "my_popup",
            null,
            {
                content: content,
                closeIcon: {right: "20px", top: "10px"},
                titleBar: {
                    content: BX.create("span", {
                        html: '<br><b><span class="ui-btn ui-btn-link popup-title">'
                            + this.getMessage("popup-title") + '</b></span>',
                        'props': {'className': 'access-title-bar'},
                    })
                },
                zIndex: 0,
                offsetLeft: 0,
                offsetTop: 0,
                buttons: [
                    new BX.PopupWindowButton({
                        text: this.getMessage("popup-apply-btn"),
                        className: "popup-window-button-accept",
                        events: {
                            click: function () {
                                // Действие по нажатию на кнопку
                                this.popupWindow.close();
                            }
                        }
                    }),
                    new BX.PopupWindowButton({
                        text: this.getMessage("popup-close-btn"),
                        className: "popup-window-button-close",
                        events: {
                            click: function () {
                                // Действие по нажатию на кнопку
                                this.popupWindow.close();
                            }
                        }
                    })
                ]
            });
        popup.show();
    },

    // Вызов action компонента
    startAction: function (tabs, targetUserID) {
        BX.ajax.runComponentAction('ognevoydev:base.grid.component', 'someFunction', {
            mode: 'class',
            signedParameters: this.signedParameters,
            data: {
                ID: targetUserID,
            }
        }).then(function () {
            BX.BaseComponent.List.reloadGrid();
        }, function () {
            alert("Error occurred while tabs delegation.");
        });
    },

    // Открытие URL в слайдере
    openSidePanel: function (url) {
        BX.SidePanel.Instance.open(url, {
            cacheable: false,
            width: 460,
        });
    },

    // Получение выбранных элементов
    getSelectedIDs: function () {
        let grid = this.getGridInstance();
        if (grid) {
            return grid.getRows().getSelectedIds();
        }
    },

    // Обновление таблицы
    reloadGrid: function () {
        let grid = this.getGridInstance();
        if (grid) {
            grid.reloadTable();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    },

    // Получение таблицы
    getGridInstance: function () {
        let grid = BX.Main.gridManager.getById(this.gridId);
        return grid.instance;
    },

    // Получение строкового ресурса
    getMessage: function (message) {
        return this.messages[message];
    },
}
