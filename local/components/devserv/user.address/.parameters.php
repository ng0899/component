<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentParameters = array(
	"GROUPS" => array(

	),
	"PARAMETERS" => array(
        'CACHE_TIME' => array('DEFAULT' => 120),
        "IS_ACTIVE" => array(
            "NAME" => GetMessage("BXCERT_ACTIVE_ELEMENTS"),
            "TYPE" => "CHECKBOX",
            "DEFAULT" => "Y",
        )
	),

);
