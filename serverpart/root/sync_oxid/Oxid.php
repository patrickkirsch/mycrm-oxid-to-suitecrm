<?php

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
 ****************************************************/
/*
 * Modifications for Oxid 5.2.6 and SuiteCRM
 * 2016, Patrick Kirsch <pk@wellonga.de>
 *
 */
class oxid
{

    var $url;
    var $SoapClient;
    var $users;
    var $oxid_url;
    var $oxid_username;
    var $oxid_password;


    function Connect()
    {
        if (isset($_REQUEST['oxid_url'])) {
            $sugar_url = $_REQUEST['oxid_url'];
        } else {
            $sugar_url = $this->oxid_url;
        }
        $SugarCrmOxid     = new SoapClient("sugarcrm.wsdl");
        $this->SoapClient = $SugarCrmOxid;
    }

    function check_connection($uss, $pass)
    {
        try {
            $this->Connect();
            $connected = $this->SoapClient->check_connection($uss, $pass);

        } catch (SoapFault $ex) {
            print $ex->getMessage();
            print $ex->getTraceAsString();
        }

        if (is_soap_fault($connected)) {
            return "url_error";
        }
        $this->connected = $connected;

        return $connected;
    }


    function get_users()
    {
        $this->Connect();
        $users = $this->SoapClient->get_users($this->oxid_username, $this->oxid_password);
        $this->users = $users;

        return $users;
    }


    function update_users($update_array)
    {
        $update_array = json_encode($update_array);
        $update_array = base64_encode($update_array);
        $this->Connect();
        $users = $this->SoapClient->update_users($this->oxid_username, $this->oxid_password, $update_array);
        return $users;
    }

    function save_leads()
    {
        $result      = $this->get_users();
        $result      = base64_decode($result);
        $xmlparse    = &new OxidParseXML();
        $xml         = $xmlparse->GetXMLTree($result);
        $user_values = $xml['USERS'][0]['USER_LIST'];
        $user_number = count($user_values);
        $fld_def     = $xml['USERS'][0]['FIELD_ARRAY'][0]['DEFINITION'][0]['VALUE'];
        $fld_def     = json_decode($fld_def);

/////// leads contacts
        foreach ($user_values as $k => $v) {
            //print_r($fld_def);
            $ld                = new OxidLead();
            $ld->order_count_c = 0;
            foreach ($fld_def as $ox_name => $sug_name) {
                $ox_name     = strtoupper($ox_name);
                $sug_name    = strtolower($sug_name);
                $sug_nameBIG = strtoupper($sug_name);
                //echo "<br>sn ".$sug_name." ov ".$v[$ox_name][0]['VALUE']." def ".$ox_name;
                if (isset($v[$sug_nameBIG])) {
                    //$this->$k=utf8_encode($this->$k);
                    $ld->$sug_name = $v[$sug_nameBIG][0]['VALUE'];
                }
            }
            if (isset($v['ALT_ADDRESS_STREET'][0]['VALUE'])) {
                $ld->alt_address_street = $v['ALT_ADDRESS_STREET'][0]['VALUE'];
            }
            if (isset($v['ALT_ADDRESS_CITY'][0]['VALUE'])) {
                $ld->alt_address_city = $v['ALT_ADDRESS_CITY'][0]['VALUE'];
            }
            if (isset($v['ALT_ADDRESS_COUNTRY'][0]['VALUE'])) {
                $ld->alt_address_country = $v['ALT_ADDRESS_COUNTRY'][0]['VALUE'];
            }
            if (isset($v['ALT_ADDRESS_POSTALCODE'][0]['VALUE'])) {
                $ld->alt_address_postalcode = $v['ALT_ADDRESS_POSTALCODE'][0]['VALUE'];
            }
            if (isset($v['PRIMARY_ADDRESS_COUNTRY'][0]['VALUE'])) {
                $ld->primary_address_country = $v['PRIMARY_ADDRESS_COUNTRY'][0]['VALUE'];
            }
            $ld->save();
            //debugl("Oxid.php::save_leads " . $ld->first_name . " " . $ld->last_name . " " . $ld->id."<br>");
        }

        $orders1 = $xml['USERS'][0]['ORDERS'][0]['ITEMS'];
        $orders  = $orders1[0]['BESTELLLISTE'][0]['BESTELLUNG'];

        if (is_array($orders)) {
            foreach ($orders as $k => $v) {
                $oo          = new OxidOpportunity();
                $array_order = $oo->parse_oxid_fields($v);
                $oo->save($array_order);
            }
        }
////// end orders
    }
}

?>