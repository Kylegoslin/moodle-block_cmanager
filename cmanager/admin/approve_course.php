 <?php
/* --------------------------------------------------------- 

     COURSE REQUEST BLOCK FOR MOODLE  

     2012 Kyle Goslin & Daniel McSweeney
     Institute of Technology Blanchardstown
     Dublin 15, Ireland
 --------------------------------------------------------- */

require_once("../../../config.php");
global $CFG, $DB;
$formPath = "$CFG->libdir/formslib.php";
require_once($formPath);
require_once('../lib/displayLists.php');

/** Navigation Bar **/
$PAGE->navbar->ignore_active();
$PAGE->navbar->add(get_string('cmanagerDisplay', 'block_cmanager'), new moodle_url('/blocks/cmanager/cmanager_admin.php'));
$PAGE->navbar->add(get_string('approvecourse', 'block_cmanager'));
require_login();
require_once('../validate_admin.php');

$PAGE->set_url('/blocks/cmanager/admin/approve_course.php');
$PAGE->set_context(get_system_context());
$PAGE->set_heading(get_string('pluginname', 'block_cmanager'));
$PAGE->set_title(get_string('pluginname', 'block_cmanager'));
echo $OUTPUT->header();
?>



<script language="javascript" type="text/javascript">
<!--
function popitup(url) {
	newwindow=window.open(url,'name','height=400,width=350');
	if (window.focus) {newwindow.focus()}
	return false;
}

// -->
</script>

<?php


if(isset($_GET['id'])){
	$mid = required_param('id', PARAM_INT);
	$_SESSION['mid'] = $mid;
} else {

	$mid = $_SESSION['mid'];
}



class courserequest_form extends moodleform {
 
    function definition() {
    global $CFG, $currentSess, $mid, $USER, $DB;


	
 	$rec =  $DB->get_record('block_cmanager_records', array('id'=>$mid));



	$mform =& $this->_form; // Don't forget the underscore! 
	$mform->addElement('header', 'mainheader', '<span style="font-size:18px">'. get_string('courserequestadmin','block_cmanager'). '</span>');

	// Page description text
	$mform->addElement('html', '<p></p>&nbsp;&nbsp;&nbsp;
				    <a href="../cmanager_admin.php">< '.get_string('back','block_cmanager').'</a>
				    <p></p>');
				    
	



	$rec = $DB->get_recordset_select('block_cmanager_records', 'id = ' . $mid);
   	$displayModHTML = displayAdminList($rec, false, false, false, '');
	


	$outputHTML = '
	<center>
	<div style="width:300px; height:500px">
	'.$displayModHTML.'
	</div>
	<p></p>
	&nbsp;
	<p></p>
	<a href="#" onclick="return popitup(\'showcoursedetails.php?id='.$mid.'\')"
	>[' . get_string('requestReview_OpenDetails','block_cmanager'). ']</a> - <a href="approve_course_new.php">' . get_string('requestReview_ApproveRequest','block_cmanager'). '</a>	
	
	</center>
		';


	$mform->addElement('html', $outputHTML);

   
	}
}


   $mform = new courserequest_form();//name of the form you defined in file above.

   	if ($mform->is_cancelled()){
     
	} 
	
	else if ($fromform=$mform->get_data()){
	

  	} 
  
  	else {
        
 	
	
	
	$mform->set_data($mform);
	$mform->display();
	
	
	echo $OUTPUT->footer();
	}





?>
