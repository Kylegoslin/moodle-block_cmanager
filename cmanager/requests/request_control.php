<?php
// --------------------------------------------------------- 
// block_cmanager is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// block_cmanager is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.
//
// COURSE REQUEST MANAGER BLOCK FOR MOODLE
// by Kyle Goslin & Daniel McSweeney
// Copyright 2012-2014 - Institute of Technology Blanchardstown.
// --------------------------------------------------------- 
/**
 * COURSE REQUEST MANAGER
  *
 * @package    block_cmanager
 * @copyright  2014 Kyle Goslin, Daniel McSweeney
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
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
$PAGE->set_context(context_system::instance());
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

class block_cmanager_request_control_form extends moodleform {
 
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
	
        

	}
}









  $mform = new block_cmanager_request_control_form();
  

  
  if ($mform->is_cancelled()){
        
		echo "<script>window.location='../module_manager.php'; </script>";
        die;
        
  } else if ($fromform=$mform->get_data()){

		
  		// Send Email
		$custommessage = $_POST['customrequestmessage'];
  		require_once('../cmanager_email.php');
		block_cmanager_handover_email_lecturers($_SESSION['mid'], $USER->id, $custommessage);
		
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
