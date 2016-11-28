# Installation auf Oxid SuiteCRM connector

Disclaimer:
    This code bases on the file "Unzip-me first - MyCRM-OXID-to-Sugar Extension.zip" from
    http://exchange.oxid-esales.com/System-Integration/Customer-Relationship-Management-CRM/MyCRM-OXID-to-Sugar-Extension-1-0-7-Beta-CE-4-5-x.html
    
This repository contains only adaptions to make this really deprected code to work with current Oxid 5.2.6.


## Installation instructions:

### Oxid side
- put directory "websitepart" to your Oxid Shop root directory
- read and apply readme.txt
- apply sql.txt on the database
- adapt sugarcrm.wsdl to your need (esp. replace URLs)
- (hint: Nusoap was dropped in favor of standard PHP WSDL)

### SuiteCRM side
- install file SugarOxidServerPart_v1.07.zip via SuiteCRM
- adapt sugarcrm.wsdl to your need (esp. replace URLs)
- configure credentials via Oxid connector settings, e.g.
  https://your.suitecrm.net/index.php?module=Administration&action=DetailViewOxidSync
 
 
###Important node
- The Update is done by script
    http://your.suitecrm.net/sync_oxid.php
  So you need a cron job to periodically import users or orders from Oxid.
  

