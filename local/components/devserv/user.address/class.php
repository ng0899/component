<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Loader;
use Bitrix\Highloadblock as HL;

if (!Loader::includeModule('highloadblock'))
{
    return;
}

class CDemoSqr extends CBitrixComponent
{

    protected $hlbl = 2;

    public function onPrepareComponentParams($arParams)
    {
        $arParams = parent::onPrepareComponentParams($arParams);
        $arParams["CACHE_TIME"] =  isset($arParams["CACHE_TIME"]) ?$arParams["CACHE_TIME"]: 36000000;
        return $arParams;
    }

    protected function getHlClass()
    {
        $hlblock = HL\HighloadBlockTable::getById($this->hlbl)->fetch();
        $entity = HL\HighloadBlockTable::compileEntity($hlblock);
        return $entity->getDataClass();
    }

    public function getUserAddress()
    {
        global $USER;
        $result = [];

        if($USER->IsAuthorized()){
            $entity_data_class = $this->getHlClass();

            $arFilter = array("UF_USER_ID" => $USER->GetID());
            if($this->arParams['IS_ACTIVE'] == 'Y'){
                $arFilter['!UF_ACTIVE'] = false;
            }

            $obCache = new CPHPCache();
            if( $obCache->InitCache($this->arParams['CACHE_TIME'], md5(serialize([$arFilter])), 'user_address') ){
                $result = $obCache->GetVars();
            }else{
                $rsData = $entity_data_class::getList(array(
                    "select" => array("*"),
                    "order" => array("ID" => "ASC"),
                    "filter" => $arFilter
                ));

                while($arData = $rsData->Fetch()){
                    $arData['UF_ACTIVE'] = ($arData['UF_ACTIVE'] > 0) ? 'Да' : 'Нет';
                    $result[] = ['data' => $arData];
                }

                $obCache->EndDataCache($result);
            }
        }
        return $result;
    }


    public function executeComponent()
    {
        $this->arResult['ROWS'] = $this->getUserAddress();
        $this->IncludeComponentTemplate();
    }
}