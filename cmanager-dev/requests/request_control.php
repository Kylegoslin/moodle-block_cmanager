<?php
/* --------------------------------------------------------- 



     COURSE REQUEST BLOCK FOR MOODLE  

     2012-2013 Kyle Goslin, Daniel McSweeney



 --------------------------------------------------------- */
require_once("../../../config.php");
require_once("$CFG->libdir/formslib.php");
global $CFG, $DB;
global $USER;

/** Navigation **/
$PAGE->navbar->ignore_active();
$PAGE->navbar->add(get_string('cmanagerDisplay', 'block_cmanager'), new moodle_url('/blocks/cmanager/module_manager.php'));
$PAGE->navbar->add(get_string('requestcontrol', 'block_cmanager'));
require_login();

$PAGE->set_url('/blocks/cmanager/requests/request_control.php');
$PAGE->set_context(get_system_context());
$PAGE->set_heading(get_string('pluginname', 'block_cmanager'));
$PAGE->set_title(get_string('pluginname', 'block_cmanager'));
$currentSess = '00';
$currentSess = $_SESSION['cmanager_session'];


if(isset($_GET['id'])){

	$_SESSION['mid'] = required_param('id', PARAM_INT);
} 

?>
<link rel="stylesheet" type="text/css" href="css/main.css" />
<SCRIPT LANGUAGE="JavaScript" SRC="http://code.jquery.com/jquery-1.6.min.js">
</SCRIPT>

<?php

class courserequest_form extends moodleform {
 
    function definition() {
        global $CFG;
        global $currentSess, $DB;
        $currentRecord =  $DB->get_record('block_cmanager_records', array('id' =>$currentSess));


        $mform =& $this->_form; // Don't forget the underscore! 
 

        $mform->addElement('header', 'mainheader', get_string('modrequestfacility','block_cmanager'));


        // Page description text
		$mform->addElement('html', '<center><b>' . get_string('sendrequestforcontrol','block_cmanager'). '</b></center>');
		$mform->addElement('html', '<p></p><center><p>' . get_string('emailswillbesent','block_cmanager'). '</p>&nbsp; ');
        
        
        
        // Comment box
		$mform->addElement('textarea', 'customrequestmessage', '', 'wrap="virtual" rows="8" cols="50"');
	
        
        
        $buttonarray=array();
        $buttonarray[] = &$mform->createElement('submit', 'submitbutton',get_string('sendrequestemail','block_cmanager'));
        $buttonarray[] = &$mform->createElement('cancel');
        $mform->addGroup($buttonarray, 'buttonar', '', array(' '), false);

		$mform->addElement('html', '<p></p>&nbsp;</center>');
	
        
/*

	// Page description text
	$mform->addElement('html', '<center><b>A request has been  made</b></center>');
	$mform->addElement('html', '<p></p><center>E-mails have been sent to the owner of the module. Please wait for a response.<p></p>&nbsp; </center>');
	$mform->addElement('html', '<p></p><center><a href="../../cmanager/module_manager.php">Click here to return to the module manager</a> </center>');
	$mform->closeHeaderBefore('buttonar');

	*/
	}
}









  $mform = new courserequest_form();
  

  
  if ($mform->is_cancelled()){
        
		echo "<script>window.location='../module_manager.php'; </script>";
        die;
        
  } else if ($fromform=$mform->get_data()){

		
  		// Send Email
		$custommessage = $_POST['customrequestmessage'];
  		require_once('../cmanager_email.php');
		handover_email_lecturers($_SESSION['mid'], $USER->id, $custommessage);
		
  		echo "<script>window.location='../module_manager.php'; </script>";
        die;
    
  

  } else {
        
 		echo $OUTPUT->header();
   		$mform->focus();
   		
	    $mform->set_data($mform);
	    $mform->display();
	   
	   
	   echo $OUTPUT->footer();
		
}











?>
