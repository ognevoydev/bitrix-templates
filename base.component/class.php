<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

class BaseComponent extends CBitrixComponent
{
    public function onPrepareComponentParams($arParams)
    {
        if (!isset($arParams["CACHE_TIME"])) {
            $arParams["CACHE_TIME"] = 36000000;
        }
        if (!isset($arParams["IBLOCK_ID"])) {
            $arParams["IBLOCK_ID"] = 0;
        }
        if (!isset($arParams["IBLOCK_TYPE"])) {
            $arParams["IBLOCK_TYPE"] = 0;
        }
        if (!isset($arParams["SORT_BY1"])) {
            $arParams["SORT_BY1"] = "ACTIVE_FROM";
        }
        if (!isset($arParams["SORT_ORDER1"])) {
            $arParams["SORT_ORDER1"] = "ASC";
        }
        return $arParams;
    }

    public function executeComponent()
    {
        if ($this->startResultCache()) {
            if (!CModule::IncludeModule("iblock")) {
                $this->AbortResultCache();
                ShowError(GetMessage("IBLOCK_MODULE_NOT_INSTALLED"));
            }
            $arOrder = [$this->arParams["SORT_BY1"] => $this->arParams["SORT_ORDER1"]];
            $arFilter = ["IBLOCK_ID" => $this->arParams["IBLOCK_ID"], "ACTIVE" => "Y"];
            $arSelect = [
                "ID",
                "IBLOCK_ID",
                "NAME",
                "ACTIVE_FROM",
            ];
            $IBlockElements = CIBlockElement::GetList($arOrder, $arFilter, false, false, $arSelect);
            while ($item = $IBlockElements->GetNext()) {
                $id = $item['ID'];
                $this->arResult["ITEMS"][$id] = $item;
            }
            $this->includeComponentTemplate();
        } else {
            $this->abortResultCache();
        }
    }
}
