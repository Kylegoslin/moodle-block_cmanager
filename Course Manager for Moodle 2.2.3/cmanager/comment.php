<?php
/* --------------------------------------------------------- 

     COURSE REQUEST BLOCK FOR MOODLE  

     2012 Kyle Goslin & Daniel McSweeney
     Institute of Technology Blanchardstown
     Dublin 15, Ireland
 --------------------------------------------------------- */

require_once("../../config.php");
global $CFG, $DB;
$formPath = "$CFG->libdir/formslib.php";
require_once($formPath);

?>
<title>Course Manager</title>
<link rel="stylesheet" type="text/css" href="css/main.css" />
<SCRIPT LANGUAGE="JavaScript" SRC="http://code.jquery.com/jquery-1.6.min.js">
</SCRIPT>
<?php

/** Navigation Bar **/
$PAGE->navbar->ignore_active();
$PAGE->navbar->add(get_string('cmanagerDisplay', 'block_cmanager'), new moodle_url('/blocks/cmanager/module_manager.php'));
$PAGE->navbar->add(get_string('addviewcomments', 'block_cmanager'));

$PAGE->set_url('/blocks/cmanager/comment.php');
$PAGE->set_context(get_system_context());


if(isset($_GET['id'])){
	$mid = required_param('id', PARAM_INT);
	$_SESSION['mid'] = $mid;
} else {

	$mid = $_SESSION['mid'];
}



class courserequest_form extends moodleform {
 
    function definition() {
    	
    global $CFG, $currentSess, $mid, $USER, $DB;

    $mform =& $this->_form; // Don't forget the underscore! 
 	$mform->addElement('header', 'mainheader', get_string('comments_Header','block_cmanager'));

	// Page description text
	$mform->addElement('html', '<p></p>&nbsp;&nbsp;&nbsp;
				    <a href="module_manager.php">< '.get_string('back','block_cmanager').'</a>
				    <p></p>
				    &nbsp;&nbsp;&nbsp;'.get_string('comments_Forward','block_cmanager').'<p></p>&nbsp;<center>');

	// Comment box
	$mform->addElement('textarea', 'newcomment', '', 'wrap="virtual" rows="5" cols="50"');
	
	$buttonarray=array();
    $buttonarray[] = &$mform->createElement('submit', 'submitbutton', get_string('comments_PostComment','block_cmanager'));
    $buttonarray[] = &$mform->createElement('cancel');
    $mform->addGroup($buttonarray, 'buttonar', '', array(' '), false);

	$mform->addElement('html', '<p></p>&nbsp;');
	
	$whereQuery = "instanceid = '$mid' ORDER BY id DESC";
 	$modRecords = $DB->get_recordset_select('cmanager_comments', $whereQuery);
	$htmlOutput = '';

	foreach($modRecords as $record){
		
		$createdbyid = $record->createdbyid;
		$username = $DB->get_field_select('user', 'username', "id = '$createdbyid'");
		
	  	$htmlOutput .='	<tr>';
		$htmlOutput .=' <td width="150px">' . $record->dt . '</td>';
		$htmlOutput .=' <td width="300px">' . $record->message . '</td>';
		$htmlOutput .=' <td width="100px">' . $username .'</td>';
		$htmlOutput .=' <tr>';

	}
	 $mform->addElement('html', '</center>');
	 $mform->addElement('html', '<center><div align="left" style="border: 1px #E0E0E0 solid; width:700px;
		                    background:  #E0E0E0">
	<table width="700px">
			 <tr>
		             <td width="170px">'.get_string('comments_date','block_cmanager').'</td>
		             <td width="430px">'.get_string('comments_message','block_cmanager').'</td> 
		             <td width="100px">'.get_string('comments_from','block_cmanager').'</td> 
		         <tr>
			 </table>

	</div>

	<table width="700px">
			 <tr>
		             <td width="170px"></td>
		             <td width="430px"></td> 
		             <td width="100px"></td> 
		         <tr>
			' . $htmlOutput . '
			 </table>
	</div>

	<p></p>
	<p></p>
	');




	}
}




   $mform = new courserequest_form();//name of the form you defined in file above.



   if ($mform->is_cancelled()){
        
	echo "<script>window.location='module_manager.php';</script>";
			die;

  } else if ($fromform=$mform->get_data()){
		global $USER, $CFG, $DB;
		
		// Add a record to the database
		$userid = $USER->id;
		$newrec = new stdClass();
		$newrec->instanceid = $mid;
		$newrec->createdbyid = $userid;
		$newrec->message = $_POST['newcomment'];
		$newrec->dt = date("Y-m-d H:i:s");	
		$DB->insert_record('cmanager_comments', $newrec, false);
            	



		// Send an email to everyone concerned.
		require_once('cmanager_email.php');
		$message = $_POST['newcomment'];
		// Get all user id's from the record
		$currentRecord =  $DB->get_record('cmanager_records', array('id'=>$mid));


		$user_id = ''; 
		$user_id = $currentRecord->createdbyid; // Add the current user
		
		// Get info about the current object.
		$currentRecord =  $DB->get_record('cmanager_records', array('id'=>$mid));
		
		
		// Send email to the user
		$replaceValues = array();
	    $replaceValues['[course_code'] = $currentRecord->modcode;
	    $replaceValues['[course_name]'] = $currentRecord->modname;
	    //$replaceValues['[p_code]'] = $currentRecord->progcode;
	    //$replaceValues['[p_name]'] = $currentRecord->progname;
	    $replaceValues['[e_key]'] = '';
	    $replaceValues['[full_link]'] = $CFG->wwwroot .'/blocks/cmanager/comment.php?id=' . $mid;
	    $replaceValues['[loc]'] = '';
		$replaceValues['[req_link]'] = $CFG->wwwroot .'/blocks/cmanager/view_summary.php?id=' . $mid;
	    
	    
		   
		email_comment_to_user($message, $user_id, $mid, $replaceValues);
		
		
		email_comment_to_admin($message, $mid, $replaceValues);
		
		

		echo "<script> window.location = 'comment.php?id=$mid';</script>";

  } else {
        

         
   print_header_simple($streditinga='', '',"<a href=\"module_manager.php\">".get_string('cmanagerDisplay','block_cmanager')."</a> ->", $mform->focus(), "", false);
	    $mform->set_data($mform);
	    $mform->display();
	    
	     $OUTPUT->footer();
	  
 
}





?>
