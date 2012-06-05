<?php
/* --------------------------------------------------------- 

     COURSE REQUEST BLOCK FOR MOODLE  

     2012 Kyle Goslin & Daniel McSweeney
     Institute of Technology Blanchardstown
     Dublin 15, Ireland
 --------------------------------------------------------- */

require_once("../../../config.php");
global $CFG;
$formPath = "$CFG->libdir/formslib.php";
require_once($formPath);
require_once('../validate_admin.php');
?>

<link rel="stylesheet" type="text/css" href="css/main.css" />
<SCRIPT LANGUAGE="JavaScript" SRC="http://code.jquery.com/jquery-1.6.min.js">
</SCRIPT>
<?php

if(isset($_GET['id'])){
	$mid = required_param('id', PARAM_INT);
	$_SESSION['mid'] = $mid;
} else {

	$mid = $_SESSION['mid'];
}


/** Navigation Bar **/
$PAGE->navbar->ignore_active();
$PAGE->navbar->add(get_string('cmanagerDisplay', 'block_cmanager'), new moodle_url('/blocks/cmanager/cmanager_admin.php'));
$PAGE->navbar->add(get_string('denycourse', 'block_cmanager'));
$PAGE->set_url('/blocks/cmanager/admin/deny_course.php');
$PAGE->set_context(get_system_context());




class courserequest_form extends moodleform {
 
    function definition() {
    global $CFG;
    global $currentSess;
	global $mid;
	global $USER, $DB;

	$currentRecord =  $DB->get_record('cmanager_records', array('id'=>$currentSess));
	$mform =& $this->_form; // Don't forget the underscore! 
	$mform->addElement('header', 'mainheader', get_string('denyrequest_Title','block_cmanager'));


	// Page description text
	$mform->addElement('html', '<p></p>&nbsp;&nbsp;&nbsp;
				    <a href="../cmanager_admin.php">< Back</a>
				    <p></p>
				    <center>'.get_string('denyrequest_reason','block_cmanager').'.<p></p>&nbsp;');


	// Comment box
	$mform->addElement('textarea', 'newcomment', '', 'wrap="virtual" rows="5" cols="50"');
		
	$buttonarray=array();
    $buttonarray[] = &$mform->createElement('submit', 'submitbutton', get_string('denyrequest_Btn','block_cmanager'));
    $buttonarray[] = &$mform->createElement('cancel');
    $mform->addGroup($buttonarray, 'buttonar', '', array(' '), false);
	$mform->addElement('html', '<p></p>&nbsp;');
	
	$mform->addElement('html', '</center>');
	
	}
}




   $mform = new courserequest_form();//name of the form you defined in file above.



   if ($mform->is_cancelled()){
        
	echo "<script>window.location='../cmanager_admin.php';</script>";
			die;

  } else if ($fromform=$mform->get_data()){
		global $USER;
		global $CFG, $DB;
		
		// Send Email to all concerned about the request deny.
		require_once('../cmanager_email.php');
		
		
		$message = $_POST['newcomment'];

	
		
		// update the request record
		$newrec = new stdClass();
		$newrec->id = $mid;
		$newrec->status = 'REQUEST DENIED';
		$DB->update_record('cmanager_records', $newrec); 
		
		// Add a comment to the module
		$userid = $USER->id;
		$newrec = new stdClass();
		$newrec->instanceid = $mid;
		$newrec->createdbyid = $userid;
		$newrec->message = $message;
		$newrec->dt = date("Y-m-d H:i:s");	
		$DB->insert_record('cmanager_comments', $newrec, false);
		
		
		
		$currentRecord =  $DB->get_record('cmanager_records', array('id'=>$mid));
		
		$replaceValues = array();
	    $replaceValues['[course_code'] = $currentRecord->modcode;
	    $replaceValues['[course_name]'] = $currentRecord->modname;
	    //$replaceValues['[p_code]'] = $currentRecord->progcode;
	    //$replaceValues['[p_name]'] = $currentRecord->progname;
	    $replaceValues['[e_key]'] = '';
	    $replaceValues['[full_link]'] = $CFG->wwwroot.'/blocks/cmanager/comment.php?id=' . $mid;
	    $replaceValues['[loc]'] = '';
	    $replaceValues['[req_link]'] = $CFG->wwwroot.'/blocks/cmanager/view_summary.php?id=' . $mid;
	    
	    
	    
	    
		send_deny_email_admin($message, $mid, $replaceValues);
			
		send_deny_email_user($message, $userid, $mid, $replaceValues);
			
	   	

		echo "<script> window.location = '../cmanager_admin.php';</script>";
	
  } else {
        

          print_header_simple($streditinga='', '',

		    
		    "<a href=\"../cmanager_admin.php\">".get_string('cmanagerDisplay','block_cmanager')."</a> ->
		    ", $mform->focus(), "", false);
	    $mform->set_data($mform);
	    $mform->display();
	  
 
}

function getUsername($id){
		
	global $DB;
	return $username = get_field('user', 'username', array('id'=>$id));
	
}



?>
