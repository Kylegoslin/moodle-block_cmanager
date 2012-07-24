<?php 
require_login();
global $USER;
global $CFG;


if ($admins = get_admins()) { 
$loginIsValid = False;
	foreach ($admins as $admin) {
		
		
		if($admin->id == $USER->id){
		 
		   $loginIsValid = True;
		  
		}
		 
	}
		if($loginIsValid != True){
	   echo "<script>window.location = '".$CFG->wwwroot."';</script>";
	   die;
	}
	
}


?>