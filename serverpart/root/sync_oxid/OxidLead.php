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
class OxidLead extends Lead
{

    var $conflict_except = ["name"                    => true,
                            "date_entered"            => true,
                            "date_modified"           => true,
                            "full_name"               => true,
                            "do_not_call"             => true,
                            "invalid_email"           => true,
                            "email_opt_out"           => true,
                            "converted"               => true,
                            "status"                  => true,
                            "account_id"              => true,
                            "opportunity_id"          => true,
                            "contact_id"              => true,
                            "opportunity_name"        => true,
                            "oxid_c_last_change_c"    => true,
                            "modified_by_name"        => true,
                            "modified_user_id"        => true,
                            "oxidsugar_last_change_c" => true,
                            "report_to_name"          => true,
                            "user_sync"               => true,
                            "id"                      => true,
                            "portal_active"           => true,
    ];
    //portal_active

    var $lead_data;
    var $conflicts;
    var $changed_in_oxid = false;
    var $changed_in_sugar = false;
    var $has_conflicts = false;
    var $so_send_sugar_to_oxid = false;
    var $conflict_resolution = "sugar_wins";
    var $possible_duplicate = false;
    var $duplicate_object;
    var $updated_oxid;
    var $lead_contact_id;
    var $changed_in_sugar_and_oxid = false;

    function convert_lead_to_contact($contact_id)
    {
        $ct = new Contact();
        if ($contact_id != "") {
            $ct->disable_row_level_security = true;
            $ct->retrieve($contact_id);
        }
        $this->lead_contact_id = $ct->id;
        foreach ($this->lead_data->field_name_map as $k => $v) {
            $ct->$k = $this->lead_data->$k;

        }
        $ct->id                = $contact_id;
        $_REQUEST['relate_to'] = "Leads";
        $_REQUEST['relate_id'] = $this->id;
        if ($contact_id == "") {
            $ct->id = "";
        }
        debugl("\n<br> convert to contact " . $contact_id);
        $ct->save();
        $query = "UPDATE leads set converted='1', contact_id='$ct->id',status='Converted' where  id='$this->id' and deleted=0";
        $this->db->query($query, true, "Error converting lead: ");

// $sq = "update contacts_cstm set oxid_last_change_c='".$this->oxid_last_change_c."' ,oxidsugar_last_change_c=(select date_modified from contacts where id='".$ct->id."' and deleted=0) where id_c='".$ct->id."' ";
// $sq = "update contacts_cstm set oxid_last_change_c='".$this->oxid_last_change_c."'  where id_c='".$ct->id."' ";
// echo "<br>".$sq;
// $this->db->query($sq);

    }

    function convert_lead_to_account()
    {
        global $oxid_account_id, $oxid_account_name;
        if ($this->lead_data->account_name != "") {
            $ct = new Account();

            foreach ($this->lead_data->field_name_map as $k => $v) {
                $ct->$k = $this->lead_data->$k;

            }
            $ct->name              = $this->lead_data->account_name;
            $_REQUEST['relate_to'] = "Leads";
            $_REQUEST['relate_id'] = $this->id;
            $ct->id                = "";
            $ct->save();
            $account_id                     = $ct->id;
            $ld                             = new Lead();
            $ld->disable_row_level_security = true;
            $ld->retrieve($this->id);

            $_REQUEST['relate_to'] = "Contacts";
            $_REQUEST['relate_id'] = $ld->contact_id;
            //      echo "\n<br>relate to cts ".$ld->contact_id;
            $ct->save();
            $contact                             = new Contact();
            $contact->disable_row_level_security = true;
            $contact->retrieve($ld->contact_id);
            $contact->account_id   = $ct->id;
            $contact->account_name = $ct->name;
            $contact->save();
        } else {
            $account_id = $oxid_account_id;
        }

        $query = "UPDATE leads set account_id='$account_id',status='Converted' where  id='$this->id' and deleted=0";
        $this->db->query($query, true, "Error converting lead: ");

    }

    function check_existing()
    {
        global $db;

        $sq     = "select count(*) as tc,id_c from leads_cstm where oxid_c='" . $this->oxid_c . "' group by id_c";
        $result = $db->query($sq);
        $row    = $db->fetchByAssoc($result);
        if ($row['tc'] > 0) {
            $this->id = $row['id_c'];
            $this->check_conflict();
        } else {
            $sq     = "select * from email_addresses left join email_addr_bean_rel on email_address.id=email_addr_bean_rel.email_address_id where email_address='" . $this->email1 . "' and email_address.deleted=0 limit 0,1";
            $result = $db->query($sq);
            $row    = $db->fetchByAssoc($result);
            if ($row['bean_module'] != "") {

                if ($row['bean_module'] == "Contacts") {
                    $obj = new Contact();
                }
                if ($row['bean_module'] == "Accounts") {
                    $obj = new Account();
                }
                if ($row['bean_module'] == "Leads") {
                    $obj = new Lead();
                }
                $obj->disable_row_level_security = true;
                $obj->retrieve($row['bean_id']);
                $this->possible_duplicate = true;
                $this->duplicate_object   = $this;
            }

        }
    }

    function check_conflict()
    {
        $lead_existing                             = new Lead();
        $lead_existing->disable_row_level_security = true;
        $lead_existing->retrieve($this->id);
        if ($lead_existing->contact_id == "") {
            $this->old_lead_values = $lead_existing;
            debugl("<br>\n existing lead ");
        } else {
            debugl("<br>\n existing contact ");
            $contact_existing                             = new Contact();
            $contact_existing->disable_row_level_security = true;
            $contact_existing->retrieve($lead_existing->contact_id);
            $this->old_lead_values = $contact_existing;
        }
        $this->new_lead_values = $this;
        $this->compare_fields();
        $this->solve_conflicts();
    }

    function compare_fields()
    {
        $conflict_array  = [];
        $this->conflicts = $conflict_array; //turn off conflict
        return false;//turn off conflict
        foreach ($this->field_name_map as $k => $v) {
            if ($v['type'] != "relate" and $v['type'] != "link" and !isset($v['custom_module'])) {
                $this->$k = utf8_encode($this->$k);

                if ($this->old_lead_values->$k != $this->$k and ($this->old_lead_values->$k != "" or $this->$k != "")) {

                    if (!isset($this->conflict_except[$k])) {
                        if (isset($this->$k) and isset($this->old_lead_values->$k)) {
                            debugl("\n" . $k);
                            if ($k != "email1" and $this->$k != "admin" and $k != "team_id" and $k != "team_set_id" and $k != "salutation") {  /// skip this conflict .. admin user from oxid - sugar does not accept wrong email addresses
                                $conflict_array[] = ["field_name" => $k, "old_value" => $this->old_lead_values->$k, "new_value" => $this->$k];
                            }
                        }
                    }
                }
            }
        }
        if (count($conflict_array) > 0) {
            $this->check_conflict_part();
        }
        $this->conflicts = $conflict_array;


    }

    function compare_fields11()
    {
        $conflict_array = [];
        foreach ($this->field_name_map as $k => $v) {
            if ($v['type'] != "relate" and $v['type'] != "link" and !isset($v['custom_module'])) {
                // echo "<br>tk1 ".$this->$k;

                $this->$k = utf8_encode($this->$k);

                if ($this->old_lead_values->$k != utf8_decode($this->$k) and ($this->old_lead_values->$k != "" or $this->$k != "")) {
                    //      echo "<br>tk2 ".utf8_decode($this->old_lead_values->$k)." ov ".$this->$k." ov2 ".utf8_encode($this->old_lead_values->$k);
                    if (!isset($this->conflict_except[$k])) {
                        if (isset($this->$k) and isset($this->old_lead_values->$k)) {
                            debugl("\n" . $k);
                            if ($k != "email1" and $this->$k != "admin") {  /// skip this conflict .. admin user from oxid - sugar does not accept wrong email addresses
                                $conflict_array[] = ["field_name" => $k, "old_value" => $this->old_lead_values->$k, "new_value" => utf8_decode($this->$k)];
                            }
                        }
                    }
                }
            }
        }
        if (count($conflict_array) > 0) {
            $this->check_conflict_part();
        }
        $this->conflicts = $conflict_array;


    }

    function solve_conflicts()
    {

        return false; //turn off conflict
// 1.sugar wins -> do not update Sugar BUT update OXID
// 2.oxid wins -> update Sugar 
        if (count($this->conflicts) > 0) {
            $this->has_conflicts = true;

        }
//echo "\n<br>hc ".$this->has_conflicts." end\n";
        global $db;

        $sq                    = "select * from config where category='info' and name='so_send_sugar_to_oxid'";
        $result                = $db->query($sq, true);
        $row                   = $db->fetchByAssoc($result);
        $so_send_sugar_to_oxid = $row['value'];

        if ($so_send_sugar_to_oxid == 1) {
            $this->so_send_sugar_to_oxid = true;
        }

        $sq                        = "select * from config where category='info' and name='conflict_resolution'";
        $result                    = $db->query($sq, true);
        $row                       = $db->fetchByAssoc($result);
        $conflict_resolution       = $row['value'];
        $this->conflict_resolution = $conflict_resolution;


        //oxid_wins
        if ($conflict_resolution == "oxid_wins") {
            $sugar_conflict_wins = false;
        } else {
            $sugar_conflict_wins = true;
        }

//debugl( "\n<br><br><br>this has conflicts".$this->has_conflicts."  ".print_r($this->conflicts,true)."<br><br><br>\n");

        if ($this->has_conflicts == true) {
            if ($sugar_conflict_wins == true) {
                $this->solve_conflict_sugar_wins();
            }

            if ($sugar_conflict_wins == false) {
                $this->solve_conflict_oxid_wins();
            }


        }

    }

    function solve_conflict_sugar_wins()
    {   // case 1
        global $db;
        // debugl( "\n<br>conflict sugar wins".print_r($this->id,true));
        $l                             = new Lead();
        $l->disable_row_level_security = true;
        $l->retrieve($this->id);
        //debugl( "\n<br>contact id ".print_r($l->contact_id,true));
        if ($l->contact_id != "") {
            //lead is converted / leave the lead unchanged and check the contact
        }
        foreach ($this->conflicts as $k => $v) {
            $this->$v['field_name'] = $v['old_value'];
        }
        $_REQUEST['conflicts_to_sugar'][] = [
            'conflicts' => $this->conflicts,
            'oxid_id'   => $this->oxid_c,
        ];
        if ($this->so_send_sugar_to_oxid == true) {

            $oxid_url          = get_conf_val('oxid_url');
            $oxid_username     = get_conf_val('oxid_username');
            $oxid_password     = get_conf_val('oxid_password');
            $ox                = new oxid();
            $ox->oxid_url      = $oxid_url;
            $ox->oxid_username = $oxid_username;
            $ox->oxid_password = base64_decode($oxid_password);

//echo "<br>".$this->id;    
            //print_r($_REQUEST['conflicts_to_sugar']);
            $upd = $ox->update_users($_REQUEST['conflicts_to_sugar']);
            // echo "<br>res upd ".$upd;

            $upd = base64_decode($upd);

            $upd = json_decode($upd);
//    print_r($upd);
            //   exit;
            $this->updated_oxid    = $upd;
            $this->lead_contact_id = $l->contact_id;


// $sq = "select * from contacts_cstm  where id_c='".$l->contact_id."' "; 
// $r=$db->query($sq);
// $a=$db->fetchByAssoc($r);

            //print_r($a);
            //exit;
        }
        $this->create_conflict_note($l);
    }

    function put_last_change()
    {
        global $db;
        if ($this->updated_oxid->OXSLASTUPDATE != "") {
            if ($this->lead_contact_id != "") {
                $sq = "update contacts_cstm set oxid_c_last_change_c='" . $this->updated_oxid->OXSLASTUPDATE . "'  where id_c='" . $this->lead_contact_id . "' ";
            } else {
                $sq = "update leads_cstm set oxid_c_last_change_c='" . $this->updated_oxid->OXSLASTUPDATE . "'  where id_c='" . $this->id . "' ";
            }
//    echo "<br>\n".$sq;
            $db->query($sq);
        }
    }

    function get_last_change($id, $table)
    {
        global $db;
        $sq = "select * from " . $table . "  where id_c='" . $id . "' ";
        $r  = $db->query($sq);
        $a  = $db->fetchByAssoc($r);

        return $a;
    }

    function solve_conflict_oxid_wins()
    {   // case 2
        $l                             = new Lead();
        $l->disable_row_level_security = true;
        $l->retrieve($this->id);
        //  debugl( "\n<br>contact id ".print_r($l->contact_id,true));
        if ($l->contact_id != "") {
        }
        foreach ($this->conflicts as $k => $v) {
            $this->$v['field_name'] = $v['new_value'];
        }
        $this->create_conflict_note($l);
    }

    function create_conflict_note($l)
    {
        if ($this->changed_in_sugar_and_oxid == true) {
            require_once('include/upload_file.php');
            $upath = new UploadFile("");

            $n       = new Note();
            $n->name = "Conflict detected";
            $fhtml   = "<table width=500><tr><td width=200>Field name<td  width=150>Old value<td  width=150>New value</tr>";
            foreach ($this->conflicts as $k => $v) {
                $fhtml .= "
<tr><td>" . $v['field_name'] . "
<td>" . $v['old_value'] . " 
<td>" . $v['new_value'] . " 
";
            }
            $fhtml .= "</table>";
            $n->filename       = "conflict_description.html";
            $n->file_mime_type = "text/html";
            $n->contact_id     = $l->contact_id;

            unset($_REQUEST['relate_id']);
            $n->parent_id   = $this->id;
            $n->parent_type = "Leads";


            $n->save();
            $path = "cache/upload" . $n->id;
            $path = clean_path($upath->get_upload_path($n->id));
            debugl("\n<br>upload path to write " . $path . "  contact id" . $l->contact_id);
            $fp = fopen($path, "w+");
            fwrite($fp, $fhtml);
            fclose($fp);
//}
            $_REQUEST['conflicts_array'][] = $fhtml;
        }
    }


    function create_duplicate_note($l)
    {
        global $sugar_config;
        if ($this->possible_duplicate == true) {
            require_once('include/upload_file.php');
            $upath = new UploadFile("");

            $n              = new Note();
            $n->name        = "Possible duplicate detected";
            $description    = "
Title:  " . $this->duplicate_object->title . "    
First Name: " . $this->duplicate_object->first_name . "
Last Name: " . $this->duplicate_object->last_name . "
Company: " . $this->duplicate_object->account_name . "
Street, StreetNo: " . $this->duplicate_object->primary_address_street . "
Postal Code, City:    " . $this->duplicate_object->primary_address_postalcode . "," . $this->duplicate_object->primary_address_city . "
VAT ID No.: " . $this->duplicate_object->vat_id_c . "    
Additional Info: " . $this->duplicate_object->description . "    
Country: " . $this->duplicate_object->primary_address_country . "
Phone: " . $this->duplicate_object->phone_work . "
Fax: " . $this->duplicate_object->phone_fax . " 
Mobile Phone: " . $this->duplicate_object->phone_mobile . " 
Evening Phone: " . $this->duplicate_object->phone_home . " 
Birthdate: " . $this->duplicate_object->birthdate . "

";
            $n->description = $description;


            $url   = $sugar_config['site_url'] . "index.php?module=" . $this->duplicate_object->object_names . "&action=DetailView&record=" . $this->duplicate_object->id . "";
            $fhtml = "<table  width=500><tr><td  width=200>Name<td  width=150>Email<td  width=150>URL</tr>";
            foreach ($this->conflicts as $k => $v) {
                $fhtml .= "
<tr><td>" . $this->duplicate_object->name . "
<td>" . $this->duplicate_object->email1 . " 
<td>" . $url . " 
";
            }
            $fhtml .= "</table>";

            $n->filename       = "duplicate_description.html";
            $n->file_mime_type = "text/html";
            $n->contact_id     = $l->contact_id;


            $n->parent_id   = $this->id;
            $n->parent_type = "Leads";


            $n->save();
            $path = "cache/upload" . $n->id;
            $path = clean_path($upath->get_upload_path($n->id));
            debugl("\n<br>upload path to write " . $path . "  contact id" . $l->contact_id);
            $fp = fopen($path, "w+");
            fwrite($fp, $fhtml);
            fclose($fp);
//}
            $_REQUEST['duplicates_array'][] = $fhtml;

        }
    }


    function check_conflict_part()
    {
        $ld                             = new Lead();
        $ld->disable_row_level_security = true;
        $ld->retrieve($this->id);
        if ($ld->contact_id != "") {
            $lc = $this->get_last_change($ld->contact_id, "contacts_cstm");
        } else {
            $lc = $this->get_last_change($ld->id, "leads_cstm");
        }
        // echo "<br>last change".print_r($lc);
        // exit;
        $this->old_lead_values->oxid_c_last_change_c = $lc['oxid_c_last_change_c'];

//    echo "<br>\n oxlch 0 ".$this->old_lead_values->oxid_c_last_change_c." 2 ".$this->oxid_c_last_change_c;

        if ($this->old_lead_values->oxid_c_last_change_c != $this->oxid_c_last_change_c) {
            //    echo "<br>\n oxlch 0 ".$this->old_lead_values->oxid_c_last_change_c." 2 ".$this->oxid_c_last_change_c;

            $this->changed_in_oxid = true;
        }
//   echo "<br>\n oxlch 1 ".$this->old_lead_values->oxidsugar_last_change_c." 2 ".$this->old_lead_values->date_modified;  
        if ($this->old_lead_values->oxidsugar_last_change_c != $this->old_lead_values->date_modified) {
            //   echo "<br>\n oxlch 1 ".$this->old_lead_values->oxidsugar_last_change_c." 2 ".$this->old_lead_values->date_modified;

            $this->changed_in_sugar = true;
        }

        //and count($this->conflicts)>0
        if ($this->changed_in_oxid == true and $this->changed_in_sugar == true) {
            //$this->has_conflicts=true;
            $this->changed_in_sugar_and_oxid = true;
        }
//   echo "\n<br>has conf a ".$this->changed_in_oxid." b ".$this->changed_in_sugar." c ".count($this->conflicts)." d ".$this->changed_in_sugar_and_oxid." end has confl\n";
    }

    function save()
    {
        $upd_date = $this->oxid_c_last_change_c;

        if ($this->order_count_c > 0) {
            $has_orders = true;
        } else {
            $has_orders = false;
        }

        $this->check_existing();
        $this->lead_data = $this;

        $ldd                             = new Lead();
        $ldd->disable_row_level_security = true;
        $ldd->retrieve($this->id);
        debugl(sprintf("<br>\n save:: %s, %s<br> ",$this->id, $ldd->contact_id));
        if ($ldd->contact_id == "") {    // if is not converted update it

            if ($this->id == "") {
                foreach ($this->field_name_map as $k => $v) {
                    $this->$k = utf8_encode($this->$k);
                }
            }

            parent::save();
            $sq = "update leads_cstm set oxid_c_last_change_c='" . $upd_date . "' ,oxidsugar_last_change_c=(select date_modified from leads where id='" . $this->id . "' and deleted=0) where id_c='" . $this->id . "' ";
//            echo "<br>OxidLead::save lm ".$sq;

            global $db;
            $db->query($sq);
            $this->put_last_change();

            $ld                             = new Lead();
            $ld->disable_row_level_security = true;
            $ld->retrieve($this->id);
            $this->lead_data     = $ld;
            $this->lead_data->id = $this->lead_data->id;
        } else {
            $ld                             = new Lead();
            $ld->disable_row_level_security = true;
            $ld->retrieve($this->id);
        }

        if ($has_orders == true) {
            $this->convert_lead_to_contact($ld->contact_id);
            $this->put_last_change();

            if ($ld->account_id == "" and $ld->account_name != "") {
                $this->convert_lead_to_account();
            }

        }

//        debugl("<br>Exit save<br>");
    }
}

?>