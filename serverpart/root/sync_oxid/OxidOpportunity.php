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
 ****************************************************/
class OxidOpportunity extends Opportunity
{
    var $focus;
    var $oxid2sugar = [
        '[\'BESTELLNUMMER\']'              => 'oxid_order_number_c',
        '[\'BESTELLID\']'                  => 'oxid_order_id_c',
        '[\'BESTELLDATUM\'][0][\'DATUM\']' => 'date_created',
        '[\'KUNDE\'][0][\'KUNDENNUMMER\']' => 'oxid_customer_number_c',
        '[\'KUNDE\'][0][\'KUNDEID\']'      => 'oxid_customer_id_c',
        '[\'KUNDE\'][0][\'FIRMENNAME\']'   => 'account_name',
        '[\'CURRENCYRATE\']'               => 'currency_rate_c',
        #'OXID'           => 'oxid_c',
        #'OXID'           => 'oxid_c',
        #'OXID'           => 'oxid_c',

    ];

    var $oxid_product2sugar = [
        'PRODUKTNAME'   => 'name',
        'ARTIKELNUMMER' => 'artikelnr_c',
        'ARTIKELID'     => 'artikelid_c',
        #'ARTIKELID1'           => 'artikelid_c1',
        'PREIS'         => 'list_price',
        'ANZAHL'        => 'qty',
        'DESCRIPTION'   => 'description',
        #'PRODUKTNAME'           => 'name',
    ];


    function save($a)
    {
        $p = new OxidProduct();

        $p->data = $a['products'];

        $this->array2object($a);
        $existing = $this->check_existing($a);
        if ($existing['id_c'] != "") {
            $this->id = $existing['id_c'];
        }
        $this->name = $this->oxid_order_number_c;
        if ($a['contact_id'] != "") {
            $_REQUEST['relate_to'] = "Contacts";
            $_REQUEST['relate_id'] = $a['contact_id'];
        }
        parent::save();
        if ($a['lead_id'] != "") {
            $_REQUEST['relate_to'] = "Leads";
            $_REQUEST['relate_id'] = $a['lead_id'];
            parent::save();
        }

        $products_array = $p->check_products($a['products'], $this->id);

        if (file_exists("modules/MyCRM_Products/MyCRM_Products.php")) {
            foreach ($products_array as $k => $v) {
                $_REQUEST['relate_to'] = "MyCRM_Products";
                $_REQUEST['relate_id'] = $v;
                parent::save();
                $_REQUEST['relate_to'] = "Products";
                $_REQUEST['relate_id'] = $v;
                parent::save();

            }
        } else {
            foreach ($products_array as $k => $v) {
                $rel_name = "opportunities_products";
                $this->load_relationship($rel_name);
                $this->$rel_name->add($v);
            }
        }

        $sq = "update opportunities set date_closed='" . $a['date_closed'] . "',date_entered='" . $a['date_created'] . "' where id='" . $this->id . "'";
        $this->db->query($sq);

    }

    function array2object($a)
    {
        $fld_def = $this->field_name_map;
        foreach ($fld_def as $k => $v) {

            if (isset($a[$k])) {
                $this->$k = $a[$k];
            }
        }
    }

    function check_existing($a)
    {
        $sq     = "select * from opportunities_cstm where oxid_order_id_c='" . $a['oxid_order_id_c'] . "' limit 0,1";
        $result = $this->db->query($sq);
        $row    = $this->db->fetchByAssoc($result);

        return $row;
    }

    function newOpportunity()
    {
        $opp         = new Opportunity();
        $this->focus = $opp;
    }

    function parse_oxid_fields($v)
    {

        foreach ($this->oxid2sugar as $ox_name => $sug_name) {
            $ox_name     = strtoupper($ox_name);
            $sug_name    = strtolower($sug_name);
            $sug_nameBIG = strtoupper($sug_name);
            $eval_arg    = "return  \$v" . $ox_name . "[0]['VALUE'];";

            $arr[$sug_name] = eval($eval_arg);
        }
        $account                = $this->fetch_account($arr['oxid_customer_id_c']);
        $contact                = $this->fetch_contact($arr['oxid_customer_id_c']);
        $lead                   = $this->fetch_lead($arr['oxid_customer_id_c']);
        $arr['lead_id']         = $lead['id'];
        $arr["account_id"]      = $account->id;
        $arr["contact_id"]      = $contact->id;
        $arr['products']        = $this->parse_oxid_products($v);
        $arr['amount']          = $this->get_order_total($arr['products']);
        $arr['amount_usdollar'] = $arr['amount'] / $v['CURRENCYRATE'][0]['VALUE'];
        $arr['sales_stage']     = "Closed Won";
        $arr['date_closed']     = $arr['date_created'];
        $arr['currency_id']     = $this->get_currency_id($v['CURRENCY'][0]['VALUE']);


        return $arr;
    }

    function get_currency_id($cs)
    {
        $sq     = "SELECT * FROM currencies where iso4217='" . $cs . "'";
        $result = $this->db->query($sq);
        $row    = $this->db->fetchByAssoc($result);
        if ($row['id'] != "") {
            return $row['id'];
        } else {
            return -99;
        }
    }

    function get_order_total($a)
    {
        $total = 0;
        foreach ($a as $k => $v) {
            $total = $total + $v['list_price'] * $v['qty'];
        }

        return $total;
    }

    function parse_oxid_products($v)
    {
        $art            = $v['ARTIKELLISTE'][0]['ARTIKEL'];
        $count_products = count($v['ARTIKELLISTE']);
        foreach ($art as $k => $val) {
            $arr_prod[] = $this->parse_order_products($val);
            //}
        }

        return $arr_prod;
    }

    function parse_order_products($v)
    {


        foreach ($this->oxid_product2sugar as $ox_name => $sug_name) {
            $ox_name     = strtoupper($ox_name);
            $sug_name    = strtolower($sug_name);
            $sug_nameBIG = strtoupper($sug_name);
            if (isset($v[$ox_name])) {
                $arr[$sug_name] = $v[$ox_name][0]['VALUE'];
            }
        }


        return $arr;
    }

    function fetch_lead($cid)
    {
        global $db;
        $sq     = "select * from leads_cstm left join leads on leads_cstm.id_c=leads.id where oxid_c='" . $cid . "'";
        $result = $db->query($sq);
        $row    = $db->fetchByAssoc($result);

        return $row;
    }

    function fetch_account($cid)
    {
        $lead = $this->fetch_lead($cid);


        global $oxid_account_id, $oxid_account_name;
        if ($lead['account_id'] == "") {
            $account                             = new Account();
            $account->disable_row_level_security = true;
            $account->retrieve($oxid_account_id);

            return $account;

        }


        if ($lead['account_id'] != "") {
            $account                             = new Account();
            $account->disable_row_level_security = true;
            $account->retrieve($lead['account_id']);

            return $account;
        } else {
            return false;
        }
    }

    function fetch_contact($cid)
    {
        $lead = $this->fetch_lead($cid);
        if ($lead['contact_id'] != "") {
            $contact                             = new Contact();
            $contact->disable_row_level_security = true;
            $contact->retrieve($lead['contact_id']);

            return $contact;
        } else {
            return false;
        }
    }

}

?>