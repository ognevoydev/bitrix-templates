<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\UI\Extension;
use Bitrix\Main\UI\Filter\Options;
use Bitrix\UI\Toolbar\Facade\Toolbar;

CUtil::InitJSCore(array('ajax', 'popup'));
Extension::load('ui.buttons');
Extension::load("ui.dialogs.messagebox");

// Вывод кнопок в тулбаре
foreach ($arResult["TOOLBAR_BUTTONS"] as $button) {
    Toolbar::addButton($button);
}

// Вывод фильтра
if ($arParams["USE_FILTER"]) {
    Toolbar::addFilter([
        'GRID_ID' => $arResult["GRID_ID"],
        'FILTER_ID' => $arResult["FILTER"]["ID"],
        'FILTER' => $arResult["FILTER"]["FILTER"],
        'ENABLE_LIVE_SEARCH' => true,
        'ENABLE_LABEL' => true,
        'DISABLE_SEARCH' => true,
    ]);

    //Вызов метода компонента при фильтрации
    $filterOptions = new Options($arResult["FILTER"]["ID"]);
    $filterFields = $filterOptions->getFilter($arResult["FILTER"]["FILTER"]);
    if ($filterFields["USER"] > 0) {
        $userID = str_replace("U", "", $filterFields["USER"]);
        $tab = $_POST["activeTab"] ?? array_key_first($arResult["TABS"]);
        $rows = $this->getComponent()->getGridRows($userID, $tab);
    }
}
?>

<div class="main-container" style="overflow-x: hidden">

    <div class="tabs-menu">
        <? foreach ($arResult["TABS"] as $id => $tab) { ?>
            <span id="<?= $id ?>" class="ui-btn ui-btn-light-border ui-btn-sm tab"
                  style="margin: 0"><?= $tab ?></span>
        <? } ?>
    </div>

    <div class="tabcontent" style="overflow-y: hidden; overflow-x: hidden">
        <?php
        $APPLICATION->IncludeComponent('bitrix:main.ui.grid', '', [
            'GRID_ID' => $arResult['GRID_ID'],
            'COLUMNS' => $arResult['GRID_COLUMNS'],
            'ROWS' => $rows ?? $arResult["GRID_ROWS"],
            'ACTION_PANEL' => $arResult['ACTION_PANEL'],
            'AJAX_MODE' => 'Y',
            'AJAX_OPTION_JUMP' => 'N',
            'AJAX_OPTION_HISTORY' => 'N',
            'SHOW_ROW_CHECKBOXES' => true,
            'SHOW_CHECK_ALL_CHECKBOXES' => true,
            'SHOW_ROW_ACTIONS_MENU' => true,
            'SHOW_GRID_SETTINGS_MENU' => false,
            'SHOW_SELECTED_COUNTER' => true,
            'SHOW_TOTAL_COUNTER' => false,
            'ALLOW_PIN_HEADER' => true,
            'ALLOW_HORIZONTAL_SCROLL' => false,
        ]);
        ?>
    </div>
</div>
<div class="apply-form" hidden="hidden" style="width: 400px; height: 100px">
    <span>Содержимое окна, можно вставить компонент</span>
</div>

<script>
    BX.ready(function () {
        let componentParams = {
            gridId: '<?= $arResult["GRID_ID"] ?>',
            messages: '<?= json_encode($arResult["MESSAGES"]) ?>',
            signedParameters: '<?= $this->getComponent()->getSignedParameters() ?>',
        }
        BX.BaseComponent.List.init(componentParams)
    })
</script>
