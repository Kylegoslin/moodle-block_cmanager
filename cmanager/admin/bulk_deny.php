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
global $CFG; $DB;
$formPath = "$CFG->libdir/formslib.php";
require_once($formPath);
require_login();


/** Navigation Bar **/
$PAGE->navbar->ignore_active();
$PAGE->navbar->add(get_string('cmanagerDisplay', 'block_cmanager'), new moodle_url('/blocks/cmanager/cmanager_admin.php'));
$PAGE->navbar->add(get_string('bulkdeny', 'block_cmanager'));
$PAGE->set_url('/blocks/cmanager/admin/bulk_deny.php');
$PAGE->set_context(context_system::instance());
$PAGE->set_heading(get_string('pluginname', 'block_cmanager'));
$PAGE->set_title(get_string('pluginname', 'block_cmanager'));
echo $OUTPUT->header();
?>


<SCRIPT LANGUAGE="JavaScript" SRC="http://code.jquery.com/jquery-1.6.min.js">
</SCRIPT>
<?php

$context = context_system::instance();
if (has_capability('block/cmanager:denyrecord',$context)) {
} else {
  print_error(get_string('cannotdenyrecord', 'block_cmanager'));
}

if(isset($_GET['id'])){
	$mid = required_param('id', PARAM_INT);
	$_SESSION['mid'] = $mid;
} else {
	$mid = $_SESSION['mid'];
}


if(isset($_GET['mul'])){
	$_SESSION['mul'] = required_param('mul', PARAM_TEXT);
}

class block_cmanager_bulk_deny extends moodleform {
 
    function definition() {
        global $CFG;
        global $currentSess;
		global $mid;
		global $USER;
		global $DB;
 	
        $currentRecord =  $DB->get_record('block_cmanager_records', array('id'=>$currentSess));
        $mform =& $this->_form; // Don't forget the underscore! 
		 
        $mform->addElement('header', 'mainheader', get_string('denyrequest_Title','block_cmanager'));

		// Page description text
		$mform->addElement('html', '<p></p>&nbsp;&nbsp;&nbsp;
					    <a href="../cmanager_admin.php">< Back</a>
					    <p></p>
					    <center>'.get_string('denyrequest_Instructions','block_cmanager').'.<p></p>&nbsp;</center><center>');
	
		// Comment box
		$mform->addElement('textarea', 'newcomment', '', 'wrap="virtual" rows="5" cols="50"');
		
		$buttonarray=array();
        $buttonarray[] = &$mform->createElement('submit', 'submitbutton', get_string('denyrequest_Btn','block_cmanager'));
        $buttonarray[] = &$mform->createElement('cancel');
        $mform->addGroup($buttonarray, 'buttonar', '', array(' '), false);

		$mform->addElement('html', '<p></p>&nbsp;</center>');

	}
}




   $mform = new block_cmanager_bulk_deny();//name of the form you defined in file above.



   if ($mform->is_cancelled()){
        
	echo "<script>window.location='../cmanager_admin.php';</script>";
			die;

  } else if ($fromform=$mform->get_data()){
		global $USER, $CFG, $DB;
		
		// Send Email to all concerned about the request deny.
		require_once('../cmanager_email.php');
		
		
			$message = $_POST['newcomment'];
			$denyIds = explode(',',$_SESSION['mul']);
		    
			foreach ($denyIds as $cid) {
			
				// If the id isn't blank
				if ($cid != 'null') {
				
							$currentRecord =  $DB->get_record('block_cmanager_records', array('id'=>$cid));
		
							$replaceValues = array();
						    $replaceValues['[course_code'] = $currentRecord->modcode;
						    $replaceValues['[course_name]'] = $currentRecord->modname;
						    //$replaceValues['[p_code]'] = $currentRecord->progcode;
						    //$replaceValues['[p_name]'] = $currentRecord->progname;
						    $replaceValues['[e_key]'] = '';
						    $replaceValues['[full_link]'] = $CFG->wwwroot .'/blocks/cmanager/comment.php?id=' . $cid;
						    $replaceValues['[loc]'] = '';
						    $replaceValues['[req_link]'] = $CFG->wwwroot .'/blocks/cmanager/view_summary.php?id=' . $cid;
	    
						    
	    
						    // update the request record
							$newrec = new stdClass();
							$newrec->id = $cid;
							$newrec->status = 'REQUEST DENIED';
							$DB->update_record('block_cmanager_records', $newrec); 
							
							// Add a comment to the module
							$userid = $USER->id;
							$newrec = new stdClass();
							$newrec->instanceid = $cid;
							$newrec->createdbyid = $userid;
							$newrec->message = $message;
							$newrec->dt = date("Y-m-d H:i:s");	
							$DB->insert_record('block_cmanager_comments', $newrec);
					
							block_cmanager_send_deny_email_admin($message, $cid, $replaceValues);
								
							block_cmanager_send_deny_email_user($message, $userid, $cid, $replaceValues);
							
							$_SESSION['mul'] = '';
							
				}
			
		
			}	
		

		echo "<script> window.location = '../cmanager_admin.php';</script>";


  } else {
      
       $mform->focus();
	   $mform->set_data($mform);
	   $mform->display();
	   echo $OUTPUT->footer();
}





?>
