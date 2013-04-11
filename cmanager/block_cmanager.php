
<?php

class block_cmanager extends block_base {




function init() {

    $this->title   = 'Course Request Manager';
    $plugin = new stdClass();
    $plugin->version   = 2013041131;      // The current module version (Date: YYYYMMDDXX)
    $plugin->requires  = 2012062500;      // Requires this Moodle version


  }



function get_content() {

    if ($this->content !== NULL) {
      return $this->content;
    }

    global $CFG;
    global $COURSE;
	global $DB;



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


global $USER, $DB, $CFG;


$adminHTML = '';
if ($admins = get_admins()) {

	$loginIsValid = False;

	foreach ($admins as $admin) {
				if($admin->id == $USER->id){
		 			 $loginIsValid = True;
				}

	}

	if($loginIsValid == True){

		$numRequestsPending = 0;

		$numRequestsPending = $DB->count_records('block_cmanager_records', array('status'=>'PENDING'));

		$adminHTML = '<a href ="'.$CFG->wwwroot. '/blocks/cmanager/cmanager_admin.php' .'">'.get_string('block_admin','block_cmanager').' ['.$numRequestsPending.']</a><br>
					 <a href ="'.$CFG->wwwroot.'/blocks/cmanager/cmanager_confighome.php">'.get_string('block_config','block_cmanager').'</a><br>
					 <a href ="'.$CFG->wwwroot.'/blocks/cmanager/cmanager_admin_arch.php">'.get_string('allarchivedrequests','block_cmanager').'</a>';

	}

}
$var1 = '';
if((isloggedin())){

	$var1 = "
	<hr>
	<a href =\"".$CFG->wwwroot."/blocks/cmanager/course_request.php?new=1\">".get_string('block_request','block_cmanager')."</a><br>
	<a href =\"".$CFG->wwwroot."/blocks/cmanager/module_manager.php\">".get_string('block_manage','block_cmanager')."</a><br>
	<a href =\"".$CFG->wwwroot."/blocks/cmanager/module_manager_history.php\">".get_string('myarchivedrequests','block_cmanager')."</a>

	<hr>
	   $adminHTML
	";
}
	return $var1;


}//end function


