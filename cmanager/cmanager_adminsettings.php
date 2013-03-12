<?php 
/* -----------------------------------------------------------------
 * 
 * 
 *  Course Request Manager
 *  2012-2013 Kyle Goslin, Daniel McSweeney
 * 
 * 
 * 
 * 
 * ------------------------------------------------------------------
 */

require_once("../../config.php");
global $CFG, $DB;
require_once("$CFG->libdir/formslib.php");
require_login();
require_once('validate_admin.php');

$PAGE->set_url('/blocks/cmanager/cmanager_othersettings.php');
$PAGE->set_context(get_system_context());


/** Navigation Bar **/
$PAGE->navbar->ignore_active();
$PAGE->navbar->add(get_string('cmanagerDisplay', 'block_cmanager'), new moodle_url('/blocks/cmanager/cmanager_admin.php'));
$PAGE->navbar->add(get_string('configurecoursemanagersettings', 'block_cmanager'), new moodle_url('/blocks/cmanager/cmanager_confighome.php'));
$PAGE->navbar->add(get_string('configureadminsettings', 'block_cmanager'));
$PAGE->set_heading(get_string('pluginname', 'block_cmanager'));
$PAGE->set_title(get_string('pluginname', 'block_cmanager'));
echo $OUTPUT->header();
?>


<link rel="stylesheet" type="text/css" href="css/main.css" />
<script src="js/jquery/jquery-1.7.2.min.js"></script>
  
  <script>
  $(document).ready(function() {
    $("#accordion").accordion();
  });

  $(document).ready(function() {
    $("#tabs").tabs();
  });


function cancelConfirm(i,langString) {
	var answer = confirm(langString)
	if (answer){
		
		window.location = "cmanager_othersettings.php?t=d&&id=" + i;
	}
	else{
		
	}
}

/*
 * This function is used to save the text from the 
 * list of textareas using ajax.
 */
function saveChangedText(object, idname, langString){

    var fieldvalue = object.value;
   
    
    $.post("ajax_functions.php", { type: 'updatefield', value: fieldvalue, id: idname },
    		   function(data) {
    		     alert("Changes have been saved!");
    		   });
	
}

</script>




<?php

// If any records were set to be deleted.
if(isset($_GET['t']) && isset($_GET['id'])){

	if(required_param('t', PARAM_TEXT) == 'd'){
	
		$deleteId = required_param('id', PARAM_INT);

		// Delete the record
		$deleteQuery = "id = $deleteId";
		$DB->delete_records_select('block_cmanager_config', $deleteQuery);
	
	    echo "<script>window.location='cmanager_othersettings.php';</script>";
	}
}


//did we make a change to the course name, enrolment key or date?
if(isset($_POST['naming']) && isset($_POST['key']) && isset($_POST['course_date']) &&isset($_POST['defaultmail']) &&isset($_POST['snaming'])){

	 	 
		 //update autoKey
	 	 $newrec = new stdClass();
		 $rowId = $DB->get_field_select('block_cmanager_config', 'id', "varname = 'autoKey'");	 
		 $newrec->id = $rowId;	 
	     $newrec->varname = 'autoKey';
	     $newrec->value = $_POST['key'];
  	     $DB->update_record('block_cmanager_config', $newrec); 

		//update naming
	 	 $newrec = new stdClass();
		 $rowId = $DB->get_field_select('block_cmanager_config', 'id', "varname = 'naming'");	 
		 $newrec->id = $rowId;	 
	     $newrec->varname = 'naming';
	     $newrec->value = $_POST['naming'];
  	     $DB->update_record('block_cmanager_config', $newrec); 
	
		//self car
	 	 $newrec = new stdClass();
		 $rowId = $DB->get_field_select('block_cmanager_config', 'id', "varname = 'selfcat'");	 
		 $newrec->id = $rowId;	 
	     $newrec->varname = 'selfcat';
	     $newrec->value = $_POST['selfcat'];
  	     $DB->update_record('block_cmanager_config', $newrec); 
	
	
		//update snaming
	 	 $newrec = new stdClass();
		 $rowId = $DB->get_field_select('block_cmanager_config', 'id', "varname = 'snaming'");	 
		 $newrec->id = $rowId;	 
	     $newrec->varname = 'snaming';
	     $newrec->value = $_POST['snaming'];
  	     $DB->update_record('block_cmanager_config', $newrec); 
	
		//retrieve updated date and convert to timestamp
		$courseTimeStamp = $_POST['course_date'];
		$courseTimeStamp = mktime (0, 0, 0, $courseTimeStamp['M'], $courseTimeStamp['d'], $courseTimeStamp['Y']);
	
		//add the new date to the config
		$newrec = new stdClass();
		$rowId = $DB->get_field_select('block_cmanager_config', 'id', "varname = 'startdate'");	 
		$newrec->id = $rowId;	 
	    $newrec->varname = 'startdate';
	    $newrec->value = $courseTimeStamp;
  	    $DB->update_record('block_cmanager_config', $newrec); 
		echo "<script>alert('".get_string('ChangesSaved','block_cmanager')."');</script>";
	
	
		//update no reply email
		$newrec = new stdClass();
		$rowId = $DB->get_field_select('block_cmanager_config', 'id', "varname = 'emailSender'");	 
		$newrec->id = $rowId;	 
	    $newrec->varname = 'emailSender';
	    $newrec->value = $_POST['defaultmail'];
  	    $DB->update_record('block_cmanager_config', $newrec); 
  	    
		
}

?>
 
<?php



class courserequest_form extends moodleform {
 
    function definition() {
        global $CFG;
        global $currentSess;
		global $mid;
		global $USER, $DB;


        $currentRecord =  $DB->get_record('block_cmanager_records', array('id'=> $currentSess));
	    $mform =& $this->_form; // Don't forget the underscore! 
 	    $mform->addElement('header', 'mainheader', '<span style="font-size:18px"> '.get_string('configureadminsettings','block_cmanager').'</span>');

	    // Back Button
		$mform->addElement('html', '<p></p>&nbsp;&nbsp;&nbsp;<a href="cmanager_confighome.php">&lt ' . get_string('back','block_cmanager') . '</a><p></p>');
	
	/*
		// Email text box
		$approvedTextRecord = $DB->get_record('block_cmanager_config', array('varname'=>'approved_text'));
	
		$emailText = '';
		if($approvedTextRecord != null){
			$emailText = $approvedTextRecord->value;
		}
	
	
			// Approved user email
			$approved_user_email =  $DB->get_record('block_cmanager_config', array('varname'=>'approveduseremail'));
			$approved_user_email_value = '';
			if(!empty($approved_user_email)){
				$approved_user_email_value = $approved_user_email->value;
			}
			
			// Approved admin email
			$approved_admin_email =  $DB->get_record('block_cmanager_config', array('varname'=>'approvedadminemail'));
			$approved_admin_email_value = '';
			if(!empty($approved_admin_email)){
			$approved_admin_email_value = $approved_admin_email->value;
			}
			
			
			// Request new module user
			$request_new_module_user =  $DB->get_record('block_cmanager_config', array('varname'=>'requestnewmoduleuser'));
			$request_new_module_user_value = '';
			if(!empty($request_new_module_user)){
			$request_new_module_user_value = $request_new_module_user->value;
			}
			
			
			// Request new module admin
			$request_new_module_admin =  $DB->get_record('block_cmanager_config', array('varname'=>'requestnewmoduleadmin'));
			$request_new_module_admin_value = '';
			if(!empty($request_new_module_admin)){
				$request_new_module_admin_value = $request_new_module_admin->value;
			}
			
			
		    // Comment email admin
			$comment_email_admin =  $DB->get_record('block_cmanager_config', array('varname'=>'commentemailadmin'));
			$comment_email_admin_value = '';
			if(!empty($comment_email_admin)){
				$comment_email_admin_value = $comment_email_admin->value;
			}
			
		    // Comment email user
			$comment_email_user =  $DB->get_record('block_cmanager_config', array('varname'=>'commentemailuser'));
			$comment_email_user_value = '';
			if(!empty($comment_email_user)){
				$comment_email_user_value = $comment_email_user->value;
			}
			
			
		    // Request denied admin
			$module_request_denied_admin =  $DB->get_record('block_cmanager_config', array('varname'=>'modulerequestdeniedadmin'));
			$module_request_denied_admin_value = '';
			if(!empty($module_request_denied_admin)){
				$module_request_denied_admin_value = $module_request_denied_admin->value;
			}
		
			
			
			// Request denied user
			$module_request_denied_user =  $DB->get_record('block_cmanager_config', array('varname'=>'modulerequestdenieduser'));
			$module_request_denied_user_value = '';
			if(!empty($module_request_denied_user)){
				$module_request_denied_user_value = $module_request_denied_user->value;
			}
			
			
			// Handover current
			$handover_current =  $DB->get_record('block_cmanager_config', array('varname'=>'handovercurrent'));
			$handover_current_value = '';
			if(!empty($handover_current)){
				$handover_current_value = $handover_current->value;
			}
			
			//Handover user
			$handover_user =  $DB->get_record('block_cmanager_config', array('varname'=>'handoveruser'));
			$handover_user_value = '';
			if(!empty($handover_user)){
				$handover_user_value = $handover_user->value;
			}
			
			
			// Handover admin
			$handover_admin =  $DB->get_record('block_cmanager_config', array('varname'=>'handoveradmin'));
			$handover_admin_value = '';
			if(!empty($handover_admin)){
				$handover_admin_value = $handover_admin->value;
			}
			*/
			
			$statsCode = get_string('totalRequests','block_cmanager').':';
			$whereQuery = "varname = 'admin_email'";
		 	$modRecords = $DB->get_recordset_select('block_cmanager_config', $whereQuery);
			
			
			//get the current values for naming and autoKey from the database and use in the setting of seleted values for dropdowns
		
			$autoKey = $DB->get_field_select('block_cmanager_config', 'value', "varname = 'autoKey'");	
			$naming = $DB->get_field_select('block_cmanager_config', 'value', "varname = 'naming'");
			$snaming = $DB->get_field_select('block_cmanager_config', 'value', "varname = 'snaming'");
			$emailSender = $DB->get_field_select('block_cmanager_config', 'value', "varname = 'emailSender'");
	
			$selfcat = $DB->get_field_select('block_cmanager_config', 'value', "varname = 'selfcat'");
	
	
	
	
/////////////////////////////////////////////////////////////////////////////////////////////////////////
//fragment 2 (placed on tab 2)
/////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	$fragment2 = '
	<div id="fragment-2" style="padding-left: 2em;">'.
	
	get_string('namingConvetion','block_cmanager').'
	
		<div style="font-size: 12px">
			<p></p>
			'.get_string('namingConvetionInstruction','block_cmanager').'
			<br><br>
	
		<form action="cmanager_othersettings.php" method="post">
			<select name="naming">';
		
			if($naming == 1){
				$fragment2 .='
				<option value="1" selected="selected">'.get_string('namingConvetion_option1','block_cmanager').'</option>
				<option value="2">'.get_string('namingConvetion_option2','block_cmanager').'</option>
				<option value="3">'.get_string('namingConvetion_option3','block_cmanager').'</option>';
			}
		
			else if ($naming == 2){
				$fragment2 .='
				<option value="1">'.get_string('namingConvetion_option1','block_cmanager').'</option>
				<option value="2" selected="selected">'.get_string('namingConvetion_option2','block_cmanager').'</option>
				<option value="3">'.get_string('namingConvetion_option3','block_cmanager').'</option>';
			}
		
			else if ($naming == 3){
				$fragment2 .='
				<option value="1">'.get_string('namingConvetion_option1','block_cmanager').'</option>
				<option value="2">'.get_string('namingConvetion_option2','block_cmanager').'</option>
				<option value="3" selected="selected">'.get_string('namingConvetion_option3','block_cmanager').'</option>';
			}
		
	$fragment2 .='
			</select>
			<p></p>
			<br>
			<hr>
			<br>
			<p></p>
		</div>

	'.get_string('snamingConvetion','block_cmanager').'
	
	<div style="font-size: 12px">
		<p></p>
		'.get_string('snamingConvetionInstruction','block_cmanager').'
		<br><br>
		<select name="snaming">';
		
			if($snaming == 1){
				$fragment2 .='
				<option value="1" selected="selected">'.get_string('snamingConvetion_option1','block_cmanager').'</option>
				<option value="2">'.get_string('snamingConvetion_option2','block_cmanager').'</option>';
			}
		
			else if ($snaming == 2){
				$fragment2 .='
				<option value="1">'.get_string('snamingConvetion_option1','block_cmanager').'</option>
				<option value="2" selected="selected">'.get_string('snamingConvetion_option2','block_cmanager').'</option>';
			}
		
	
	$fragment2 .='
		</select>
		<p></p>
		<br>
		<hr>
		<br>
		<p></p>
	</div>
	'.get_string('configure_EnrolmentKey','block_cmanager').'
	
	<div style="font-size: 12px">
		<p></p>
		'.get_string('cmanagerEnrolmentInstruction','block_cmanager').'<br><br>
		<select name="key">';
		
		if($autoKey == 1){
			$fragment2 .='
			<option value="1" selected="selected">'.get_string('cmanagerEnrolmentOption1','block_cmanager').'</option>
			<option value="0">'.get_string('cmanagerEnrolmentOption2','block_cmanager').'</option>';
		}
		
		else{
			$fragment2 .='
			<option value="1">'.get_string('cmanagerEnrolmentOption1','block_cmanager').'</option>
			<option value="0" selected="selected">'.get_string('cmanagerEnrolmentOption2','block_cmanager').'</option>';
		}
		
	$fragment2 .='
		</select>
			<hr>
			<p></p>
			<div style="font-size: 14px">
			'.get_string('clearHistoryTitle','block_cmanager').'
			</div>
			<p></p>
				<div style="font-size: 12px">
				

			<script>
			function deleteAll(){
				window.location="history/delete.php?delete=all";
			}
			function deleteArchOnly(){
				window.location="history/delete.php?delete=archonly";
			}
			</script>
				<input type="button" onClick="deleteAll()" value="'.get_string('deleteAllRequests', 'block_cmanager').'"><p></p>
				<input type="button" onClick="deleteArchOnly()" value="'.get_string('deleteOnlyArch', 'block_cmanager').'">
				
				
				</div>
		<p></p>
		<br>
		<hr>
		<br>
			<p></p>
			<div style="font-size: 14px">
			'.get_string('allowSelfCategorization','block_cmanager').'
			</div>
			<p></p>
			'.get_string('allowSelfCategorization_desc', 'block_cmanager').'
			<p></p>';
			
			
		if($selfcat == 'yes'){
		 $fragment2 .= '
		 	<div style="font-size: 12px">
					<select name="selfcat">
					<option value="yes" selected="selected">'.get_string('selfCatOn', 'block_cmanager').'</option>
					<option value="no">'.get_string('selfCatOff', 'block_cmanager').'</option>
					</select>
				</div>
		 ';
		} else if($selfcat == 'no'){
			 $fragment2 .= '
		 	<div style="font-size: 12px">
					<select name="selfcat">
					<option value="yes">'.get_string('selfCatOn', 'block_cmanager').'</option>
					<option value="no" selected="selected">'.get_string('selfCatOff', 'block_cmanager').'</option>
					</select>
				</div>
		 ';
		}

	
			
				
		$fragment2 .='		
				
		<p></p>
		<br>
		<hr>
		<br>
		<p></p>
	</div>
	
	'.get_string('email_noReply','block_cmanager').'
	
	<div style="font-size: 12px">
		<p></p>
		'.get_string('email_noReplyInstructions','block_cmanager').'
		<p></p>
		'.get_string('config_addemail','block_cmanager').'
		<input type="text" name="defaultmail"  size="50" id="defaultemail" value="'.$emailSender.'"/>	
		<p></p>
		<br>
		<hr>
		<br>
	</div>
	
	'.get_string('configure_defaultStartDate','block_cmanager').'
	
	<div style="font-size: 12px">
	
	<p></p>
	'.get_string('configure_defaultStartDateInstructions','block_cmanager').'<br><br>
	
	<!--ADD A DATE PICKER -->
	
	

	
	
	
	
	';	
				
	
	
	
/////////////////////////////////////////////////////////////////////////////////////////////////////////
//saveall 2 (placed under fragmen 2
/////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	$saveall = '
	</div>		
	<br><br><br>	
	<span style="font-size:12px"><center><input type="submit" value="'.get_string('SaveAll','block_cmanager').'" /></center></span>
	</form>
</div> <!--end of fragment 2 -->
</div><!--tabs tag -->
';
	
	
		
		
	$mainSlider = '	
		<p></p> 
		&nbsp;
		<p></p>	
    '.$fragment2.'    
    
	
';
	
			//add the main slider 		
			$mform->addElement('html', $mainSlider);
			
			
			$timestamp_startdate = $DB->get_field_select('block_cmanager_config', 'value', "varname = 'startdate'");	
			//convert to date
			$startdate = getdate($timestamp_startdate);
			//add the date selector and set defaults
			$date_options = array('format' => 'dMY', 'minYear' => 2012, 'maxYear' => 2020); 
			
			$mform->addElement('date', 'course_date', 'Date:', $date_options);
			$date_defaults = array('d' => $startdate['mday'], 'M' => $startdate['mon'], 'Y' => $startdate['year']);
			$mform->setDefaults(array('course_date' => $date_defaults));
		
			//close off the html and form
			$mform->addElement('html', $saveall);
	}
}

   $mform = new courserequest_form();

   if ($mform->is_cancelled()){
        
	echo "<script>window.location='../cmanager_admin.php';</script>";
			die;

  } else if (isset($_POST['addemailbutton'])){
			global $USER;
			global $CFG;
		
			// Add an email address
			$post_email = addslashes($_POST['newemail']);
			
	        if($post_email != '' && validateEmail($post_email)){
	        	$newrec = new stdClass();
				$newrec->varname = 'admin_email';
				$newrec->value = $post_email;
				$DB->insert_record('block_cmanager_config', $newrec);
	            	
			}
	        
	        echo "<script>window.location='cmanager_othersettings.php';</script>";
	        die;
	
  } else {
  			
 			$mform->focus();
		    $mform->set_data($mform);
		    $mform->display();
		   	echo $OUTPUT->footer();
	  
	  
 
}


/*
 * Very basic funciton for validating an email address.
 * This should really be replaced with something a little better!
 */
function validateEmail($email){

	$valid = true;
	
	
	
	if($email == ''){
	   $valid = false;
	}
	
	$pos = strpos($email, '.');
	if($pos === false){
		$valid = false;
	}
	
	$pos = strpos($email, '@');
	if($pos === false){
		$valid = false;
	}
	
	if($valid){
	   return true;
	} else {
		return false;
	}

}


?>

	