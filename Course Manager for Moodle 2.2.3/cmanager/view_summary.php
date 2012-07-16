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
require_login();
require_once('generate_summary.php');
?>
<title>Course Manager</title>
<link rel="stylesheet" type="text/css" href="css/main.css" />
<SCRIPT LANGUAGE="JavaScript" SRC="http://code.jquery.com/jquery-1.6.min.js">
</SCRIPT>
<?php


/** Navigation Bar **/
$PAGE->navbar->ignore_active();
$PAGE->navbar->add(get_string('cmanagerDisplay', 'block_cmanager'), new moodle_url('/blocks/cmanager/module_manager.php'));
$PAGE->navbar->add(get_string('viewsummary', 'block_cmanager'));

$PAGE->set_url('/blocks/cmanager/view_summary.php');
$PAGE->set_context(get_system_context());


if(isset($_GET['id'])){
	$mid = $_GET['id'];
	$_SESSION['mid'] = $mid;
} else {

	$mid = $_SESSION['mid'];
}



class courserequest_form extends moodleform {
 
    function definition() {
        global $CFG;
        global $currentSess;
	    global $mid;
	    global $USER, $DB;


    //$currentRecord =  get_record('cmanager_records', 'id', $currentSess);
    $rec =  $DB->get_record('cmanager_records', array('id'=>$mid));

    $mform =& $this->_form; // Don't forget the underscore! 
 
	$mform->addElement('header', 'mainheader', get_string('cmanagerDisplay','block_cmanager'));



	// Page description text
	$mform->addElement('html', '<p></p>&nbsp;&nbsp;&nbsp;
				    <a href="module_manager.php">< '.get_string('back','block_cmanager').'</a>
				    <p></p>');
				    

	
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
			    $role = $DB->get_record('role', array('id'=>$roleid));
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
		$fullname = $DB->get_field('user', 'username', array('id'=>$rec->createdbyid));
		
		$lecturerHTML = '<a href="'.$CFG->wwwroot.'/user/view.php?id='.
				                    $rec->createdbyid.'&amp;course='.SITEID.'">'.$fullname.'</a>';
	}
	
	
	
	

 		
		$page1_fieldname1 = $DB->get_field_select('cmanager_config', 'value', "varname = 'page1_fieldname1'");
		$page1_fieldname2 = $DB->get_field_select('cmanager_config', 'value', "varname = 'page1_fieldname2'");
	

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
				<b>'.$page1_fieldname1.':</b>
			</td>
			<td>
				'. 		$page1_fieldname1 . '
			</td>
		</tr>
		<tr>
			<td width="150px">
				<b>'.$page1_fieldname2.':</b>
			</td>
			<td>
				'. 		$page1_fieldname2 . '
			</td>
		</tr>
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
				
			</td>

		</tr>
	 </table>
		</div></div>
		';







	$mform->addElement('html', $outputHTML);





	$mform->addElement('html', '<p></p>&nbsp;');
	
	$whereQuery = "instanceid = '$mid' ORDER BY id DESC";
 	$modRecords = $DB->get_recordset_select('cmanager_comments', $whereQuery);
	$htmlOutput = '';

	foreach($modRecords as $record){
		
		// Get the username of the person
		$username = $DB->get_field('user', 'username', array('id'=>$record->createdbyid));
		
	  	$htmlOutput .='	<tr>';
		$htmlOutput .=' <td width="150px">' . $record->dt . '</td>';
		$htmlOutput .=' <td width="300px">' . $record->message . '</td>';
		$htmlOutput .=' <td width="100px">' . $username .'</td>';
		$htmlOutput .=' <tr>';

	}


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
} 

else if ($fromform=$mform->get_data()){
	
} else {
		print_header_simple($streditinga='', '', get_string('cmanagerDisplay','block_cmanager')."", $mform->focus(), "", false);
		$mform->set_data($mform);
		$mform->display();
		
 		echo $OUTPUT->footer();
}




?>
