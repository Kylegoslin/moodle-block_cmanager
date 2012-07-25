<?php
require_once("../../config.php");
global $CFG, $DB;
$formPath = "$CFG->libdir/formslib.php";
require_once($formPath);
require_once('generate_summary.php');
require_once('validate_admin.php');

require_login();
?>
<title>Course Request Manager</title>
<link rel="stylesheet" type="text/css" href="css/main.css" />
<link href="js/jquery/jquery-ui18.css" rel="stylesheet" type="text/css"/>
<script src="js/jquery/jquery-1.7.2.min.js"></script>
<script src="js/jquery/jquery-ui.1.8.min.js"></script>
  
<script type="text/javascript">

function cancelConfirm(id,langString) {
	var answer = confirm(langString)
	if (answer){
		
		window.location = "deleteRequest.php?t=a&&id=" + id;
	}
	
}


  $(document).ready(function() {
    $("#tabs").tabs();
    
    <?php 
    
    if(isset($_GET['view'])){
    	if (required_param('view', PARAM_TEXT) == 'history'){
			echo "    $('#tabs').tabs('select', '2');";    		
    	}
    }
    ?>
  });
  </script>

<?php


/** Navigation Bar **/
$PAGE->navbar->ignore_active();
$PAGE->navbar->add(get_string('cmanagerDisplay', 'block_cmanager'), new moodle_url('/blocks/cmanager/cmanager_admin.php'));

$PAGE->set_url('/blocks/cmanager/cmanager_admin.php');
$PAGE->set_context(get_system_context());

?>

<?php


class courserequest_form extends moodleform {
 
    	function definition() {
        global $CFG;
        global $USER, $DB;
        $mform =& $this->_form; // Don't forget the underscore! 
 
	// -----------------------------------------------------------------------
	// CURRENT REQUESTS CODE
	// Get the list of records
	$selectQuery = "status = 'PENDING' ORDER BY id DESC";
	$pendingList = $DB->get_recordset_select('block_cmanager_records', $select=$selectQuery);


   $outputHTML = '';

   foreach($pendingList as $rec){

			// Get a list of all the lecturers
			$lecturerHTML = '';
			$req_values = $rec->req_values;
			
			if(!empty($req_values)){
				if (! $course = $DB->get_record("course", array("id"=> $req_values))) {
					   // If the course doesn't exist anymore, just let the process continue..
					} else { // Otherwise, start the process
						    $context = get_context_instance(CONTEXT_COURSE, $course->id); 
						    if ($managerroles = get_config('', 'coursemanager')) {
									$coursemanagerroles = explode(',', $managerroles);
									foreach ($coursemanagerroles as $roleid) {
									    $role = $DB->get_record('role', array('id'=>$roleid));
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
			} 
			
			else {
				// Get the id from who created the record, and get their username
				
				$fullname = $DB->get_field('user', 'username', array('id'=>$rec->createdbyid));
				
				$lecturerHTML = '<a href="'.$CFG->wwwroot.'/user/view.php?id='.
						                    $rec->createdbyid.'&amp;course='.SITEID.'">'.$fullname.'</a>';
			
			
			}


			//Get the latest comment
			$latestComment = '';
			$currentModId = $rec->id;
	 
		
			$whereQuery = "instanceid = '$currentModId'";
		 	$modRecords = $DB->get_recordset_select('block_cmanager_comments', $whereQuery);
			
					                              
		    foreach($modRecords as $record){
			  
				$latestComment = $record->message;
				if(strlen($latestComment) > 55){
					$latestComment = substr($latestComment, 0, 55);
					$latestComment .= '... <a href="comment.php?id=' . $record->id . '">[View More]</a>';
				}
		    }
			
	
	

			echo "
			<script>
				var checkedIds  = ['null'];
				
				function addIdToList(id){
					var i = checkedIds.length;
					var found = false;
					
					while (i--) {
					
					    if (checkedIds[i] === id) {
					      	checkedIds[i] = 'null';
							found = true;
					    }
					}
					
					if(found === false){
						checkedIds.push(id);
					}
				}
			</script>
				";

			$page1_fieldname1 = $DB->get_field_select('block_cmanager_config', 'value', "varname='page1_fieldname1'");
			$page1_fieldname2 = $DB->get_field_select('block_cmanager_config', 'value', "varname='page1_fieldname2'");
		
	
	
			$outputHTML .= '
			<center>
			
			<div id="existingrequest" style="border-top:1px solid black; height:300px"> <div style="float:left; width:20px">
				<input type="checkbox" name="groupedcheck" onClick="addIdToList(' . $rec->id . ')" value="' . $rec->id . '" />	
			</div>
			
			<div style="float:left">
			 <table width="550px">
				
				<tr>
					
					<td width="150px">
						<b>' . get_string('status','block_cmanager'). ':</b>
					</td>
					<td>
						'. $rec->status . '
					</td>
				</tr>
				
				<tr>	
					<td width="150px">
						<b>' . get_string('creationdate','block_cmanager'). ':</b>
					</td>
					<td>
						'. $rec->createdate . '
					</td>
				</tr>
				
				<tr>	
					<td width="150px">
						<b>' . get_string('requesttype','block_cmanager'). ':</b>
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
						<b>'. $page1_fieldname2 .':</b>
					</td>
					<td>
						'. $rec->modname . '
					</td>
				</tr>';
				
				
				if 	(isset($rec->modkey)){
					
					$outputHTML .= '
						<tr>
							<td width="150px">
								<b> ' . get_string('configure_EnrolmentKey','block_cmanager'). ':</b>
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
						<b>' . get_string('originator','block_cmanager'). ':</b>
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
						<b>' . get_string('comments','block_cmanager'). ':</b>
					</td>
					<td>
						'. $latestComment . '
					</td>
		
				</tr>
			 </table>
			 </div>
			 ';


			$outputHTML .= '
				<div style="float:right; font-size:12px">
				
				<table width="130px">
				<tr>
					<td>
						<a href="admin/approve_course.php?id=' . $rec->id .'">' . get_string('approve','block_cmanager'). '</a>		
					</td>
				</tr>
		
				<tr>
					<td>
						<a href="admin/deny_course.php?id=' . $rec->id .'">' . get_string('deny','block_cmanager'). '</a>		
					</td>
				</tr>
				
				<tr>
					<td>
						<a href="course_request.php?edit=' . $rec->id .'">' . get_string('edit','block_cmanager'). '</a>		
					</td>
				</tr>
		
				<tr>
					<td>
						<a onclick="cancelConfirm('. $rec->id .',\''.get_string('configure_delete','block_cmanager').'\')" href="#">' . get_string('delete','block_cmanager'). '</a>		
					</td>
				</tr>
				
				<tr>
					<td>
						<A href="admin/comment.php?id=' . $rec->id . '">' . get_string('addviewcomments','block_cmanager'). '</a>	
					</td>
				</tr>
				
				</table>
				</div>
			
			</div>
			
			</center>
			
			';

    }


  // --------------------------------------------------------------------------------
  // REQUESTS DROPDOWN 
  
  

	$dropdownHTML= "

			<script>
				function bulkaction(){
				
				var cur = document.getElementById('bulk');
				    	
				if(cur.value == 'Delete'){
							
					$.post(\"ajax_functions.php\", { type: \"del\", values: checkedIds},
						   function(data) {
						    		window.location='cmanager_admin.php';
						    	
						   });						

					}
					
					if(cur.value == 'Deny'){
			    		window.location='admin/bulk_deny.php?mul=' + checkedIds;			
					}
				}
			</script>


			<center>
			<div style=\"width: 700px; text-align:left\">
			<p></p>
			<b>".get_string('bulkactions','block_cmanager')."</b><br>
			".get_string('withselectedrequests','block_cmanager')."<br>
			
			<select id=\"bulk\" onchange='bulkaction();'>
			  <option></option>
			  <option value='".get_string('deny','block_cmanager')."'>".get_string('deny','block_cmanager')."</option>
			  <option value ='".get_string('delete','block_cmanager')."'>".get_string('delete','block_cmanager')."</option>
			</select>
			
			</div>	 
			</center>
			";	 


  // -----------------------------------------------------------------------------------------
  //  ARCH REQUESTS TAB
  //
  
  
  echo "
  <script>
    // Open the selected archived request page

  function goToPage(){
  	var page = document.getElementById('pageNumber');
  	window.location = 'cmanager_admin.php?view=history&p=' + page.value;
  }
  </script>";
  
  
        // Arch Requests Dropdow
        $page1_fieldname1 = $DB->get_field_select('block_cmanager_config', 'value', "varname='page1_fieldname1'");
		$page1_fieldname2 = $DB->get_field_select('block_cmanager_config', 'value', "varname='page1_fieldname2'");
        
        $numberOfRecords = $DB->count_records_sql("SELECT count(id) FROM " . $CFG->prefix ."block_cmanager_records WHERE status = 'COMPLETE' OR status = 'REQUEST DENIED'");
	
        $numberOfPages = floor($numberOfRecords / 10);
        $selectedOption = '';
        $archRequestsDropdown = '
        
        View Page: 
        <select onchange="goToPage();" name="pageNumber" id="pageNumber">';
           
           $i = 1;		   
		   
		   while($i < $numberOfPages+1){
		   	
			if(isset($_GET['p'])){
				if(required_param('p', PARAM_INT) == $i){
					$selectedOption = 'selected = "yes"';
				}
			}
		    $archRequestsDropdown .= '<option ' .$selectedOption .' value="' . $i. '">' . $i. '</option>';
			$i++;
			$selectedOption = '';	
		  }  
		  
		  if($numberOfRecords % 2){
		  	
		  } else {
		  	if(isset($_GET['p'])){
				if(required_param('p', PARAM_INT) == $i){
					$selectedOption = 'selected = "yes"';
				}
			}
		  		  $archRequestsDropdown .= '<option '. $selectedOption.'="' . $i. '"> ' . $i.'</option>';
		  }
		   
		$archRequestsDropdown .= '</select>';
        
  
   		// Archived Requests Header
 		$archRequestsHeader = '<center>

			<div id="twobordertitle">
				<div style="text-align: left; float: left">&nbsp;<b>'. get_string('archivedrequests','block_cmanager').'</b></div> 
				<div style="text-align: right"><b>'. get_string('actions','block_cmanager').'</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
			</div>
			</center>';

  // -----------------------------------------------------------------------------------------
  //
  //
  //  Copy of code for viewing a list of requests, this should be
  //  trimmed down in time.

	
	
	// if a page number is selected
	if(isset($_GET['p'])){
		
		$selected_page_number = required_param('p', PARAM_INT);
		$fromLimit = ($selected_page_number -1) * 10;
		$toLimit = $fromLimit + 10;
	} else {

		$fromLimit = 0;
		$toLimit = 10;
	}
	 
	
	
	
	$pendingList = $DB->get_records_sql("SELECT * FROM ". $CFG->prefix ."block_cmanager_records WHERE status = 'COMPLETE' OR status = 'REQUEST DENIED' order by id desc LIMIT $fromLimit, $toLimit");
	

   
   $archOutputHTML = '';
   foreach($pendingList as $rec){
   
				// Get a list of all the lecturers
				$lecturerHTML = '';
				$req_values = $rec->req_values;
				
				
				if(!empty($req_values)){
					if (! $course = $DB->get_record("course", array("id"=>$req_values))) {
						   // If the course doesn't exist anymore, just let the process continue..
						} else { // Otherwise, start the process
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
					$fullname = $DB->get_field('user', 'username', array('id'=>$rec->createdbyid));
					
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
	
				
				
			
				echo "
				<script>
					var checkedIds  = ['null'];
					function addIdToList(id){
			
						 var i = checkedIds.length;
			
			 			 var found = false;
						while (i--) {
						    if (checkedIds[i] === id) {
						      	checkedIds[i] = 'null';
							found = true;
						    }
						 }
						if(found === false){
							checkedIds.push(id);
						}
			
					
					}
			
				</script>
					";
			
			  	
				$archOutputHTML .= '<center><div id="existingrequest" style="border-top:1px solid black; height:300px"> 
				
				
					<div style="float:left">
				 <table width="550px">
					<tr>
						
						<td width="150px">
							<b>' . get_string('status','block_cmanager'). ':</b>
						</td>
						<td>
							'. $rec->status . '
						</td>
					</tr>
					<tr>
						
						<td width="150px">
							<b>' . get_string('creationdate','block_cmanager'). ':</b>
						</td>
						<td>
							'. $rec->createdate . '
						</td>
					</tr>
					
					<tr>
						
						<td width="150px">
							<b>' . get_string('requesttype','block_cmanager'). ':</b>
						</td>
						<td>
							'. $rec->req_type . '
						</td>
					</tr>
			
			
					<tr>
						<td width="150px">
							<b>
							' . $page1_fieldname1 . '
							</b>
						</td>
						<td>
							'. $rec->modcode . '
						</td>
					</tr>
					<tr>
						<td width="150px">
							<b>'. $page1_fieldname2 .'</b>
						</td>
						<td>
							'. $rec->modname . '
						</td>
					</tr>
					<tr>
			
				
				
				' . generateSummary($rec->id, $rec->formid) . '
				
				
				
					
					<tr>
						<td width="150px">
							<b>' . get_string('originator','block_cmanager'). ':</b>
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
							<b>' . get_string('comments','block_cmanager'). ':</b>
						</td>
						<td>
							'. $latestComment . '
						</td>
			
					</tr>
				 </table></div>
					';
			
			
				
			  
			
				$archOutputHTML .= '
					<div style="float:right; font-size:12px">
					<table width="130px">
					<tr>
						<td>
							<a onclick="cancelConfirm('. $rec->id .', \'delete\')" href="#">' . get_string('delete','block_cmanager'). '</a>		
						</td>
					</tr>
					<tr>
						<td>
							<A href="admin/comment.php?id=' . $rec->id . '">' . get_string('addviewcomments','block_cmanager'). '</a>	
						</td>
					</tr>
					</table>
					</div>
				</div>
			
			
				</center>
				';
				
			    




    }
  

	 
	 
	 $mform->addElement('header', 'mainheader', get_string('courserequestadmin','block_cmanager'));

	 
	 

 		// Existing Requests
 		$existingRequestsHeader = '<center>
			<div id="twobordertitle">
				<div style="text-align: left; float: left">&nbsp;<b>' .  get_string('existingrequests','block_cmanager') .'</b></div> 
				<div style="text-align: right"><b>' .  get_string('actions','block_cmanager') .'</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
			</div>

			</center>';

		
			$mainSlider = '<div id="tabs">
			    	<ul>
			        	<li><a href="#fragment-1"><span>'.get_string('currentrequests','block_cmanager').'</span></a></li>
			        	<li><a href="#fragment-2"><span>'.get_string('archivedrequests','block_cmanager').'</span></a></li>
			    	</ul>
			    
			    	<div id="fragment-1">
			    		<span style="font-size:11px">
					    '. $existingRequestsHeader.'
					    ' . $outputHTML. '
					    ' . $dropdownHTML.'
				    </div>
			    
			    
			    	<div id="fragment-2" style="font-size:11px">
			        '. $archRequestsDropdown.'
				    '. $archRequestsHeader .'
				    '. $archOutputHTML .'
			    	</div>
			</div>
			';
	
	$mform->addElement('html', $mainSlider);

	echo "</div>";	 


    } // Close the function
}  // Close the class






$mform = new courserequest_form();

if ($mform->is_cancelled()){
    
	
} else if ($fromform=$mform->get_data()){
//this branch is where you process validated data.
 
} else {

          print_header_simple('', '', $mform->focus(), "", false);
		    
		    //$mform->set_data($toform);
		   
		    $mform->display();
		
		   
}


echo $OUTPUT->footer();
?>

  
	
		