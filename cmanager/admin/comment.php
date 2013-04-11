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
require_login();
$PAGE->set_url('/blocks/cmanager/admin/comment.php');
$PAGE->set_context(get_system_context());
/** Navigation Bar **/
$PAGE->navbar->ignore_active();
$PAGE->navbar->add(get_string('cmanagerDisplay', 'block_cmanager'), new moodle_url('/blocks/cmanager/cmanager_admin.php'));
$PAGE->navbar->add(get_string('addviewcomments', 'block_cmanager'));
$PAGE->set_heading(get_string('pluginname', 'block_cmanager'));
$PAGE->set_title(get_string('pluginname', 'block_cmanager'));
echo $OUTPUT->header();

if(isset($_GET['id'])){
	$mid = required_param('id', PARAM_INT);
	$_SESSION['mid'] = $mid;
} else {
	$mid = $_SESSION['mid'];
}

$type = optional_param('type', '', PARAM_TEXT); 
if(!empty($type)){
	$_SESSION['type'] = $type;	
	
} else {
	$type = '';
	$type = $_SESSION['type'];
}

$backLink = '';
if($type == 'adminarch'){
	$backLink = '../cmanager_admin_arch.php';
}  
else if($type == 'adminq'){
	$backLink = '../cmanager_admin.php';
}



$PAGE->set_url('/blocks/cmanager/admin/comment.php', array('id'=>$mid));

class courserequest_form extends moodleform {
 
    function definition() {
    global $CFG;
    global $currentSess;
	global $mid;
	global $USER;
	global $DB;
	global $backLink;

	$currentRecord =  $DB->get_record('block_cmanager_records', array('id'=>$currentSess));
	$mform =& $this->_form; // Don't forget the underscore! 
 	$mform->addElement('header', 'mainheader','<div><span style="font-size:18px">'. get_string('comments_Header','block_cmanager'). '</span></div>');

	// Page description text
	$mform->addElement('html', '<p></p>&nbsp;&nbsp;&nbsp;
				    <a href="'.$backLink.'">< Back</a>
				    <p></p>
				    &nbsp;&nbsp;&nbsp;'.get_string('comments_Forward','block_cmanager').'.<p></p>&nbsp;<center>');


	// Comment box
	$mform->addElement('textarea', 'newcomment', '', 'wrap="virtual" rows="5" cols="50"');
	
	$buttonarray=array();
	$buttonarray[] = &$mform->createElement('submit', 'submitbutton', get_string('comments_PostComment','block_cmanager'));
	$buttonarray[] = &$mform->createElement('cancel');
	$mform->addGroup($buttonarray, 'buttonar', '', array(' '), false);

	$mform->addElement('html', '<p></p>&nbsp;');
	
	$whereQuery = "instanceid = '$mid'  ORDER BY id DESC";
 	$modRecords = $DB->get_recordset_select('block_cmanager_comments', $whereQuery);
	$htmlOutput = '';


	
	foreach($modRecords as $record){
		
		$createdbyid = $record->createdbyid;
		$username = $DB->get_field_select('user', 'username', "id = '$createdbyid'");
		
		
	  	$htmlOutput .='	<tr style=" border-bottom:1pt solid black;">';
		$htmlOutput .=' <td width="150px">' . $record->dt . '</td>';
		$htmlOutput .=' <td width="300px">' . $record->message . '</td>';
		$htmlOutput .=' <td width="100px">' . $username .'</td>';
		$htmlOutput .=' </tr>';

	}

	 $mform->addElement('html', '<center><div align="left" style="border: 1px #000000 solid; width:700px;
		                    background:  #E0E0E0">
	<table width="700px">
			 <tr>
		             <td width="170px">Date / Time</td>
		             <td width="430px">Message</td> 
		             <td width="100px">From</td> 
		         </tr>
			 </table>

	</div>

	<table width="700px">
			 <tr>
		             <td width="170px"></td>
		             <td width="430px"></td> 
		             <td width="100px"></td> 
		         </tr>
			' . $htmlOutput . '
			 </table>
	</div>

	<p></p>
	<p></p>
	
	</center>
	');

	}
}




   $mform = new courserequest_form();//name of the form you defined in file above.



   if ($mform->is_cancelled()){
        
	echo "<script>window.location='" . $backLink."';</script>";
			die;

  } else if ($fromform=$mform->get_data()){
		global $USER, $CFG, $DB, $mid;

		$userid = $USER->id;

		$newrec = new stdClass();
		$newrec->instanceid = $mid;
		$newrec->createdbyid = $userid;
		$newrec->message = $_POST['newcomment'];
		$newrec->dt = date("Y-m-d H:i:s");	
		$DB->insert_record('block_cmanager_comments', $newrec, false);
           	
		// Send an email to everyone concerned.
		require_once('../cmanager_email.php');
		$message = $_POST['newcomment'];
		
		// Get all user id's from the record
		$currentRecord =  $DB->get_record('block_cmanager_records', array('id'=>$mid));
		
		
		
		$user_ids = ''; // Used to store all the user IDs for the people we need to email.
		$user_ids = $currentRecord->createdbyid; // Add the current user
		
		// Get info about the current object.
		
		// Send email to the user
		$replaceValues = array();
	    $replaceValues['[course_code'] = $currentRecord->modcode;
	    $replaceValues['[course_name]'] = $currentRecord->modname;
	    //$replaceValues['[p_code]'] = $currentRecord->progcode;
	   // $replaceValues['[p_name]'] = $currentRecord->progname;
	    $replaceValues['[e_key]'] = '';
	    $replaceValues['[full_link]'] = $CFG->wwwroot.'/blocks/cmanager/comment.php?id=' . $mid;
	    $replaceValues['[loc]'] = '';
		$replaceValues['[req_link]'] = $CFG->wwwroot.'/blocks/cmanager/view_summary.php?id=' . $mid;
	    
	    
		email_comment_to_user($message, $user_ids, $mid, $replaceValues);
	
		// Send email to admin
		email_comment_to_admin($message, $mid, $replaceValues);
		 
		echo "<script> window.location = 'comment.php?type=".$type."&id=$mid';</script>";

  } else {
        
	
 	$mform->focus();
    $mform->set_data($mform);
    $mform->display();
    
    

	echo $OUTPUT->footer();
	 
}



?>
