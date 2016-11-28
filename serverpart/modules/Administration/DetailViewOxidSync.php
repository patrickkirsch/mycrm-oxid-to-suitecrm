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


require_once('include/DetailView/DetailView.php');
require_once('include/export_utils.php');
require_once('include/timezone/timezones.php');
require_once('include/utils.php');
#require_once('modules/Users/User.php');
#require_once('modules/Administration/Administration.php');

global $current_user;
global $theme;
global $app_strings;
global $mod_strings;
global $timezones;
 if (file_exists("custom/modules/Users/Ext/Language/en_us.lang.ext.php")){
require_once("custom/modules/Users/Ext/Language/en_us.lang.ext.php");
}
if (file_exists("custom/modules/Users/Ext/Language/ge_ge.lang.ext.php")){
require_once("custom/modules/Users/Ext/Language/ge_ge.lang.ext.php");
}
#if (!is_admin($current_user) && ($_REQUEST['record'] != $current_user->id)) sugar_die("Unauthorized access to administration.");

 $sq = "select * from config where category='info' and name='allow_faxsms_user_change'";
global $db;
$result= $db->query($sq, true);
              while($row = $db->fetchByAssoc($result))
            {
                $aokusers = $row['value'];
           }

   if($aokusers == '1' or $aokusers==true) {   
   }else{
if(!is_admin($current_user) ) sugar_die("Unauthorized access to administration.");
   }

  
   
   
#$focus = new User();
 # echo "aaa";
  #        exit;
$detailView = new DetailView();
$offset=0;



echo "\n<p>\n";
#echo get_module_title($mod_strings['LBL_MODULE_NAME'], $mod_strings['LBL_MODULE_NAME'].": ".$focus->full_name." (".$focus->user_name.")", true);
echo "\n</p>\n";
global $theme;
global $app_list_strings;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');


$GLOBALS['log']->info("User detail view");

$xtpl=new XTemplate ('modules/Administration/DetailViewOxidSync.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);

$xtpl->assign("THEME", $theme);
$xtpl->assign("GRIDLINE", $gridline);
$xtpl->assign("IMAGE_PATH", $image_path);$xtpl->assign("PRINT_URL", "index.php?".$GLOBALS['request_string']);
#$xtpl->assign("ID", $focus->id);
#$xtpl->assign("USER_NAME", $focus->user_name);
#$xtpl->assign("FULL_NAME", $focus->full_name);

$xtpl->assign('FAXSMS_FAX_PREFIX_C', $focus->faxsms_fax_prefix_c);     
$xtpl->assign('FAXSMS_SMS_PREFIX_C', $focus->faxsms_sms_prefix_c);  

$sq = "select * from config where category='info' and name='oxid_url'";
$result= $db->query($sq, true);
$row = $db->fetchByAssoc($result);
//print_r($row);
//print_r($so_send_sugar_to_oxid);
$oxid_url=$row['value'];
$xtpl->assign('OXID_URL', $oxid_url);

  $sq = "select * from config where category='info' and name='oxid_username'";
$result= $db->query($sq, true);
$row = $db->fetchByAssoc($result);
//print_r($so_send_sugar_to_oxid);
$oxid_username=$row['value'];
$xtpl->assign('OXID_USERNAME', $oxid_username);


$sq = "select * from config where category='info' and name='so_send_sugar_to_oxid'";
$result= $db->query($sq, true);
$row = $db->fetchByAssoc($result);
$so_send_sugar_to_oxid=$row['value'];

if ($so_send_sugar_to_oxid==1){
$xtpl->assign('SO_SEND_SUGAR_TO_OXID', "checked");     
}


 $sq = "select * from config where category='info' and name='conflict_resolution'";
$result= $db->query($sq, true);
$row = $db->fetchByAssoc($result);
//print_r($so_send_sugar_to_oxid);
$conflict_resolution=$row['value'];
   
   if($conflict_resolution == 'oxid_wins' ) {
    $xtpl->assign('SO_OXID_WINS', 'CHECKED');
}else{
    $xtpl->assign('SO_SUGAR_WINS', 'CHECKED');
   }
  $sq = "select * from config where category='info' and name='oxid_conflict_email'";
$result= $db->query($sq, true);
$row = $db->fetchByAssoc($result);
$oxid_conflict_email=$row['value'];
$xtpl->assign('CONFLICT_EMAIL', $oxid_conflict_email);

 $sq = "select * from config where category='info' and name='oxid_account_name'";
$result= $db->query($sq, true);
$row = $db->fetchByAssoc($result);
$oxid_account_name=$row['value'];
 $xtpl->assign('DEFAULT_ACCOUNT_NAME', $oxid_account_name); 

  

/////////////5.2->5.5 compat
global $sugar_config;
$sver=$sugar_config['sugar_version'] ;

if (stristr($sver,"5.5")!=""){
$sugar55=true; 
}else{
$sugar55=false;  
}
if (stristr($sver,"6.")!=""){  
$sugar55=true;     
}

if ($sugar55){
    $xtpl->assign('tabDetailView', "detail view");   
    $xtpl->assign('tabDetailViewDL', "scope=\"row\"");  
    $xtpl->assign('tabDetailViewDL1', "");   
  
}else{
    $xtpl->assign('tabDetailView', "tabDetailView");
    $xtpl->assign('tabDetailViewDL', "class=\"tabDetailViewDL\"");    
    $xtpl->assign('tabDetailViewDL1', "class=\"tabDetailViewDL\"");  
}

////////////////////////////  
  
  

#   if($focus->show_fax_button_c == 'on' ) {
#    $xtpl->assign('SHOW_FAX_BUTTON_C_CHECKED', 'CHECKED');
#}
#   if($focus->show_sms_button_c == 'on' ) {
#    $xtpl->assign('SHOW_SMS_BUTTON_C_CHECKED', 'CHECKED');
#}


   if($aokusers == '1' or $aokusers==true) {
    $xtpl->assign('ALLOWUSERS', 'CHECKED');
}

///////////////////////////////////////////////////////////////////////////////
////	TO SUPPORT LEGACY XTEMPLATES
#$xtpl->assign('FIRST_NAME', $focus->first_name);
#$xtpl->assign('LAST_NAME', $focus->last_name);
////	END SUPPORT LEGACY XTEMPLATES
///////////////////////////////////////////////////////////////////////////////


require_once('modules/DynamicFields/templates/Files/DetailView.php');

 # $xtpl->assign('DIMDIM_VAR1_C', $focus->dimdim_var1_c);  
   if($aokusers == '1' or $aokusers==true) {
    $xtpl->assign('ALLOWUSERS', 'CHECKED');
}

   
$xtpl->parse("user_info.tabchooser");


$xtpl->parse("main");
$xtpl->out("main");


$xtpl->parse("user_info.layoutopts");
$xtpl->parse("user_info");
$xtpl->out("user_info");

 

echo "</td></tr>\n";

require_once('modules/SavedSearch/SavedSearch.php');
$savedSearch = new SavedSearch();
$json = getJSONobj();
$savedSearchSelects = $json->encode(array($GLOBALS['app_strings']['LBL_SAVED_SEARCH_SHORTCUT'] . '<br>' . $savedSearch->getSelect('Users')));
$str = "<script>
YAHOO.util.Event.addListener(window, 'load', SUGAR.util.fillShortcuts, $savedSearchSelects);
</script>";
echo $str;

?>
