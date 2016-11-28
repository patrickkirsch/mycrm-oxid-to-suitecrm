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
/*
 * Modifications for Oxid 5.2.6 and SuiteCRM
 * 2016, Patrick Kirsch <pk@wellonga.de>
 *
 */
//error_reporting(E_ALL ^ E_NOTICE);
//ini_set('display_errors', 1);
#error_reporting("E_NONE");     
ini_set('max_execution_time', '36000');
define('sugarEntry', 'index.php');
require_once("data/SugarBean.php");
require_once('include/MVC/SugarApplication.php');
require_once('config.php');
require_once('include/database/PearDatabase.php');
require_once("include/TimeDate.php");
require_once("modules/Users/User.php");
require_once("modules/Tasks/Task.php");
require_once("modules/Accounts/Account.php");
require_once("modules/Contacts/Contact.php");
require_once("modules/Leads/Lead.php");
require_once("modules/Administration/Administration.php");
#include_once('sugar_version.php'); 

$td = new TimeDate();
global $server, $sugar_config, $system_config;

$startTime = microtime();
require_once('include/entryPoint.php');
require_once('include/MVC/SugarApplication.php');
$app = new SugarApplication();
$app->startSession();
require_once("sync_oxid/clsParseXML.php");
require_once("sync_oxid/MyCurl.php");
include_once('sync_oxid/OxidOpportunity.php');
include_once('sync_oxid/OxidLead.php');
include_once('sync_oxid/Oxid.php');
include_once('sync_oxid/OxidProducts.php');
include_once('sync_oxid/OxidSendAlertEmail.php');

$_REQUEST['duplicates_array'] = [];
$_REQUEST['conflicts_array']  = [];


global $db;
$sq = "ALTER TABLE `contacts_cstm` MODIFY COLUMN `oxid_last_change_c` DATETIME DEFAULT NULL,
 MODIFY COLUMN `oxidsugar_last_change_c` DATETIME DEFAULT NULL;";
$r  = $db->query($sq);
$sq = "ALTER TABLE `leads_cstm` MODIFY COLUMN `oxid_last_change_c` DATETIME DEFAULT NULL,
 MODIFY COLUMN `oxidsugar_last_change_c` DATETIME DEFAULT NULL;";
$r  = $db->query($sq);

$so_send_sugar_to_oxid = get_conf_val('so_send_sugar_to_oxid');
$oxid_account_name     = get_conf_val('oxid_account_name');
$oxid_account_id       = get_conf_val('oxid_account_id');
$oxid_conflict_email   = get_conf_val('oxid_conflict_email');
$conflict_resolution   = get_conf_val('conflict_resolution');


$oxid_url      = get_conf_val('oxid_url');
$oxid_username = get_conf_val('oxid_username');
$oxid_password = get_conf_val('oxid_password');


if (!isset($_REQUEST['check_oxid_connection'])) {

    $oxUsers                = new oxid();
    $oxUsers->oxid_url      = $oxid_url;
    $oxUsers->oxid_username = $oxid_username;
    $oxUsers->oxid_password = base64_decode($oxid_password);

    if ($oxUsers->oxid_username == "" or $oxUsers->oxid_password == "" or $oxUsers->oxid_url == "") {
        echo "Please configure SugarCRM -> Oxid Connector Under MyAccount.";
        die;
    }

    $us = $oxUsers->save_leads();

    if ($oxid_conflict_email != "") {
        $email_text = "";
        if (count($_REQUEST['duplicates_array']) > 0 or count($_REQUEST['conflicts_array']) > 0) {

            $email_text = "<table width=500><tr><td colspan=3>Possible duplicates</tr></table>";
            foreach ($_REQUEST['duplicates_array'] as $k => $v) {
                $email_text .= $v;
            }
            //$email_text.="</table>";
            $email_text .= "<table width=500><tr><td colspan=3>Conflicts detected</tr></table>";
            foreach ($_REQUEST['conflicts_array'] as $k => $v) {
                $email_text .= $v;
            }

            $em = new send_alert();
            $em->send_alert_mail($oxid_conflict_email, $email_text);
        }
    }

} else {
    $oxUsers = new oxid();
    $check   = $oxUsers->check_connection($_REQUEST['uss'], $_REQUEST['pass']);
    if ($check == "url_error") {
        echo "<br>Please check URL";
    }
    if ($check == "loginFailed") {
        echo "<br>Login failed";
    }
    if ($check == "isUser") {
        echo "<br>You need an admin account !";
    }
    if ($check == "isAdmin") {
        echo "<br>Connection Ok !";
    }
}


function get_conf_val($val)
{
    global $db;
    $sq     = "select * from config where category='info' and name='" . $val . "'";
    $result = $db->query($sq, true);
    $row    = $db->fetchByAssoc($result);

    return $row['value'];
}


function debugl($message)
{
    echo $message;
}

echo "<br>Finished..";


?>
