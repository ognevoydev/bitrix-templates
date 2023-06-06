<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Application;
use Bitrix\Main\Engine\Contract\Controllerable;
use Bitrix\Main\Grid\Panel\Types;
use Bitrix\Main\Localization\Loc;
use Bitrix\UI\Buttons\Button;
use Bitrix\UI\Buttons\JsCode;

class BaseComponentComponent extends \CBitrixComponent implements Controllerable
{
    const GRID_ID = "GRID_ID";
    const FILTER_ID = "FILTER_ID";

    /**
     * Конструктор
     * Подходит для инициализации сервис-локатора и классов модуля
     * @return void
     */
    public function __construct($component = null)
    {
        parent::__construct($component);
    }

    /**
     * Обработка параметров компонента
     * @return array
     */
    public function onPrepareComponentParams($arParams)
    {
        if (!isset($arParams["USER_ID"])) {
            $arParams["USER_ID"] = 0;
        }
        if (!isset($arParams["USE_FILTER"])) {
            $arParams["USE_FILTER"] = false;
        }

        return $arParams;
    }

    public function executeComponent()
    {
        global $USER;
        if (!$USER->IsAuthorized()) {
            return;
        }
        global $APPLICATION;

        $this->arResult["GRID_ID"] = self::GRID_ID;
        $this->arResult["TABS"] = $this->getGridTabs();
        $this->arResult["GRID_COLUMNS"] = $this->getGridColumns();
        $this->arResult["TOOLBAR_BUTTONS"] = $this->getToolbarButtons();
        $this->arResult["ACTION_PANEL"] = $this->getActionPanel();
        $this->arResult["MESSAGES"] = $this->getMessages();
        if ($this->arParams["USE_FILTER"]) {
            $this->arResult["FILTER"] = $this->getFilter();
        }

        // Если таблица содержит вкладки
        $request = Application::getInstance()->getContext()->getRequest();
        $activeTab = $request->getPost("activeTab");
        $rows = [];
        if (!empty($activeTab)) {
            $rows = $this->getGridRows($this->arParams["USER_ID"], $activeTab);
        }
        $this->arResult["GRID_ROWS"] = $rows;

        $this->includeComponentTemplate();
        $APPLICATION->SetPageProperty("title", Loc::getMessage("BGC_PAGE_TITLE"));
        $APPLICATION->SetTitle(Loc::getMessage("BGC_PAGE_TITLE"));
    }

    /**
     * Получение вкладок для списка
     * @return array
     */
    public function getGridTabs(): array
    {
        $result = [];

        $result["firstTab"] = "Вкладка 1";
        $result["secondTab"] = "Вкладка 2";

        return $result;
    }

    /**
     * Получение столбцов таблицы
     * @return array
     */
    public function getGridColumns(): array
    {
        return [
            ["id" => "ID", "name" => Loc::GetMessage("BGC_COL_ID"), 'default' => true],
            ["id" => "TITLE", "name" => Loc::GetMessage("BGC_COL_TITLE"), 'default' => true],
            ["id" => "DESCRIPTION", "name" => Loc::GetMessage("BGC_COL_DESC"), 'default' => true],
        ];
    }

    /**
     * Получение строк таблицы
     * @param string $tabID
     * @return array
     * @throws Exception
     */
    public function getGridRows(int $userID, string $tabID): array
    {
        $rows = [];

        if ($tabID == "firstTab") {
            $items = [
                [
                    "ID" => 1,
                    "title" => "Строка 1 название",
                    "description" => "Строка 1 описание",
                ],
                [
                    "ID" => 2,
                    "title" => "Строка 2 название",
                    "description" => "Строка 2 описание",
                ],
                [
                    "ID" => 3,
                    "title" => "Строка 3 название",
                    "description" => "Строка 3 описание",
                ],
            ];
        } else if ($tabID == "secondTab") {
            $items = [
                [
                    "ID" => 4,
                    "title" => "Строка 4 название",
                    "description" => "Строка 4 описание",
                ],
                [
                    "ID" => 5,
                    "title" => "Строка 5 название",
                    "description" => "Строка 5 описание",
                ],
                [
                    "ID" => 6,
                    "title" => "Строка 6 название",
                    "description" => "Строка 6 описание",
                ],
            ];
        }

        foreach ($items as $item) {
            $rows[] = [
                "data" => [
                    "ID" => $item["ID"], "TITLE" => $item["title"], "DESCRIPTION" => $item["description"]
                ],
                // Поля, используемые в действиях над строками таблицы
                "actions" => $this->getActions([
                    "ID" => $item["ID"],
                ])
            ];
        }
        return $rows;
    }

    /**
     * Определение действий для строк таблицы
     * @param array $fields
     * @return array
     * @throws Exception
     */
    protected function getActions(array $fields): array
    {
        $actions = [];

        $actions[] = [
            'text' => Loc::getMessage("BGC_BTN_OPEN"),
            'onclick' => "BX.BaseComponent.List.openDetailPage(
                {
                    'URL => '/index.php', 
                    'IN_SLIDER' => false,
                }
            )",
            'default' => true
        ];

        return $actions;
    }

    /**
     * Получение фильтра
     * @return array
     */
    protected function getFilter(): array
    {
        return [
            "ID" => self::FILTER_ID,
            "FILTER" => [
                [
                    "id" => "USER",
                    "name" => Loc::getMessage("BGC_FILTER_USER"),
                    // Тип фильтра - выбор пользователя
                    "type" => "dest_selector",
                    "default" => true,
                ],
            ],
        ];
    }

    /**
     * Получение кнопок для тулбара
     * @return array
     * @throws Exception
     */
    protected function getToolbarButtons(): array
    {
        $buttons = [];

        $reloadButton = new Button(
            [
                "text" => Loc::getMessage("BGC_RELOAD"),
                "click" => new JsCode("BX.BaseComponent.List.reloadGrid()")
            ]
        );
        $reloadButton->addClass('ui-btn-primary');
        $reloadButton->addAttribute("type", "reload-btn");

        $buttons[] = $reloadButton;

        return $buttons;
    }

    /**
     * Получение панели групповых действий
     * @return array
     */
    protected function getActionPanel(): array
    {
        $panel = [
            'GROUPS' => [
                [
                    'ITEMS' => [
                        [
                            'TYPE' => Types::BUTTON,
                            'ID' => "apply_button",
                            'CLASS' => "apply",
                            'TEXT' => Loc::getMessage("BGC_BTN_APPLY"),
                            'ONCHANGE' => [[
                                'ACTION' => 'CALLBACK',
                                'DATA' => [
                                    ['JS' => 'BX.BaseComponent.List
                                        .openPopUp()'],
                                ],
                            ],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        return $panel;
    }

    /**
     * Получение строковых данных в зависимости от языка сайта
     * @return array
     */
    protected function getMessages(): array
    {
        return [
            "popup-title" => Loc::getMessage("BGC_POPUP_TITLE"),
            "popup-apply-btn" => Loc::getMessage("BGC_POPUP_APPLY"),
            "popup-close-btn" => Loc::getMessage("BGC_POPUP_CLOSE"),
        ];
    }

    /**
     * Параметры компонента, используемые в JS-скрипте
     * @return array
     */
    protected function listKeysSignedParameters(): array
    {
        return [
            "USER_ID",
        ];
    }

    public function configureActions(): array
    {
        return [];
    }

    /**
     * Пример action, вызываемого из скрипта
     * @param int $ID
     * @return int
     */
    public function someFunctionAction(int $ID): int
    {
        return $ID;
    }
}
