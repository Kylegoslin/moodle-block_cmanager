<?php
/* --------------------------------------------------------- 

     COURSE REQUEST BLOCK FOR MOODLE  

     2012 Kyle Goslin & Daniel McSweeney
     Institute of Technology Blanchardstown
     Dublin 15, Ireland
 --------------------------------------------------------- */

require_once("../../../config.php");
global $CFG, $DB;

require_once("$CFG->libdir/formslib.php");
require_once('../../../course/lib.php');
require_once($CFG->libdir.'/completionlib.php');
require_once('../lib/course_lib.php');
require_login();
require_once('../validate_admin.php');
$PAGE->set_url('/blocks/cmanager/admin/approve_course_new.php');
$PAGE->set_context(get_system_context());
$PAGE->set_title(get_string('pluginname', 'block_cmanager'));



if(isset($_GET['id'])){
	$mid = required_param('id', PARAM_INT);
	$_SESSION['mid'] = $mid;
} else {

	$mid = $_SESSION['mid'];
}


	// Create the course by record ID      
	$nid = createNewCourseByRecordId($mid, true);
	
	
  if(empty($nid)){
  	
	echo 'New Mod ID Not set';
	die;
	  
	  
  } else {
	
	echo '<script> window.location ="../../../course/edit.php?id=' .$nid . '";</script>';
  }



?>
