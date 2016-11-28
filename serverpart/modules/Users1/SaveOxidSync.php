<?php
/************************************************

MyCRM OXID-to-Sugar Connector

The Program is provided AS IS, without warranty. You can redistribute it and/or modify it under the terms of the GNU Affero General Public License Version 3 as published by the Free Software Foundation.

For contact:
MyCRM GmbH
Hirschlandstrasse 150
73730 Esslingen
Germany

www.mycrm.de
info@mycrm.de

****************************************************/
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');


require_once('modules/Users/User.php');
require_once('modules/MySettings/TabController.php');

$display_tabs_def = urldecode($_REQUEST['display_tabs_def']);
$hide_tabs_def = urldecode($_REQUEST['hide_tabs_def']);
$remove_tabs_def = urldecode($_REQUEST['remove_tabs_def']);

$DISPLAY_ARR = array();
$HIDE_ARR = array();
$REMOVE_ARR = array();


parse_str($display_tabs_def,$DISPLAY_ARR);
parse_str($hide_tabs_def,$HIDE_ARR);
parse_str($remove_tabs_def,$REMOVE_ARR);

if (isset($_POST['record']) && !is_admin($current_user) && $_POST['record'] != $current_user->id) sugar_die("Unauthorized access to administration.");
elseif (!isset($_POST['record']) && !is_admin($current_user)) echo ("Unauthorized access to user administration.");

$focus = new User();
$focus->disable_row_level_security=true;
$focus->retrieve($_REQUEST['record']);
# print_r($focus);
# exit;
   #print_r($_REQUEST["show_fax_button_c"]);
   #print_r($_REQUEST["show_sms_button_c"]);  
   #exit;



 if(!isset($_REQUEST["allowusers"]))  { 
   $allowusers=0; 
}
if($_REQUEST["allowusers"]=="on")  { 
   $allowusers=1; 
}

global $db;   

#print_r($_REQUEST);
#exit;


  if ($_REQUEST["so_send_sugar_to_oxid"]=="on"){
$so_send_sugar_to_oxid = 1;  
 }else{
$so_send_sugar_to_oxid = 0;   
 }

 #print_r($_REQUEST);
 #exit;

$sq = "update config set value='".$_REQUEST['account_name']."' where category='info' and name='oxid_account_name'";
$result= $db->query($sq, true); 

$sq = "update config set value='".$_REQUEST['account_id']."' where category='info' and name='oxid_account_id'";
$result= $db->query($sq, true);  
 
 
$sq = "update config set value='".$_REQUEST['conflict_email']."' where category='info' and name='oxid_conflict_email'";
$result= $db->query($sq, true); 
 
$sq = "update config set value='$so_send_sugar_to_oxid' where category='info' and name='so_send_sugar_to_oxid'";
$result= $db->query($sq, true);


$_REQUEST['oxid_password']=base64_encode($_REQUEST['oxid_password']);
$sq = "update config set value='".$_REQUEST['oxid_url']."' where category='info' and name='oxid_url'";
$result= $db->query($sq, true);
$sq = "update config set value='".$_REQUEST['oxid_username']."' where category='info' and name='oxid_username'";
$result= $db->query($sq, true);
$sq = "update config set value='".$_REQUEST['oxid_password']."' where category='info' and name='oxid_password'";
$result= $db->query($sq, true);






#echo $sq;
#exit;

$sq = "update config set value='".$_REQUEST['conflict_resolution']."' where category='info' and name='conflict_resolution'";
$result= $db->query($sq, true);



  if(is_admin($current_user)){   
 $sq = "update config set value='$allowusers' where category='info' and name='allow_sugar_oxid_user_change'";
global $db;
$result= $db->query($sq, true);
  }






    foreach($focus->column_fields as $field)
    {
        if(isset($_POST[$field]))
        {
            $value = $_POST[$field];
            $focus->$field = $value;
        }
    }

    foreach($focus->additional_column_fields as $field)
    {
        if(isset($_POST[$field]))
        {
            $value = $_POST[$field];
            $focus->$field = $value;

        }
    }




 $focus->save(); 
 


// Flag to determine whether to save a new password or not.
if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] != "") $return_module = $_REQUEST['return_module'];
else $return_module = "Users";
if(isset($_REQUEST['return_action']) && $_REQUEST['return_action'] != "") $return_action = $_REQUEST['return_action'];
else $return_action = "DetailView";
if(isset($_REQUEST['return_id']) && $_REQUEST['return_id'] != "") $return_id = $_REQUEST['return_id'];

$GLOBALS['log']->debug("Saved record with id of ".$return_id);



if(!isset($_REQUEST["allowusers"]))  { 
   $allowusers=0; 
}
if($_REQUEST["allowusers"]=="on")  { 
   $allowusers=1; 
}



$return_action = "DetailViewOxidSync";
$redirect = "index.php?action={$return_action}&module={$return_module}&record={$focus->id}";
header("Location: {$redirect}");
?>
