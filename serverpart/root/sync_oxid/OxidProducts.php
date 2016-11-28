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
if (file_exists("modules/Products/Product.php")) {
    require_once("modules/Products/Product.php");
    $pro_ver    = true;
    $class_name = "Product";

    class OxidP extends Product
    {
    }

}

if (file_exists("modules/MyCRM_Products/MyCRM_Products.php")) {
    require_once("modules/MyCRM_Products/MyCRM_Products.php");
    $pro_ver    = false;
    $class_name = "MyCRM_Products";

    class OxidP extends MyCRM_Products
    {

    }

}

class OxidProduct extends OxidP
{
    var $data = [];

    function check_products($products_data, $opportunity_id)
    {

        $GLOBALS['log']->fatal("products data " . var_export($products_data, true));
        $ac_id = "";
        if (!file_exists("modules/MyCRM_Products/MyCRM_Products.php")) {
            $op                             = new Opportunity();
            $op->disable_row_level_security = true;
            $op->retrieve($opportunity_id);
            $ac_id = $op->account_id;
        }

        foreach ($products_data as $k => $v) {
            $existing_product = $this->check_existing($v, $opportunity_id);
            if ($existing_product['id'] == "") {
                $product_id[] = $this->new_product($v, "", $ac_id);
            } else {
                //$account_id


                $this->new_product($v, $existing_product['id'], $ac_id);
                $product_id[] = $existing_product['id'];
            }

        }

        $GLOBALS['log']->fatal("products id returned " . var_export($product_id, true));

        return $product_id;

    }


    function check_existing($a, $opportunity_id)
    {
        global $db;
        if (file_exists("modules/MyCRM_Products/MyCRM_Products.php")) {
            $sq     = "select mycrm_products.* from mycrm_products left join mycrm_produpportunities_c on mycrm_products.id=mycrm_produpportunities_c.mycrm_produm_products_ida where oxid='" . $a['artikelnr_c'] . "' and mycrm_produpportunities_c.mycrm_produortunities_idb='" . $opportunity_id . "' and mycrm_produpportunities_c.deleted=0 and mycrm_products.deleted=0 limit 0,1";
            $result = $this->db->query($sq);
            $row    = $this->db->fetchByAssoc($result);
        } else {
            $op = new Opportunity();
            $op->retrieve($opportunity_id);
            $rel_name = "opportunities_products";
            $op->load_relationships();
            $prods = $op->$rel_name->get();
            $where = implode("','", $prods);
            $where = "('" . $where . "')";
            $sq    = "select * from products left join products_cstm on id=id_c where oxid_c in " . $where;
            $r     = $db->query($sq);
            $row   = $db->fetchByAssoc($r);

//echo $sq;
//exit;               
        }

        return $row;

    }


    function check_existing_actindo($id, $field, $table, $use_custom)
    {
        if (!file_exists("modules/MyCRM_Products/MyCRM_Products.php")) {
            if ($table == "mycrm_products") {
                $table      = "product_templates";
                $use_custom = "true";
            }

            if ($table == "mycrm_relatedproducts") {
                $table      = "products";
                $use_custom = "true";
            }
        } else {
            if ($field == "actindo_id_c" and $table == "mycrm_products") {
                $use_custom = true;
            }
            if ($field == "actindo_id_c" and $table == "mycrm_relatedproducts") {
                $use_custom = false;
            }

        }
        // echo "<br>".$table." field ".$field;
        global $db;
        $table_cstm = $table . "_cstm";
        if ($use_custom != true) {
            $sq = "select * from $table  where $field='" . $id . "' and deleted=0";
        } else {
            $sq = "select * from $table left join $table_cstm on " . $table . ".id=" . $table_cstm . ".id_c  where $field='" . $id . "' and deleted=0";
        }

        //  exit;
        $r   = $db->query($sq);
        $row = $db->fetchByAssoc($r);

        //echo "<br>\n ".$sq."<br>".print_r($row);
        return $row;

    }


    function new_product($a, $existing_product_id, $account_id)
    {
        $this->id = "";
        if ($existing_product_id != "") {
            $this->id = $existing_product_id;
        }
        $this->name           = $a['name'];
        $this->oxid           = $a['artikelnr_c'];
        $this->oxid_c         = $a['artikelnr_c'];
        $this->list_price     = $a['list_price'];
        $this->list_usdollar  = $a['list_price'];
        $this->discount_price = $a['list_price'];

        $this->account_id = $account_id;

        $this->description = $a['description'];
        $this->quantity    = $a['qty'];
        $this->save();

        return $this->id;
    }


}


?>