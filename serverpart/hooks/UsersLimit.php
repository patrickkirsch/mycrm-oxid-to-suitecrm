<?PHP

class LimitUsers {
 
       function LimitUsers(&$bean, $event, $arguments)
    {
require_once("mycrm_conf.php");
$limit=$max_number_of_users;
        #    $limit=6;    
//echo "<br>lim ".$limit;    
$us_check=new User();
$us_check->disable_row_level_security=true;      
$us_check->retrieve($bean->id) ;
$old_status=$us_check->status;       

//echo "<br>1 ".$us_check->is_group;
//echo "<br>2 ".$bean->is_group;
 //  exit;
//echo "<br>1 ".$bean->portal_only;
//echo "<br>2 ".$bean->is_group;

        
 global $db;
 $sq ="select count(*) as tot_users from users where status='Active' and deleted =0 and portal_only=0 and is_group=0";
 //echo "<br>".$sq;
 $result=$db->query($sq);
$row=$db->fetchByAssoc($result); 

//echo "<br>tu ".$row['tot_users'];



if ($row['tot_users']>=$limit){
    if ($old_status=="Inactive" and $bean->status=="Active" and $bean->portal_only==0 and $bean->is_group==0){
     echo "You have reached the maximum number of users that are included in your plan.";
     exit;
    }
#}

#if ($row['tot_users']>=$limit){    
     if ($old_status=="" and $bean->status=="Active" and $bean->portal_only==0 and $bean->is_group==0){
     echo "You have reached the maximum number of users that are included in your plan.";
     exit;
    }   

     if ($us_check->is_group==1 and $bean->is_group==0  and $bean->status=="Active" ){
     echo "You have reached the maximum number of users that are included in your plan.";
     exit;
    }   

     if ($us_check->portal_only==1 and $bean->portal_only==0  and $bean->status=="Active" ){
     echo "You have reached the maximum number of users that are included in your plan.";
     exit;
    }  

}

  //exit;  
    }

}

?>