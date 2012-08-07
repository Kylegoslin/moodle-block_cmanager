<link rel="stylesheet" type="text/css" href="css/main.css" />
<?php

class block_cmanager extends block_base {
  



function init() {

    $this->title   = 'Course Request Manager';
    $plugin->version   = 2012042502;      // The current module version (Date: YYYYMMDDXX)
	$plugin->requires  = 2010031900;      // Requires this Moodle version

   
  }



function get_content() {

    if ($this->content !== NULL) {
      return $this->content;
    }
	
    global $CFG;
    global $COURSE;
	global $DB;
	
	
    // Check to see if the config vars has been run
    // if not then redirect to the setup
    $configHasRun = $DB->get_field('block_cmanager_config', 'value', array('varname'=>'confighasrun'));
	
    
    if($configHasRun == '' || $configHasRun == null){
   		echo "<script>window.location ='blocks/cmanager/installer_build_config/build.php'; </script>";
    }
 



    $this->content =  new stdClass;
    $this->content->text = getHTMLContent();
    $this->content->footer = '';
 
    return $this->content;
  }
}  


/*
This is the main content generation function that is responsible for 
returning the relevant content to the user depending on what status
they have (admin / student).

*/
function getHTMLContent(){


global $USER;


if ($admins = get_admins()) {
	 
	$loginIsValid = False;
	
	foreach ($admins as $admin) {
				if($admin->id == $USER->id){
		 			 $loginIsValid = True;
				}
		 
	}
	
	if($loginIsValid == True){
		$adminHTML = '<a href ="blocks/cmanager/cmanager_admin.php">'.get_string('block_admin','block_cmanager').'</a><br><a href ="blocks/cmanager/cmanager_confighome.php">'.get_string('block_config','block_cmanager').'</a>';
	}
	
}
$var1 = '';
if((isloggedin())){

	$var1 = "
	<hr>
	<a href =\"blocks/cmanager/course_request.php?new=1\">".get_string('block_request','block_cmanager')."</a><br>
	<a href =\"blocks/cmanager/module_manager.php\">".get_string('block_manage','block_cmanager')."</a>
	<hr>	
	   $adminHTML
	";
}
	return $var1;


}//end function


