<?php
use Bitrix\Main\Localization\Loc;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$arComponentDescription = [
    "NAME" => Loc::GetMessage("BGC_NAME"),
    "DESCRIPTION" => Loc::GetMessage("BGC_DESC"),
    "CACHE_PATH" => "Y",
    "PATH" => [
        "ID" => "base_sect",
        "NAME" => GetMessage("BGC_SECTION"),
        "SORT" => 20,
    ]
];
