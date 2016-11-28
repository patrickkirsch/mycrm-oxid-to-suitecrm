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

global $mod_strings;
global $current_user;

if(is_admin($current_user))
{

    if (($_REQUEST['action']=="DetailView" or $_REQUEST['action']=="EditView") and isset($_REQUEST['record'])){
               $guserid=$_REQUEST['record'];

        $module_menu[] = 
    Array("index.php?module=Users&action=DetailViewOxidSync&return_module=Users&return_action=DetailViewOxidSync&record=$guserid", $mod_strings['SUGAROXID_LINK_EDIT'],"oxid_eshop");

    }else{
               $guserid=$current_user->id;         
         }

    

}

if(!is_admin($current_user))
{
        if (($_REQUEST['action']=="DetailView" or $_REQUEST['action']=="EditView" or $_REQUEST['action']=="DetailViewOxidSync" or $_REQUEST['action']=="EditViewOxidSync") and isset($_REQUEST['record'])){
               $guserid=$_REQUEST['record'];
  $sq = "select * from config where category='info' and name='allow_sugaroxid_user_change'";
global $db;
$result= $db->query($sq, true);
              while($row = $db->fetchByAssoc($result))
            {
                $aokusers = $row['value'];
           }
   if($aokusers == '1' or $aokusers==true) {
        $module_menu[] = 
    Array("index.php?module=Users&action=DetailViewOxidSync&return_module=Users&return_action=DetailViewOxidSync&record=$guserid", $mod_strings['SUGAROXID_LINK_EDIT'],"oxid_eshop");
   }
   }
}



?>