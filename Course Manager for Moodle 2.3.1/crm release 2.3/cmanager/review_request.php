<?php
/* --------------------------------------------------------- 

     COURSE REQUEST BLOCK FOR MOODLE  

     2012 Kyle Goslin & Daniel McSweeney
     Institute of Technology Blanchardstown
     Dublin 15, Ireland
 --------------------------------------------------------- */

require_once("../../config.php");
global $CFG;
$formPath = "$CFG->libdir/formslib.php";
require_once($formPath);
require_once('generate_summary.php');
require_login();
  
  
?>
<title>Course Manager</title>
<link rel="stylesheet" type="text/css" href="css/main.css" />
<SCRIPT LANGUAGE="JavaScript" SRC="http://code.jquery.com/jquery-1.6.min.js">
</SCRIPT>
<?php

/** Navigation Bar **/
$PAGE->navbar->ignore_active();
$PAGE->navbar->add(get_string('cmanagerDisplay', 'block_cmanager'), new moodle_url('/blocks/cmanager/module_manager.php'));
$PAGE->navbar->add(get_string('modrequestfacility', 'block_cmanager'));

$PAGE->set_url('/blocks/cmanager/review_request.php');
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


    
 		$rec =  $DB->get_record('block_cmanager_records', array('id'=>$mid));

		$field1title = $DB->get_field_select('block_cmanager_config', 'value', "varname='page1_fieldname1'");
		$field2title = $DB->get_field_select('block_cmanager_config', 'value', "varname='page1_fieldname2'");
		

        $mform =& $this->_form; // Don't forget the underscore! 
        $mform->addElement('header', 'mainheader', get_string('requestReview_Summary','block_cmanager'));
        $mform->addElement('html', '<p></p><center>'.get_string('requestReview_intro1','block_cmanager').'<br>'.get_string('requestReview_intro2','block_cmanager').'</center><p></p>&nbsp;<p></p>&nbsp;');
        
        // Get list of lecturers
		$lecturerHTML = $DB->get_field('user', 'username', array('id'=>$rec->createdbyid));
	
		$outputHTML = '<center><div id="existingrequest"> 
		<div style="float:left">
		 <table width="550px">
		<tr>
			
			<td width="150px">
				<b>'.get_string('requestReview_status','block_cmanager').':</b>
			</td>
			<td>
				'. $rec->status . '
			</td>
		</tr>
		<tr>
			
			<td width="150px">
				<b>'.get_string('requestReview_requestType','block_cmanager').':</b>
			</td>
			<td>
				'. $rec->req_type . '
			</td>
		</tr>


		<tr>
			<td width="150px">
				<b>'.$field1title.':</b>
			</td>
			<td>
				'. $rec->modcode . '
			</td>
		</tr>';
							
				
		$outputHTML .= '
		<tr>
			<td width="150px">
				<b>'.$field2title.':</b>
			</td>
			<td>
				'. $rec->modname . '
			</td>
		</tr>'; 
	
	if 	(isset($rec->modkey)){
			
			$outputHTML .= '
					<tr>
					<td width="150px">
						<b> '. get_string('configure_EnrolmentKey','block_cmanager') .': </b>
					</td>
					<td>
						'. $rec->modkey . '
					</td>
				</tr>';
			
		} 


$outputHTML .= generateSummary($rec->id, $rec->formid).'
		<tr>
			<td width="150px">
				<b>'.get_string('requestReview_Originator','block_cmanager').':</b>
			</td>
			<td>
				' . $lecturerHTML . '
			</td>

		</tr>
		
	
		
		<tr>
			<td width="150px">
			&nbsp;
			</td>
			<td>
			&nbsp;	
			</td>

		</tr>

		
	 </table></div></div>
		';

		$mform->addElement('html', $outputHTML);

		$buttonarray=array();
        $buttonarray[] = &$mform->createElement('submit', 'submitbutton', get_string('requestReview_SubmitRequest','block_cmanager'));
        $buttonarray[] = &$mform->createElement('submit', 'alter', get_string('requestReview_AlterRequest','block_cmanager'));
        $buttonarray[] = &$mform->createElement('cancel', 'cancel', get_string('requestReview_CancelRequest','block_cmanager'));
        $mform->addGroup($buttonarray, 'buttonar', '', array(' '), false);
		$mform->addElement('html', '<p></p>&nbsp;');

	}
}




   $mform = new courserequest_form();//name of the form you defined in file above.



   if ($mform->is_cancelled()){

   
   		  // Delete the record
		  $DB->delete_records_select('block_cmanager_records', "id = $mid"); 
		  
		  
	  	  echo "<script>window.location='module_manager.php'</script>";
    	  die;
  } else if ($fromform=$mform->get_data()){
			
  			// If alter was pressed.
			if(isset($_POST['alter'])){
				echo "<script>window.location='course_request.php?edit=$mid'</script>";
				die;
			}
  			

  	  		
  	  		
		
  	  		require_once('cmanager_email.php');
			
  	  		global $mid;
  	  		global $CFG;
  	  		global $USER;
			
			
  	  		$rec = $DB->get_record('block_cmanager_records', array('id'=>$mid));
  	  		$replaceValues = array();
		    $replaceValues['[course_code'] = $rec->modcode;
		    $replaceValues['[course_name]'] = $rec->modname;
		    //$replaceValues['[p_code]'] = $rec->progcode;
		    //$replaceValues['[p_name]'] = $rec->progname;
		    $replaceValues['[e_key]'] = 'No key set';
		    $replaceValues['[full_link]'] = 'Course currently does not exist.';
		    $replaceValues['[loc]'] = 'Location: ';
   			$replaceValues['[req_link]'] = $CFG->wwwroot .'/blocks/cmanager/view_summary.php?id=' . $mid;
	    
		    
		    
		    // Send email to admin saying we are requesting a new mod
			request_new_mod_email_admins($replaceValues);
			
			// Send email to user to track that we are requestig a new mod
  	  		
			request_new_mod_email_user($USER->id, $replaceValues);
			
			
			// Return to module manager
			echo "<script>window.location='module_manager.php'</script>";
      		
			die;
      		
      		
  } else {
        
 
		print_header_simple($streditinga='', '',"<a href=\"module_manager.php\">".get_string('cmanagerDisplay','block_cmanager')."</a> -> ".get_string('modrequestfacility','block_cmanager')."", $mform->focus(), "", false);
		$mform->set_data($mform);
		$mform->display();
		
		$OUTPUT->footer();
 
}

function getUsername($id){

	global $DB;
	
	return $DB->get_field_select('user', 'username', "id = '$id'");

}


?>
