<?PHP
/************************************************
 *
 * MyCRM OXID-to-Sugar Connector
 *
 * The Program is provided AS IS, without warranty. You can redistribute it and/or modify it under the terms of the GNU
 * Affero General Public License Version 3 as published by the Free Software Foundation.
 *
 * For contact:
 * MyCRM GmbH
 * Hirschlandstrasse 150
 * 73730 Esslingen
 * Germany
 *
 * www.mycrm.de
 * info@mycrm.de
 *
 ****************************************************/
/*
 * Modifications for Oxid 5.2.6 and SuiteCRM
 * 2016, Patrick Kirsch <pk@wellonga.de>
 *
 */

// Setting error reporting mode
error_reporting(E_ALL ^ E_NOTICE);

if (function_exists('monitor_set_aggregation_hint') && isset($_REQUEST['cl'])) {
    $sAgregationHint = htmlentities($_REQUEST['cl'], ENT_QUOTES, 'UTF-8') . '/';
    if (isset($_REQUEST['fnc'])) {
        $sAgregationHint .= htmlentities($_REQUEST['fnc'], ENT_QUOTES, 'UTF-8');
    }
    monitor_set_aggregation_hint($sAgregationHint);
}


//setting basic configuration parameters
ini_set('session.name', 'sid');
ini_set('session.use_cookies', 0);
ini_set('session.use_trans_sid', 0);
ini_set('url_rewriter.tags', '');
ini_set('magic_quotes_runtime', 0);

/**
 * Returns shop base path.
 *
 * @return string
 */
function getShopBasePath()
{
    return dirname(__FILE__) . '/';
}

/**
 * Returns false.
 *
 * @return bool
 */
//if (!function_exists('isAdmin')) {
//    function isAdmin()
//    {
//        return false;
//    }
//}

// custom functions file
include getShopBasePath() . 'modules/functions.php';

// Generic utility method file
require_once getShopBasePath() . 'core/oxfunctions.php';

// Including main ADODB include
require_once getShopBasePath() . 'core/adodblite/adodb.inc.php';
require_once(dirname(__FILE__) . '/bootstrap.php');

ini_set("soap.wsdl_cache_enabled", "0");
$NAMESPACE = 'http://www.mycrmspace.de/';

$server = new SoapServer("sugarcrm.wsdl");

$server->addFunction("check_connection");
$server->addFunction("update_users");
$server->addFunction("get_users");

function check_connection($username, $password)
{
    $oxu   = new oxUserSugar();
    $login = $oxu->login($username, $password);

    return $login;
}


class oxUserSugar extends oxUser
{
    public function login($sUser, $sPassword, $blCookie = false)
    {
        $ou = new oxUser();
        $ou->login($sUser, $sPassword);

        //login successfull?
        if ($ou->oxuser__oxid->value) {   // yes, successful login
            return "isAdmin";

            return true;
        } else {
            return "loginFailed";
        }
    }
}

function update_users($username, $password, $update_array)
{
    $oxu   = new oxUserSugar();
    $login = $oxu->login($username, $password);
    if ($login != "isAdmin") {
        echo "wrong auth";
        die;
    }

    $user2lead = [

        'OXID'                  => 'oxid_c',
        'OXACTIV'               => 'OXACTIV',
        'OXRIGHTS'              => 'OXRIGHTS',
        'OXSHOPID'              => 'oxshopid_c',
        'OXUSERNAME'            => 'email1',
        'OXPASSWORD'            => 'OXPASSWORD',
        'OXCUSTNR'              => 'oxcustnr_c',
        'OXUSTID'               => 'vat_id_c',
        'OXCOMPANY'             => 'account_name',
        'OXFNAME'               => 'first_name',
        'OXLNAME'               => 'last_name',
        'OXSTREET'              => 'primary_address_street',
        'OXSTREETNR'            => 'OXSTREETNR', //no field in sugar
        'OXADDINFO'             => 'OXADDINFO',
        'OXCITY'                => 'primary_address_city',
        'PRIMARYCOUNTRYNAME'    => 'primary_address_country',
        'OXCOUNTRYID'           => 'OXCOUNTRYID', //hard to obtain for external users, use getCountries ERP method, it should be correct countryid
        'OXZIP'                 => 'primary_address_postalcode',
        'OXFON'                 => 'phone_work',
        'OXFAX'                 => 'phone_fax',
        'OXSAL'                 => 'OXSAL',
        'OXBONI'                => 'OXBONI',
        'OXCREATE'              => 'OXCREATE', //always now
        'OXREGISTER'            => 'OXREGISTER',
        'OXPRIVFON'             => 'phone_home',
        'OXMOBFON'              => 'phone_mobile',
        'OXBIRTHDATE'           => 'OXBIRTHDATE',
        'OXURL'                 => 'OXURL',
        'OXBUERGELLASTCHECK'    => 'OXBUERGELLASTCHECK',
        'OXBUERGELTEXT'         => 'OXBUERGELTEXT',
        'OXBUERGELADRESSSTATUS' => 'OXBUERGELADRESSSTATUS',
        'OXBUERGELADRESSTEXT'   => 'OXBUERGELADRESSTEXT',
        'OXLDAPKEY'             => 'OXLDAPKEY',
        'OXWRONGLOGINS'         => 'OXWRONGLOGINS',
        'ORDER_COUNT'           => 'order_count_c',
        'OXSLASTUPDATE'         => 'oxid_c_last_change_c',
    ];


    $oDB          = oxDb::getDb();
    $update_array = base64_decode($update_array);
    $update_array = json_decode($update_array);

    foreach ($update_array as $k => $v) {
        $oxid_id          = $v->oxid_id;
        $conflicts        = $fld_name = $v->conflicts;
        $conflicts_number = 0;
        $sq               = "";
        foreach ($conflicts as $k1 => $v1) {
            $fld_name  = $v1->field_name;
            $fld_value = $v1->old_value;
            if ($fld_name != "") {
                foreach ($user2lead as $k2 => $v2) {
                    if ($v2 == $fld_name) {
                        $update_oxid[] = ["field" => strtolower($k2), "value" => $fld_value];
                        $conflicts_number++;
                    }
                }
            }
        }
        $conflicts_number = count($update_oxid);
        foreach ($update_oxid as $k => $v) {
            $v['value'] = utf8_decode($v['value']);
            $sq .= " " . $v['field'] . "='" . $v['value'] . "'";
            if ($k + 1 < $conflicts_number) {
                $sq .= ",";
            }
        }
    }

    $sql = "update oxuser set " . $sq . " where oxid='" . $oxid_id . "'";

    $oDB->execute($sql);
    $sql = "select * from oxuser where oxid='" . $oxid_id . "'";
//   $oDB->execute( $sql );    
//   $rs = $oDB->selectLimit( $sql, 10000, 0); 
    $oldMode = $oDB->setFetchMode(ADODB_FETCH_ASSOC);
    $rs      = $oDB->selectLimit($sql, 10000, 0);
    $oDB->setFetchMode($oldMode);

    while (!$rs->EOF) {
        $f = $rs->fields;
        $rs->moveNext();
    }
    $f   = json_encode($f);
    $f   = base64_encode($f);
    $xml = "<?xml version=\"1.0\" encoding=\"ISO-8859-15\"?><result>" . $f . "</result>";

    return $f;
}

function get_users($username, $password)
{
    $oxu   = new oxUserSugar();
    $login = $oxu->login($username, $password);
    if ($login != "isAdmin") {
        echo "wrong auth";
        die;
    }

    $oDB = oxDb::getDb();

//190911 dan , oxuser.oxisopenid
    $sq      = "select oxuser.oxid, oxuser.oxactive, oxuser.oxrights, oxuser.oxshopid, oxuser.oxusername, oxuser.oxpassword, oxuser.oxpasssalt, oxuser.oxcustnr, oxuser.oxustid, oxuser.oxcompany, oxuser.oxfname, oxuser.oxlname, oxuser.oxstreet, oxuser.oxstreetnr, oxuser.oxaddinfo, oxuser.oxcity, oxuser.oxcountryid, oxuser.oxzip, oxuser.oxfon, oxuser.oxfax, oxuser.oxsal, oxuser.oxboni, oxuser.oxcreate, oxuser.oxregister, oxuser.oxprivfon, oxuser.oxmobfon, oxuser.oxbirthdate, oxuser.oxurl, oxuser.oxupdatekey, oxuser.oxupdateexp
,count(oxorder.oxordernr) as order_count,oxcountry.oxtitle as primarycountryname,oxuser.oxslastupdate,oxnewssubscribed.oxdboptin
from oxuser left join oxorder on oxorder.oxuserid=oxuser.oxid  
left join oxcountry on oxcountry.oxid=oxuser.oxcountryid
left join oxnewssubscribed on oxuser.oxid=oxnewssubscribed.oxuserid
where 1 group by oxuser.oxid ";
    $oldMode = $oDB->setFetchMode(ADODB_FETCH_ASSOC);
    $rs      = $oDB->selectLimit($sq, 10000, 0);
    $oDB->setFetchMode($oldMode);


    $user2lead        = [

        'OXID'                  => 'oxid_c',
        'OXACTIV'               => 'OXACTIV',
        'OXRIGHTS'              => 'OXRIGHTS',
        'OXSHOPID'              => 'oxshopid_c',
        'OXUSERNAME'            => 'email1',
        'OXPASSWORD'            => 'OXPASSWORD',
        'OXCUSTNR'              => 'oxcustnr_c',
        'OXUSTID'               => 'vat_id_c',
        'OXCOMPANY'             => 'account_name',
        'OXFNAME'               => 'first_name',
        'OXLNAME'               => 'last_name',
        'OXSTREET'              => 'primary_address_street',
        'OXSTREETNR'            => 'OXSTREETNR', //no field in sugar
        'OXADDINFO'             => 'OXADDINFO',
        'OXCITY'                => 'primary_address_city',
        'PRIMARYCOUNTRYNAME'    => 'primary_address_country',
        'OXCOUNTRYID'           => 'OXCOUNTRYID', //hard to obtain for external users, use getCountries ERP method, it should be correct countryid
        'OXZIP'                 => 'primary_address_postalcode',
        'OXFON'                 => 'phone_work',
        'OXFAX'                 => 'phone_fax',
        'OXSAL'                 => 'OXSAL',
        'OXBONI'                => 'OXBONI',
        'OXCREATE'              => 'OXCREATE', //always now
        'OXREGISTER'            => 'OXREGISTER',
        'OXPRIVFON'             => 'phone_home',
        'OXMOBFON'              => 'phone_mobile',
        'OXBIRTHDATE'           => 'OXBIRTHDATE',
        'OXURL'                 => 'OXURL',
        'OXBUERGELLASTCHECK'    => 'OXBUERGELLASTCHECK',
        'OXBUERGELTEXT'         => 'OXBUERGELTEXT',
        'OXBUERGELADRESSSTATUS' => 'OXBUERGELADRESSSTATUS',
        'OXBUERGELADRESSTEXT'   => 'OXBUERGELADRESSTEXT',
        'OXLDAPKEY'             => 'OXLDAPKEY',
        'OXWRONGLOGINS'         => 'OXWRONGLOGINS',
        'ORDER_COUNT'           => 'order_count_c',
        'OXSLASTUPDATE'         => 'oxid_c_last_change_c',
        'OXDBOPTIN'             => 'oxid_newsletter_c',
    ];
    $user2lead_shipto = [
        'OXSTREET'      => 'alt_address_street',
        'OXCITY'        => 'alt_address_city',
        'OXCOUNTRYNAME' => 'alt_address_country',
        'OXZIP'         => 'alt_address_postalcode',
    ];

    $exp1             = "";
    while (!$rs->EOF) {
        $exp = "";
        $f   = $rs->fields;

        foreach ($f as $k => $v) {
            $nk = strtoupper($k); //$user2lead
            $nk = $user2lead[$nk];
            if (empty($nk)) {
                $nk = $k;
            }
            $exp .= "<" . $nk . ">" . htmlspecialchars($v, ENT_XML1) . "</" . $nk . ">";
        }

        $sq1     = "select oxaddress.*,oxcountry.oxtitle as OXCOUNTRYNAME
from oxaddress left join oxcountry on oxcountry.oxid=oxaddress.oxcountryid  where oxuserid='" . $rs->fields['oxid'] . "' ";
        $oDB1    = oxDb::getDb();
        $oldMode = $oDB1->setFetchMode(ADODB_FETCH_ASSOC);
        $rs1     = $oDB1->selectLimit($sq1, 10000, 0);
        $oDB1->setFetchMode($oldMode);
        while (!$rs1->EOF) {
            $f = $rs1->fields;
            foreach ($f as $k => $v) {
                $nk = strtoupper($k); //$user2lead
                $nk = $user2lead_shipto[$nk];
                if (!empty($nk)) {
                    $exp .= "<" . $nk . ">" . htmlspecialchars($v, ENT_XML1) . "</" . $nk . ">";
                }

            }
            $rs1->moveNext();
        }
        ////////end ship address

        $exp1 .= "<user_list>" . $exp . "</user_list>";

        $rs->moveNext();
    }
    $orders = exportLexwareOrdersToSugar(0, 10000);
    $orders = str_replace("<?xml version=\"1.0\" encoding=\"ISO-8859-15\"?>", "", $orders);

    $xml = "<?xml version=\"1.0\" encoding=\"ISO-8859-15\"?><users>" . $exp1 . "<field_array><definition>" . json_encode($user2lead) . "</definition></field_array><orders><items>" . $orders . "</items></orders></users>";

    if (isset($_REQUEST['debug1'])) {
        return $xml;

    } else {
        return base64_encode($xml);

    }
}

if (isset($_REQUEST['debug1'])) {
    $a = get_users();
}

function exportLexwareOrdersToSugar($iFromOrderNr = null, $iToOrderNr = null)
{
    $cnfg     = new oxImex();
    $myConfig = $cnfg->getConfig();

    $sRet = "";

    $sNewLine = "\r\n";

    $oOrderlist = oxNew("oxlist");
    $oOrderlist->init("oxorder");

    $sSelect = "select oxorder.*,oxorder.OXORDERDATE as oppdate from oxorder where 1 ";

    if (!empty($iFromOrderNr)) {
        $sSelect .= "and oxordernr >= $iFromOrderNr ";
    }
    if (!empty($iToOrderNr)) {
        $sSelect .= "and oxordernr <= $iToOrderNr ";
    }

    $oOrderlist->selectString($sSelect);

    if (!$oOrderlist->count()) {
        return null;
    }

    $sExport = "<?xml version=\"1.0\" encoding=\"ISO-8859-15\"?>$sNewLine";
    $sExport .= "<Bestellliste>$sNewLine";
    $sRet .= $sExport;

    foreach ($oOrderlist->arrayKeys() as $key) {
        $oOrder = $oOrderlist[$key];
        $oUser  = oxNew("oxuser");
        $oUser->load($oOrder->oxorder__oxuserid->value);

        $sExport = "<Bestellung zurueckgestellt=\"Nein\" bearbeitet=\"Nein\" uebertragen=\"Nein\">$sNewLine";
        $sExport .= "<Bestellnummer>" . $oOrder->oxorder__oxordernr->value . "</Bestellnummer>$sNewLine";
        $sExport .= "<Currency>" . $oOrder->oxorder__oxcurrency->value . "</Currency>$sNewLine";
        $sExport .= "<CurrencyRate>" . $oOrder->oxorder__oxcurrate->value . "</CurrencyRate>$sNewLine";
        $sExport .= "<Bestellid>" . $oOrder->oxorder__oxid->value . "</Bestellid>$sNewLine";
        $sExport .= "<Standardwaehrung>978</Standardwaehrung>$sNewLine";
        $sExport .= "<Bestelldatum>$sNewLine";
        $sDBDate = oxRegistry::get("oxUtilsDate")->formatDBDate($oOrder->oxorder__oxorderdate->value);
        $sExport .= "<Datum>" . $oOrder->oxorder__oppdate . "</Datum>$sNewLine";
        $sExport .= "<Zeit>" . substr($sDBDate, 11, 8) . "</Zeit>$sNewLine";
        $sExport .= "</Bestelldatum>$sNewLine";
        $sExport .= "<Kunde>$sNewLine";

        $sExport .= "<Kundennummer>" . $cnfg->interForm($oUser->oxuser__oxcustnr->value) . "</Kundennummer>$sNewLine";
        $sExport .= "<Kundeid>" . $cnfg->interForm($oUser->oxuser__oxid->value) . "</Kundeid>$sNewLine";
        $sExport .= "<Firmenname>" . $cnfg->interForm($oOrder->oxorder__oxbillcompany->value) . "</Firmenname>$sNewLine";
        $sExport .= "<Vorname>" . $cnfg->interForm($oOrder->oxorder__oxbillfname->value) . "</Vorname>$sNewLine";
        $sExport .= "<Name>" . $cnfg->interForm($oOrder->oxorder__oxbilllname->value) . "</Name>$sNewLine";
        $sExport .= "<Strasse>" . $cnfg->interForm($oOrder->oxorder__oxbillstreet->value) . " " . $cnfg->interForm($oOrder->oxorder__oxbillstreetnr->value) . "</Strasse>$sNewLine";
        $sExport .= "<PLZ>" . $cnfg->interForm($oOrder->oxorder__oxbillzip->value) . "</PLZ>$sNewLine";
        $sExport .= "<Ort>" . $cnfg->interForm($oOrder->oxorder__oxbillcity->value) . "</Ort>$sNewLine";
        $sExport .= "<Bundesland>" . "" . "</Bundesland>$sNewLine";
        $sExport .= "<Land>" . $cnfg->interForm($oOrder->oxorder__oxbillcountry->value) . "</Land>$sNewLine";
        $sExport .= "<Email>" . $cnfg->interForm($oUser->oxuser__oxusername->value) . "</Email>$sNewLine";
        $sExport .= "<Telefon>" . $cnfg->interForm($oOrder->oxorder__oxbillfon->value) . "</Telefon>$sNewLine";
        $sExport .= "<Telefon2>" . $cnfg->interForm($oUser->oxuser__oxprivfon->value) . "</Telefon2>$sNewLine";
        $sExport .= "<Fax>" . $cnfg->interForm($oOrder->oxorder__oxbillfax->value) . "</Fax>$sNewLine";

        // lieferadresse
        if ($oOrder->oxorder__oxdellname->value) {
            $sDelComp    = $oOrder->oxorder__oxdelcompany->value;
            $sDelfName   = $oOrder->oxorder__oxdelfname->value;
            $sDellName   = $oOrder->oxorder__oxdellname->value;
            $sDelStreet  = $oOrder->oxorder__oxdelstreet->value;
            $sDelZip     = $oOrder->oxorder__oxdelzip->value;
            $sDelCity    = $oOrder->oxorder__oxdelcity->value;
            $sDelCountry = $oOrder->oxorder__oxdelcountry->value;
        } else {
            $sDelComp    = "";
            $sDelfName   = "";
            $sDellName   = "";
            $sDelStreet  = "";
            $sDelZip     = "";
            $sDelCity    = "";
            $sDelCountry = "";
        }

        $sExport .= "<Lieferadresse>$sNewLine";
        $sExport .= "<Firmenname>" . $cnfg->interForm($sDelComp) . "</Firmenname>$sNewLine";
        $sExport .= "<Vorname>" . $cnfg->interForm($sDelfName) . "</Vorname>$sNewLine";
        $sExport .= "<Name>" . $cnfg->interForm($sDellName) . "</Name>$sNewLine";
        $sExport .= "<Strasse>" . $cnfg->interForm($sDelStreet) . "</Strasse>$sNewLine";
        $sExport .= "<PLZ>" . $cnfg->interForm($sDelZip) . "</PLZ>$sNewLine";
        $sExport .= "<Ort>" . $cnfg->interForm($sDelCity) . "</Ort>$sNewLine";
        $sExport .= "<Bundesland>" . "" . "</Bundesland>$sNewLine";
        $sExport .= "<Land>" . $cnfg->interForm($sDelCountry) . "</Land>$sNewLine";
        $sExport .= "</Lieferadresse>$sNewLine";
        $sExport .= "<Matchcode>" . $cnfg->interForm($oOrder->oxorder__oxbilllname->value) . ", " . $cnfg->interForm($oOrder->oxorder__oxbillfname->value) . "</Matchcode>$sNewLine";

        // ermitteln ob steuerbar oder nicht
        $sCountry     = strtolower($oUser->oxuser__oxcountryid->value);
        $aHomeCountry = $myConfig->getConfigParam('aHomeCountry');
        if (is_array($aHomeCountry) && in_array($sCountry, $aHomeCountry)) {
            $sSteuerbar = "ja";
        } else {
            $sSteuerbar = "nein";
        }

        $sExport .= "<fSteuerbar>" . $cnfg->interForm($sSteuerbar) . "</fSteuerbar>$sNewLine";
        $sExport .= "</Kunde>$sNewLine";
        $sExport .= "<Artikelliste>$sNewLine";
        $sRet .= $sExport;

        $dSumNetPrice  = 0;
        $dSumBrutPrice = 0;

        /*
        if( $oOrder->oxorder__oxdelcost->value)
        {   // add virtual article for delivery costs
            $oDelCost = oxNew( "oxorderarticle" );
            $oDelCost->oxorderarticles__oxvat->setValue(0);
            $oDelCost->oxorderarticles__oxnetprice->setValue($oOrder->oxorder__oxdelcost->value);
            $oDelCost->oxorderarticles__oxamount->setValue(1);
            $oDelCost->oxorderarticles__oxtitle->setValue("Versandkosten");
            $oDelCost->oxorderarticles__oxbrutprice->setValue($oOrder->oxorder__oxdelcost->value);
            $oOrder->oArticles['oxdelcostid'] = $oDelCost;
        }*/

        $oOrderArticles = $oOrder->getOrderArticles();
        foreach ($oOrderArticles->arrayKeys() as $key) {
            $oOrderArt = $oOrderArticles->offsetGet($key);
            $dVATSet   = array_search($oOrderArt->oxorderarticles__oxvat->value, $myConfig->getConfigParam('aLexwareVAT'));
            $sExport   = "   <Artikel>$sNewLine";
            //$sExport .= "   <Artikelzusatzinfo><Nettostaffelpreis>".$cnfg->InternPrice( $oOrderArt->oxorderarticles__oxnetprice->value)."</Nettostaffelpreis></Artikelzusatzinfo>$sNewLine";
            $sExport .= "   <Artikelzusatzinfo><Nettostaffelpreis></Nettostaffelpreis></Artikelzusatzinfo>$sNewLine";
            $sExport .= "   <SteuersatzID>" . $dVATSet . "</SteuersatzID>$sNewLine";
            $sExport .= "   <Steuersatz>" . $cnfg->internPrice($oOrderArt->oxorderarticles__oxvat->value / 100) . "</Steuersatz>$sNewLine";
            $sExport .= "   <Artikelnummer>" . $oOrderArt->oxorderarticles__oxartnum->value . "</Artikelnummer>$sNewLine";
            $sExport .= "   <Artikelid>" . $oOrderArt->oxorderarticles__oxid->value . "</Artikelid>$sNewLine";
            $sExport .= "   <Anzahl>" . $oOrderArt->oxorderarticles__oxamount->value . "</Anzahl>$sNewLine";
            $sExport .= "   <Description>" . $oOrderArt->oxorderarticles__oxshortdesc->value . "</Description>$sNewLine";
            $sExport .= "   <Netprice>" . $oOrderArt->oxorderarticles__oxnetprice->value . "</Netprice>$sNewLine";
            $sExport .= "   <Brutprice>" . $oOrderArt->oxorderarticles__oxbrutprice->value . "</Brutprice>$sNewLine";
            $sExport .= "   <Vatprice>" . $oOrderArt->oxorderarticles__oxvatprice->value . "</Vatprice>$sNewLine";
            $sExport .= "   <VAT>" . $oOrderArt->oxorderarticles__oxvat->value . "</VAT>$sNewLine";
            $sExport .= "   <Produktname>" . $cnfg->interForm($oOrderArt->oxorderarticles__oxtitle->value);
            if ($oOrderArt->oxorderarticles__oxselvariant->value) {
                $sExport .= "/" . $oOrderArt->oxorderarticles__oxselvariant->value;
            }
            $sExport .= "   </Produktname>$sNewLine";
            $sExport .= "   <Rabatt>0.00</Rabatt>$sNewLine";
            $sExport .= "   <Preis>" . $cnfg->internPrice($oOrderArt->oxorderarticles__oxbrutprice->value / $oOrderArt->oxorderarticles__oxamount->value) . "</Preis>$sNewLine";
            $sExport .= "   </Artikel>$sNewLine";
            $sRet .= $sExport;

            $dSumNetPrice += $oOrderArt->oxorderarticles__oxnetprice->value;
            $dSumBrutPrice += $oOrderArt->oxorderarticles__oxbrutprice->value;
        }

        $dDiscount = $oOrder->oxorder__oxvoucherdiscount->value + $oOrder->oxorder__oxdiscount->value;
        $sExport   = "<GesamtRabatt>" . $cnfg->internPrice($dDiscount) . "</GesamtRabatt>$sNewLine";
        $sExport .= "<GesamtNetto>" . $cnfg->internPrice($dSumNetPrice) . "</GesamtNetto>$sNewLine";
        $sExport .= "<Lieferkosten>" . $cnfg->internPrice($oOrder->oxorder__oxdelcost->value) . "</Lieferkosten>$sNewLine";
        $sExport .= "<Zahlungsartkosten>0.00</Zahlungsartkosten>$sNewLine";
        $sExport .= "<GesamtBrutto>" . $cnfg->internPrice($dSumBrutPrice) . "</GesamtBrutto>$sNewLine";

        $oUserpayment = oxNew("oxuserpayment");
        $oUserpayment->load($oOrder->oxorder__oxpaymentid->value);
        $sPayment = $oUserpayment->oxuserpayments__oxvalue->value;
        $sPayment = str_replace("__", "", $sPayment);
        $sPayment = str_replace("@@", ",", $sPayment);

        $oPayment = oxNew("oxpayment");
        $oPayment->load($oOrder->oxorder__oxpaymenttype->value);


        $sExport .= "<Bemerkung>" . strip_tags($oOrder->oxorder__oxremark->value) . "</Bemerkung>$sNewLine";
        $sRet .= $sExport;

        $sExport = "</Artikelliste>$sNewLine";

        $sExport .= "<Zahlung>$sNewLine";
        $oPayment = oxNew("oxpayment");
        $oPayment->load($oOrder->oxorder__oxpaymenttype->value);

        //print_r($oPayment);

        $sExport .= "<Art>" . $oPayment->oxpayments__oxdesc->value . "</Art>$sNewLine";
        $sExport .= "</Zahlung>$sNewLine";

        $sExport .= "</Bestellung>$sNewLine";
        $sRet .= $sExport;

        // TODO: Notwendigkeit des Setzens von oxexport?
        //$oOrder->oxorder__oxexport->setValue(1);
        // TODO: Hier wird der Bestand runter gezaehlt warum?
        //$oOrder->save();
    }

    $sExport = "</Bestellliste>$sNewLine";
    $sRet .= $sExport;

    return mb_convert_encoding($sRet, 'ISO-8859-1');
}

$server->handle();

exit();
?>
