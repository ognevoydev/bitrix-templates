<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$arComponentDescription = array(
    "NAME" => GetMessage("BC_NAME"),
    "DESCRIPTION" => GetMessage("BC_DESC"),
    "SORT" => 10,
    "CACHE_PATH" => "Y",
    "PATH" => array(
        "ID" => "base_sect",
        "NAME" => GetMessage("BC_SECTION"),
        "SORT" => 10,
    )
);
