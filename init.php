AddEventHandler("iblock", "OnAfterIBlockElementUpdate", Array("CIblockElementChanges", "OnAfterIBlockElementUpdateHandler"));

class CIblockElementChanges
{
    public static $disableHandler = false;
    function OnAfterIBlockElementUpdateHandler(&$arFields)
    {
        if (self::$disableHandler)
				return;

        CModule::IncludeModule("iblock");

        if($arFields["ID"]) {
                    /**
                     * Проставляет активность от кнопки "не показывать
                     **/
                    $arSort   = array('DATE_CREATE' => 'DESC');

                    if($arFields['ID']) {
                        $arFilter = Array("IBLOCK_ID"=> IBLOCK_CATALOG_ID, "ID" => $arFields['ID']);
                        $arSelect = Array("ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM", "ACTIVE", "PROPERTY_DISABLED");
                        $dbFields = CIBlockElement::GetList($arSort, $arFilter, false, false, $arSelect);
                        while($dbElement = $dbFields->GetNextElement())
                        {
                           $arFields = $dbElement->GetFields();
                           self::$disableHandler = true; //отключаем
                           if($arFields["PROPERTY_DISABLED_ENUM_ID"] == 42)
                           {
                                $obEl = new CIBlockElement();
                                $boolResult = $obEl->Update($arFields['ID'], array('ACTIVE' => 'N')); // деактивация
                                $GLOBALS['DB']->Commit(); //Закрываем транзакцию, что заставляет БД сохранить изменения перманентно, не реагируя на последующие ошибки
                           } else {
                                $obEl = new CIBlockElement();
                                $boolResult = $obEl->Update($arFields['ID'], array('ACTIVE' => 'Y')); // деактивация
                               $GLOBALS['DB']->Commit(); //Закрываем транзакцию, что заставляет БД сохранить изменения перманентно, не реагируя на последующие ошибки
                           }
                           break;
                        }
                     }

        }
        else {
            //AddMessage2Log("Ошибка");
        }

    }
}
