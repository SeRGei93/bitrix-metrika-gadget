<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$arParameters = Array(
    "PARAMETERS"=> Array(
        "CACHE_TIME" => array(
            "NAME" => "Время кеширования, сек (0-не кешировать)",
            "TYPE" => "STRING",
            "DEFAULT" => "600"
        ),
    ),
    "USER_PARAMETERS" => Array(
        "TOKEN" => Array(
            "NAME" => 'Токен',
            "TYPE" => "STRING",
            "MULTIPLE" => "N",
            "DEFAULT" => "###############################",
        ),
        "IDS" => Array(
            "NAME" => 'ИД счетчика',
            "TYPE" => "STRING",
            "MULTIPLE" => "N",
            "DEFAULT" => "############",
        ),
        "PERIOD" => Array(
            "NAME" => 'Интервал, дней',
            "TYPE" => "STRING",
            "MULTIPLE" => "N",
            "DEFAULT" => "14",
        )
    ),
);

