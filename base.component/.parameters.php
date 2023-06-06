<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

/** @var array $arCurrentValues */

if (!CModule::IncludeModule("iblock"))
    return;

$arIBlockTypes = CIBlockParameters::GetIBlockTypes();

$arIBlocks = [];
$arSort = ["SORT" => "ASC"];
$arFilter = ["TYPE" => $arCurrentValues["IBLOCK_TYPE"]];
$IBlocksRes = CIBlock::GetList($arSort, $arFilter);
while ($IBlock = $IBlocksRes->Fetch())
    $arIBlocks[$IBlock["ID"]] = "[" . $IBlock["ID"] . "] " . $IBlock["NAME"];

$arSorts = ["ASC" => GetMessage("BC_SORT_ORDER_ASC"), "DESC" => GetMessage("BC_SORT_ORDER_DESC")];
$arSortFields = [
    "ID"=>GetMessage("BC_FIELD_ID"),
    "NAME"=>GetMessage("BC_FIELD_NAME"),
    "ACTIVE_FROM"=>GetMessage("BC_FIELD_ACTIVE_FROM"),
    "SORT"=>GetMessage("BC_FIELD_SORT"),
];

$arComponentParameters = [
    "PARAMETERS" => [
        "IBLOCK_TYPE" => [
            "PARENT" => "BASE",
            "NAME" => GetMessage("BC_IBLOCK_TYPE"),
            "TYPE" => "LIST",
            "VALUES" => $arIBlockTypes,
            "REFRESH" => "Y",
        ],
        "IBLOCK_ID" => [
            "PARENT" => "BASE",
            "NAME" => GetMessage("BC_IBLOCK_ID"),
            "TYPE" => "LIST",
            "VALUES" => $arIBlocks,
            "REFRESH" => "Y",
        ],
        "SORT_BY1" => [
            "PARENT" => "DATA_SOURCE",
            "NAME" => GetMessage("BC_SORT_BY1"),
            "TYPE" => "LIST",
            "VALUES" => $arSortFields,
        ],
        "SORT_ORDER1" => [
            "PARENT" => "DATA_SOURCE",
            "NAME" => GetMessage("BC_SORT_ORDER"),
            "TYPE" => "LIST",
            "DEFAULT" => "DESC",
            "VALUES" => $arSorts,
        ],
        "CACHE_TIME" => ["DEFAULT" => 36000000],
    ],
];
