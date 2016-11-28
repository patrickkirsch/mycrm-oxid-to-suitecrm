<?PHP

class LimitUsers {
 
       function LimitUsers(&$bean, $event, $arguments)
    {
require_once("mycrm_conf.php");
$limit=$max_number_of_users;
        #    $limit=6;    
    
$us_check=new User();
$us_check->disable_row_level_security=true;  
$us_check->retrieve($bean->id) ;
$old_status=$us_check->status;       

        
 global $db;
 $sq ="select count(*) as tot_users from users where status='Active' and deleted =0 and portal_only=0 and is_group=0";
 
 $result=$db->query($sq);
$row=$db->fetchByAssoc($result); 

if ($row['tot_users']>=$limit){
    if ($old_status=="Inactive" and $bean->status=="Active"){
     echo "Error saving user.Max number of users reached.";
     exit;
    }
}

if ($row['tot_users']>=$limit){    
     if ($old_status=="" and $bean->status=="Active"){
     echo "Error saving user.Max number of users reached...";
     exit;
    }   
}


    }

}

?>