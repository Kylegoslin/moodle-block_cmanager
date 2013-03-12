<?php
/* -------------------------------------------------------
 * 
 * 
 *  Course Request Manager
 *  by Kyle Goslin, Daniel McSweeney
 * 
 * 
 * -------------------------------------------------------
 * */

global $CFG, $DB;

require_once("../../config.php");
$formPath = "$CFG->libdir/formslib.php";
require_once($formPath);
require_once('generate_summary.php');
require_login();
require_once('lib/displayLists.php');


/** Navigation Bar **/
$PAGE->navbar->ignore_active();
$PAGE->navbar->add(get_string('cmanagerDisplay', 'block_cmanager'), new moodle_url('/blocks/cmanager/module_manager.php'));
$PAGE->set_url('/blocks/cmanager/module_manager.php');
$PAGE->set_context(get_system_context());
$PAGE->set_heading(get_string('pluginname', 'block_cmanager'));
$PAGE->set_title(get_string('pluginname', 'block_cmanager'));
echo $OUTPUT->header();


?>

<link rel="stylesheet" type="text/css" href="css/main.css" />
 
<link href="js/jquery/jquery-ui18.css" rel="stylesheet" type="text/css"/>
<script src="js/jquery/jquery-1.7.2.min.js"></script>
<script src="js/jquery/jquery-ui.1.8.min.js"></script>
<script type="text/javascript">


function cancelConfirm(id,langString) {
	//var answer = confirm("Are you sure you want to cancel this request?")
	var answer = confirm(langString)
	if (answer){
		
		window.location = "deleteRequest.php?id=" + id;
	}
	else{
		
	}
}
</script>





<?php

class courserequest_form extends moodleform {
 
    function definition() {

	global $CFG, $DB, $USER;
 
    $mform =& $this->_form; // Don't forget the underscore! 
	$mform->addElement('header', 'mainheader',' <span style="font-size:18px"> '.get_string('cmanagerExstingTab','block_cmanager').'</span>');
	$mform->addElement('html', '<p></p>&nbsp;&nbsp;&nbsp;'.get_string('cmanagerWelcome','block_cmanager').' &nbsp;
			<p></p><br>
			&nbsp;&nbsp;<INPUT TYPE="BUTTON" VALUE="'.get_string('cmanagerRequestBtn','block_cmanager').'" ONCLICK="window.location.href=\'course_request.php?new=1\'"><br>
			<p></p><p></p>&nbsp;');

	$uid = $USER->id;
	

	// Get the list of pending requests
   $pendingList = $DB->get_records('block_cmanager_records',array('createdbyid' => "$uid AND" , 'status' => 'PENDING'), 'id ASC');
   $outputHTML = '<div id="pendingrequestcontainer">';


   
   
 	// Existing Requests
 	
   	$outputHTML = displayAdminList($pendingList, true, false, false, 'user_manager');
 	
 	
 	$mform->addElement('html', '<center>
	<p></p>
	&nbsp;
	<p></p>
	<div id="twobordertitle" style="background:transparent">
		<div style="text-align: left; float: left; font-size:11pt">&nbsp;<b>'.get_string('cmanagerExstingTab','block_cmanager').'</b></div> 
		<div style="text-align: right; font-size:11pt"><b>'.get_string('cmanagerActions','block_cmanager').'</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
	 </div>

			'.$outputHTML.'
			');
 		
    } // Close the function
    
} // Close the class



		$mform = new courserequest_form();

		if ($mform->is_cancelled()){
			echo "<script>window.location='module_manager.php';</script>";
			die;
		} else if ($fromform=$mform->get_data()){

		} else {
			
		
		  
		
		}

	
    $mform->display();
	$mform->focus();

	$mform->focus();
	echo $OUTPUT->footer();


?>
 