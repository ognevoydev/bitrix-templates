<?php
use Bitrix\Main\Localization\Loc;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$arComponentParameters = [
    "PARAMETERS" => [
        "USER_ID" => [
            "PARENT" => "BASE",
            "NAME" => Loc::GetMessage("BGC_USER_ID"),
            "TYPE" => "STRING",
            "DEFAULT" => 0,
        ],
        "USE_FILTER" => [
            "PARENT" => "BASE",
            "NAME" => Loc::GetMessage("BGC_USE_FILTER"),
            "TYPE" => "CHECKBOX",
        ],
        "CACHE_TIME" => ["DEFAULT" => 36000000],
    ],
];
