<?PHP
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
class send_alert {    
  
var $rec_id;
var $case_description;
var $account_name;
var $subject_case;
var $module;
var $task_id;
var $template_name="FORM";
var $wf_id;
var $opp_assigned_user;
var $full_op_name;
var $fileLocation;
var $filename;
var $to_over;
var $to_over_name;
var $obj;
var $current_date;
var $notify_address;
var $notify_name;
var $wf_body;
#var $module_dir;  
    
function send_alert_mail ($to_email_address,$email_body){
require_once("modules/Administration/Administration.php");

  $GLOBALS['log']->fatal("start email");    

            $admin = new Administration();
            $admin->retrieveSettings();
            $sendNotifications = true;

               $this->send_alert_notifications($to_email_address,$email_body); 
    }    
  
function create_alert_email($to_email_address,$email_body) {
        global $sugar_version;
        global $sugar_config;
        global $app_list_strings;
        global $current_user;
        global $locale;
        require_once("XTemplate/xtpl.php");
        require_once("include/SugarPHPMailer.php");


        
        
        
        $GLOBALS['log']->fatal("Notifications: user has e-mail defined");
        $notify_mail = new SugarPHPMailer();
        $notify_mail->AddAddress($to_email_address, "");    
 

        $port = '';

        if(isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] != 80 && $_SERVER['SERVER_PORT'] != 443) {
            $port = $_SERVER['SERVER_PORT'];
        }

        $httpHost = $_SERVER['HTTP_HOST'];

        if($colon = strpos($httpHost, ':')) {
            $httpHost    = substr($httpHost, 0, $colon);
        }

        $parsedSiteUrl = parse_url($sugar_config['site_url']);
        $host = ($parsedSiteUrl['host'] != $httpHost) ? $httpHost : $parsedSiteUrl['host'];

        if(!isset($parsedSiteUrl['port'])) {
            $parsedSiteUrl['port'] = 80;
        }

        $port        = ($parsedSiteUrl['port'] != 80) ? ":".$parsedSiteUrl['port'] : '';
        $path        = !empty($parsedSiteUrl['path']) ? $parsedSiteUrl['path'] : "";
        $cleanUrl    = "{$parsedSiteUrl['scheme']}://{$host}{$port}{$path}";


        $notify_mail->Body = $email_body;

 $new_subject =  utf8_encode("Sugar-Oxid conflicts");     

  

         $notify_mail->Subject = $new_subject;

        $notify_mail->ContentType = "text/html";
        $notify_mail->IsHTML=true;
        $notify_mail->prepForOutbound();

        return $notify_mail;
    }    
    
function send_alert_notifications($to_email_address,$email_body)
    {
        global $current_user;

        
        $sendToEmail =$to_email_address;
        

            if(empty($sendToEmail)) {
                $GLOBALS['log']->fatal("Notifications: no e-mail address set for user {$notify_user->user_name}, cancelling send");
            } else {
                $notify_mail = $this->create_alert_email($to_email_address,$email_body);
                $notify_mail->setMailerForSystem();

                
            


                
        $notify_mail->ContentType == "text/html";
        $notify_mail->IsHTML=true;
                
            $admin = new Administration();
            $admin->retrieveSettings();
                
                    $notify_mail->From = $admin->settings['notify_fromaddress'];
                    $notify_mail->FromName = (empty($admin->settings['notify_fromname'])) ? "" : $admin->settings['notify_fromname'];
 $GLOBALS['log']->fatal("send message to: ".$sendToEmail);
  if(!$notify_mail->Send()) {
 $GLOBALS['log']->fatal("Notifications: error sending e-mail (method: {$notify_mail->Mailer}), (error: {$notify_mail->ErrorInfo})");
  echo "error";
  }else{
  echo "email sent";
  }
            }
#        }
    }    

}   
?>