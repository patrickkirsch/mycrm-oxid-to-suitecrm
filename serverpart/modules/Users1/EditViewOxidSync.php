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


require_once('XTemplate/xtpl.php');

require_once('include/export_utils.php');
require_once('modules/Users/User.php');
require_once('modules/Users/Forms.php');
require_once('modules/Users/UserSignature.php');
require_once('modules/Administration/Administration.php');
require_once('include/javascript/javascript.php');   

$admin = new Administration();
$admin->retrieveSettings("notify");

global $app_strings;
global $app_list_strings;
global $mod_strings;
  if (file_exists("custom/modules/Users/Ext/Language/en_us.lang.ext.php")){
require_once("custom/modules/Users/Ext/Language/en_us.lang.ext.php");
}
if (file_exists("custom/modules/Users/Ext/Language/ge_ge.lang.ext.php")){
require_once("custom/modules/Users/Ext/Language/ge_ge.lang.ext.php");
}
$admin = new Administration();
$admin->retrieveSettings();

global $db;


$sq = "select * from config where category='info' and name='so_send_sugar_to_oxid'";
$result= $db->query($sq, true);
$row = $db->fetchByAssoc($result);
$so_send_sugar_to_oxid=$row['value'];
     if  ($db->getRowCount($result)==0){
        $sq = "insert into config (category,name,value) values ('info','so_send_sugar_to_oxid',1)";
  $result= $db->query($sq, 1);   
$so_send_sugar_to_oxid=1;
     }    
     
$sq = "select * from config where category='info' and name='oxid_account_name'";
$result= $db->query($sq, true);
$row = $db->fetchByAssoc($result);
$oxid_account_name=$row['value'];
     if  ($db->getRowCount($result)==0){
        $sq = "insert into config (category,name,value) values ('info','oxid_account_name','')";
  $result= $db->query($sq, 1);   
$oxid_account_name="";
     }           

$sq = "select * from config where category='info' and name='oxid_account_id'";
$result= $db->query($sq, true);
$row = $db->fetchByAssoc($result);
$oxid_account_id=$row['value'];
     if  ($db->getRowCount($result)==0){
        $sq = "insert into config (category,name,value) values ('info','oxid_account_id','')";
  $result= $db->query($sq, 1);   
$oxid_account_id="";
     }       
     
     
$sq = "select * from config where category='info' and name='oxid_conflict_email'";
$result= $db->query($sq, true);
$row = $db->fetchByAssoc($result);
$oxid_conflict_email=$row['value'];
     if  ($db->getRowCount($result)==0){
        $sq = "insert into config (category,name,value) values ('info','oxid_conflict_email','')";
  $result= $db->query($sq, 1);   
$oxid_conflict_email="";
     }  
     
     
     
     

$sq = "select * from config where category='info' and name='conflict_resolution'";
$result= $db->query($sq, true);
$row = $db->fetchByAssoc($result);
//print_r($so_send_sugar_to_oxid);
$conflict_resolution=$row['value'];
     if  ($db->getRowCount($result)==0){
        $sq = "insert into config (category,name,value) values ('info','conflict_resolution','sugar_wins')";
  $result= $db->query($sq, 1);   
$conflict_resolution='sugar_wins';
     }        

     
$sq = "select * from config where category='info' and name='oxid_url'";
$result= $db->query($sq, true);
$row = $db->fetchByAssoc($result);
//print_r($row);
//print_r($so_send_sugar_to_oxid);
$oxid_url=$row['value'];
//echo "<br>count".$db->getRowCount($result);
     if  ($db->getRowCount($result)==0){
        $sq = "insert into config (category,name,value) values ('info','oxid_url','')";
  $result= $db->query($sq, 1);   
$oxid_url='';
     }         

$sq = "select * from config where category='info' and name='oxid_username'";
$result= $db->query($sq, true);
$row = $db->fetchByAssoc($result);
//print_r($so_send_sugar_to_oxid);
$oxid_username=$row['value'];
     if  ($db->getRowCount($result)==0){
        $sq = "insert into config (category,name,value) values ('info','oxid_username','')";
  $result= $db->query($sq, 1);   
$oxid_username='';
     }         

$sq = "select * from config where category='info' and name='oxid_password'";
$result= $db->query($sq, true);
$row = $db->fetchByAssoc($result);
//print_r($so_send_sugar_to_oxid);
$oxid_password=$row['value'];
$oxid_password=base64_decode($oxid_password);
     if  ($db->getRowCount($result)==0){
        $sq = "insert into config (category,name,value) values ('info','oxid_password','')";
  $result= $db->query($sq, 1);   
$oxid_password='';
     }         
     

$sq = "select * from config where category='info' and name='allow_sugar_oxid_user_change'";
global $db;
$result= $db->query($sq, true);
              while($row = $db->fetchByAssoc($result))
            {
                $aokusers = $row['value'];
           }
     if  ($db->getRowCount($result)==0){
        $sq = "insert into config (category,name,value) values ('info','allow_sugar_oxid_user_change',1)";
  $result= $db->query($sq, 1);   
  $aokusers = true;        
         }           

 
         
         
   if($aokusers == '1' or $aokusers==true) {   
   }else{
       ##&& $_REQUEST['record'] != $current_user->id
if(!is_admin($current_user) ) sugar_die("Unauthorized access to administration.");
   }



           
$focus = new User();


if(isset($_REQUEST['record'])) {
    $focus->disable_row_level_security=true;      
    $focus->retrieve($_REQUEST['record']);
}
if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
	$focus->id = "";
	$focus->user_name = "";
}
echo "\n<p>\n";
echo get_module_title($mod_strings['LBL_MODULE_NAME'], $mod_strings['LBL_MODULE_NAME'].": ".$focus->first_name." ".$focus->last_name."(".$focus->user_name.")", true);
echo "\n</p>\n";
global $theme;
$theme_path='themes/'.$theme.'/';
$image_path=$theme_path.'images/';
require_once($theme_path.'layout_utils.php');

$GLOBALS['log']->info('User edit view');
$xtpl=new XTemplate('modules/Users/EditViewOxidSync.html');
$xtpl->assign('MOD', $mod_strings);
$xtpl->assign('APP', $app_strings);

if(isset($_REQUEST['error_string'])) $xtpl->assign('ERROR_STRING', '<span class="error">Error: '.$_REQUEST['error_string'].'</span>');
if(isset($_REQUEST['return_module'])) $xtpl->assign('RETURN_MODULE', $_REQUEST['return_module']);
if(isset($_REQUEST['return_action'])) $xtpl->assign('RETURN_ACTION', "DetailViewDimdim");
if(isset($_REQUEST['return_id'])) $xtpl->assign('RETURN_ID', $_REQUEST['return_id']);
else { $xtpl->assign('RETURN_ACTION', 'ListView'); }

$xtpl->assign('JAVASCRIPT', get_set_focus_js().user_get_validate_record_js().user_get_chooser_js().user_get_confsettings_js().'<script type="text/javascript" language="Javascript" src="modules/Users/User.js"></script>');
$xtpl->assign('IMAGE_PATH', $image_path);$xtpl->assign('PRINT_URL', 'index.php?'.$GLOBALS['request_string']);
$xtpl->assign('ID', $focus->id);
$xtpl->assign('USER_NAME', $focus->user_name);


//$oxid_account_id
$xtpl->assign('DEFAULT_ACCOUNT_NAME', $oxid_account_name); 
$xtpl->assign('DEFAULT_ACCOUNT_ID', $oxid_account_id);   
$xtpl->assign('CONFLICT_EMAIL', $oxid_conflict_email);   

$xtpl->assign('OXID_URL', $oxid_url);       
$xtpl->assign('OXID_USERNAME', $oxid_username); 
$xtpl->assign('OXID_PASSWORD', $oxid_password); 

   if($conflict_resolution == 'oxid_wins' ) {
    $xtpl->assign('SO_OXID_WINS', 'CHECKED');
}else{
    $xtpl->assign('SO_SUGAR_WINS', 'CHECKED');
}

   if($so_send_sugar_to_oxid == 1 ) {
    $xtpl->assign('SO_SEND_SUGAR_TO_OXID', 'CHECKED');
}









   if($aokusers == '1' or $aokusers==true) {
    $xtpl->assign('ALLOWUSERS', 'CHECKED');
}
if(!is_admin($current_user)){
    $xtpl->assign('ALLOWUSERS_STATUS', 'disabled');   
}  


 
   $xtpl->parse('main');  
$xtpl->out('main');
    
                                 



  
                



      


?>
