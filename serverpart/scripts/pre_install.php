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
if(!defined('sugarEntry'))define('sugarEntry', true);
function pre_install() {

   require_once('include/utils.php');
#   check_logic_hook_file("Users", "before_save", array(2050, "LimitUsers",  
#      "custom/modules/Users/UsersLimit.php", "LimitUsers", "LimitUsers"));

   
      
}
?>