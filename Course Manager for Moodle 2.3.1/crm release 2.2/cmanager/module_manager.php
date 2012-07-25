<link rel="stylesheet" type="text/css" href="css/main.css" />
  <link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js"></script>
  <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
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

<title>Course Request Manager</title>

<?php
global $CFG, $DB;

require_once("../../config.php");
$formPath = "$CFG->libdir/formslib.php";
require_once($formPath);
require_once('generate_summary.php');
require_login();


/** Navigation Bar **/
$PAGE->navbar->ignore_active();
$PAGE->navbar->add(get_string('cmanagerDisplay', 'block_cmanager'), new moodle_url('/blocks/cmanager/module_manager.php'));
$PAGE->set_url('/blocks/cmanager/module_manager.php');
$PAGE->set_context(get_system_context());



class courserequest_form extends moodleform {
 
    function definition() {

	global $CFG, $DB, $USER;
 
    $mform =& $this->_form; // Don't forget the underscore! 
	$mform->addElement('header', 'mainheader', get_string('cmanagerDisplay','block_cmanager'));
	$mform->addElement('html', '<p></p>&nbsp;&nbsp;&nbsp;'.get_string('cmanagerWelcome','block_cmanager').' &nbsp;
			<p></p><br>
			&nbsp;&nbsp;<INPUT TYPE="BUTTON" VALUE="'.get_string('cmanagerRequestBtn','block_cmanager').'" ONCLICK="window.location.href=\'course_request.php?new=1\'"><br>
			<p></p><p></p>&nbsp;');

	$uid = $USER->id;
	

	// Get the list of pending requests
	//$selectQuery = "createdbyid = $uid AND status = 'PENDING'";
	//$pendingList = $DB->get_recordset_select('block_cmanager_records', $select=$selectQuery, $sort='', $fields='*', $limitfrom='', $limitnum='');
	
	$pendingList = $DB->get_records('block_cmanager_records',array('createdbyid' => "$uid AND" , 'status' => 'PENDING'), 'id DESC');



	
   $outputHTML = '<div id="pendingrequestcontainer">';

   foreach($pendingList as $rec){
	

			// Get a list of all the lecturers
		
			$lecturerHTML = '';
			
			
			$req_values = $rec->req_values;
			if(!empty($req_values)){
				$validCourse = True;
				if (! $course = $DB->get_record("course", array('id'=>$req_values)) ) {
					   $validCourse = False;
				}
				if($validCourse == True){
		
				    $context = get_context_instance(CONTEXT_COURSE, $course->id); 
				    if ($managerroles = get_config('', 'coursemanager')) {
					$coursemanagerroles = explode(',', $managerroles);
					foreach ($coursemanagerroles as $roleid) {
					    $role = $DB->get_record('role','id',$roleid);
					    $canseehidden = has_capability('moodle/role:viewhiddenassigns', $context);
					    $roleid = (int) $roleid;
					    $namesarray = null;
					    if ($users = get_role_users($roleid, $context, true, '', 'u.lastname ASC', $canseehidden)) {
						
						    foreach ($users as $teacher) {
						    $fullname = fullname($teacher, has_capability('moodle/site:viewfullnames', $context)); 
						    $namesarray[] = ' <a href="'.$CFG->wwwroot.'/user/view.php?id='.
						                    $teacher->id.'&amp;course='.SITEID.'">'.$fullname.'</a>';
						}
					    }          
					}
					if (!empty($namesarray)) {
					    $lecturerHTML =  implode(', ', $namesarray);
					   
					} 
				    }
				}
		
			} else {
				// Get the id from who created the record, and get their username
				$fullname = $DB->get_field('user', 'username', array('id'=> $rec->createdbyid));
				
				$lecturerHTML = '<a href="'.$CFG->wwwroot.'/user/view.php?id='.
						                    $rec->createdbyid.'&amp;course='.SITEID.'">'.$fullname.'</a>';
			}
		
		
		
		
			//Get the latest comment
			$latestComment = '';
			$currentModId = $rec->id;
		 	$modRecords = $DB->get_records('block_cmanager_comments', array('instanceid' =>$currentModId));
			
			foreach($modRecords as $commentRecord){
				$latestComment = $commentRecord->message;
				if(strlen($latestComment) > 55){
					$latestComment = substr($latestComment, 0, 55);
					$latestComment .= '... <a href="comment.php?id=' . $commentRecord->id . '">[View More]</a>';
				}
			}
		
		
		$page1_fieldname1 = '';
		$page1_fieldname2 = '';
		$page1_fieldname3 = '';
		$page1_fieldname4 = '';
		
		
		$page1_fieldname1 = $DB->get_field_select('block_cmanager_config', 'value', "varname = 'page1_fieldname1'");
		$page1_fieldname2 = $DB->get_field_select('block_cmanager_config', 'value', "varname = 'page1_fieldname2'");
		$page1_fieldname4 = $DB->get_field_select('block_cmanager_config', 'value', "varname = 'page1_fieldname4'");
		
		
		//-------------------------------------------------------------
		// Get all the record information
		// ------------------------------------------------------------
			$outputHTML .= '
			
			<div id="existingrequest" style="border-top:1px solid black; height:300px"> 
			
			
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
						<b>'.get_string('requestReview_creationDate','block_cmanager').':</b>
					</td>
					<td>
						'. $rec->createdate . '
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
						<b>' . $page1_fieldname1 . ':</b>
					</td>
					<td>
						'. $rec->modcode . '
					</td>
				</tr>
				<tr>
					<td width="150px">
						<b> '. $page1_fieldname2 .':</b>
					</td>
					<td>
						'. $rec->modname . '
					</td>
				</tr>';
				
				
		if 	(isset($rec->modkey)){
			
			$outputHTML .= '
					<tr>
					<td width="150px">
						<b> '. $page1_fieldname4 .':</b>
					</td>
					<td>
						'. $rec->modkey . '
					</td>
				</tr>';
			
		}
							
		
		$outputHTML .= '
		' . generateSummary($rec->id, $rec->formid) . '
		
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
		
				<tr>
					<td width="150px">
						<b>'.get_string('comments','block_cmanager').':</b>
					</td>
					<td>
						'. $latestComment . '
					</td>
		
				</tr>
			 </table></div>
				';
		
		
			
		
		
			$outputHTML .= '
				<div style="float:right; font-size:12px">
				<table width="130px">
				<tr>
					<td>
						<A href="view_summary.php?id=' . $rec->id .'">'.get_string('view','block_cmanager').'</a>		
					</td>
				</tr>
				<tr>
					<td>
						<A href="course_request.php?edit=' . $rec->id .'">'.get_string('edit','block_cmanager').'</a>		
					</td>
				</tr>
		
				<tr>
					<td>
						<a onclick="cancelConfirm('. $rec->id .',\''.get_string('cmanagerConfirmCancel','block_cmanager').'\')" href="#">'.get_string('cancel','block_cmanager').'</a>		
					</td>
				</tr>
				<tr>
					<td>
						<A href="comment.php?id=' . $rec->id . '">'.get_string('addviewcomments','block_cmanager').'</a>	
					</td>
				</tr>
				</table>
				
				</div>
				
		
			
			</div>
		
		
			
			
			';
			

	

    } // loop end
    
   
   
    
 

	
	$outputHTML .= "</div> ";	 
   
 
    

 		
 
    
     
// ---------------- Historic Requests ----------------------------------------------- //

$secondTabHTML = '';


 		// Existing Requests
 		$secondTabHTML .= '<center>
			<div id="twobordertitle">
				<div style="text-align: left; float: left">&nbsp;<b>'.get_string('cmanagerHistoryTab','block_cmanager').'</b></div> 
				<div style="text-align: right"><b>'.get_string('cmanagerActions','block_cmanager').'</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
			</div>

		';


    global $USER;
	$uid = $USER->id;   
	$selectQuery = "createdbyid = $uid AND status = 'COMPLETE' OR createdbyid = $uid AND status = 'REQUEST DENIED' ORDER BY id DESC";
	//$DB->sql_order_by_text('id', $numchars=32);
	$pendingList = $DB->get_recordset_select('block_cmanager_records', $select=$selectQuery);



		$page1_fieldname1 = $DB->get_field_select('block_cmanager_config', 'value', "varname = 'page1_fieldname1'");
		$page1_fieldname2 = $DB->get_field_select('block_cmanager_config', 'value', "varname = 'page1_fieldname2'");
		$page1_fieldname4 = $DB->get_field_select('block_cmanager_config', 'value', "varname = 'page1_fieldname4'");

 

   foreach($pendingList as $rec){
	
	// Get a list of all the lecturers
	$lecturerHTML = '';
	$req_values = $rec->req_values;
	 
	if(!empty($req_values)){
	
				
			$validCourse = True;
				if (! $course = $DB->get_record("course", array("id"=>$req_values))) {
			   		$validCourse = False;
				}
			if($validCourse == True){

			    $context = get_context_instance(CONTEXT_COURSE, $course->id); 
			    if ($managerroles = get_config('', 'coursemanager')) {
					$coursemanagerroles = explode(',', $managerroles);
					foreach ($coursemanagerroles as $roleid) {
					    $role = $DB->get_record('role',array('id'=>$roleid));
					    $canseehidden = has_capability('moodle/role:viewhiddenassigns', $context);
					    $roleid = (int) $roleid;
					    $namesarray = null;
					    if ($users = get_role_users($roleid, $context, true, '', 'u.lastname ASC', $canseehidden)) {
						
						    foreach ($users as $teacher) {
						    $fullname = fullname($teacher, has_capability('moodle/site:viewfullnames', $context)); 
						    $namesarray[] = '<a href="'.$CFG->wwwroot.'/user/view.php?id='.
						                    $teacher->id.'&amp;course='.SITEID.'">'.$fullname.'</a>';
							}
					    }          
					}
					if (!empty($namesarray)) {
					    $lecturerHTML =  implode(', ', $namesarray);
					   
					} 
			    }
			}

	} else {
		// Get the id from who created the record, and get their username

	$fullname = $DB->get_field('user', 'username', array('id'=> $rec->createdbyid));
		$lecturerHTML = '<a href="'.$CFG->wwwroot.'/user/view.php?id='.
			                    $rec->createdbyid.'&amp;course='.SITEID.'">'.$fullname.'</a>';
	
	
	}



	
	
			//Get the latest comment
			$latestComment = '';
			$currentModId = $rec->id;
		 	$modRecords = $DB->get_records('block_cmanager_comments', array('instanceid' =>$currentModId));
			
			foreach($modRecords as $commentRecord){
				$latestComment = $commentRecord->message;
				if(strlen($latestComment) > 55){
					$latestComment = substr($latestComment, 0, 55);
					$latestComment .= '... <a href="comment.php?id=' . $commentRecord->id . '">[View More]</a>';
				}
			}
	


	$secondTabHTML .= '<center><div id="existingrequest" style="border-top:1px solid black; height:300px"> 
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
				<b>'.get_string('requestReview_creationDate','block_cmanager').':</b>
			</td>
			<td>
				'. $rec->createdate . '
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
				<b>'.get_string('requestReview_courseName','block_cmanager').'</b>
			</td>
			<td>
				'. 		$page1_fieldname1 . '
			</td>
		</tr>
		<tr>
			<td width="150px">
				<b>'.get_string('requestReview_courseCode','block_cmanager').'</b>
			</td>
			<td>
				'. 		$page1_fieldname2 . '
			</td>
		</tr>
	' . generateSummary($rec->id, $rec->formid) . '
		<tr>
			<td width="150px">
				<b>'.get_string('requestReview_originator','block_cmanager').':</b>
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

		<tr>
			<td width="150px">
				<b>'.get_string('comments','block_cmanager').':</b>
			</td>
			<td>
				'. $latestComment . '
			</td>

		</tr>
	 </table></div>
		';


	


	$secondTabHTML .= '
		<div style="float:right; font-size:12px">
		<table width="130px">
		<tr>
			<td>
				<A href="view_summary.php?id=' . $rec->id .'">'.get_string('view','block_cmanager').'</a>		
			</td>
		</tr>
		<tr>
			<td>
				<A href="course_request.php?edit=' . $rec->id .'">'.get_string('edit','block_cmanager').'</a>		
			</td>
		</tr>

		<tr>
			<td>
				<a onclick="cancelConfirm('. $rec->id .',\''.get_string('cmanagerConfirmCancel','block_cmanager').'\')" href="#">'.get_string('cancel','block_cmanager').'</a>	
			</td>
		</tr>
		<tr>
			<td>
				<A href="comment.php?id=' . $rec->id . '">'.get_string('addviewcomments','block_cmanager').'</a>	
			</td>
		</tr>
		</table>
		</div>
	</div>

	</center>
	<hr style="width:700px"/>
	';


    }
     




 

	
	
    		// Existing Requests
 		$mform->addElement('html', '<center>
 		
 		<script>
		  $(document).ready(function() {
		    $("#tabs").tabs();
		  });
		  </script>
		<div id="tabs">
		    <ul>
		        <li><a href="#fragment-1"><span>'.get_string('cmanagerExstingTab','block_cmanager').'</span></a></li>
		        <li><a href="#fragment-2"><span>'.get_string('cmanagerHistoryTab','block_cmanager').'</span></a></li>
		    </ul>
    
    
     <div id="fragment-2">	
     <p></p>
      '. $secondTabHTML .'
     </div>
     
     
	<div id="fragment-1">	
 	<p></p>
	<div id="twobordertitle">
		<div style="text-align: left; float: left">&nbsp;<b>'.get_string('cmanagerExstingTab','block_cmanager').'</b></div> 
		<div style="text-align: right"><b>'.get_string('cmanagerActions','block_cmanager').'</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
	 </div>

			

					
			
			'.$outputHTML.'
			
			
			
			</div>
	 			
			');
 		
	
	


    } // Close the function
    
    
  
    
} // Close the class



  



		$mform = new courserequest_form();

		if ($mform->is_cancelled()){
			echo "<script>window.location='module_manager.php';</script>";
			die;

		} else if ($fromform=$mform->get_data()){

		} else {

		    
		    print_header_simple('', "", $mform->focus(), "", false);
		    
		   
		    $mform->display();
		  
		
		}


	$OUTPUT->footer();


?>
 