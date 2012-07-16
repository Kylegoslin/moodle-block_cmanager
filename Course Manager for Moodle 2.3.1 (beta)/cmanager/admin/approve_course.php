 <?php
/* --------------------------------------------------------- 

     COURSE REQUEST BLOCK FOR MOODLE  

     2012 Kyle Goslin & Daniel McSweeney
     Institute of Technology Blanchardstown
     Dublin 15, Ireland
 --------------------------------------------------------- */

require_once("../../../config.php");
global $CFG, $DB;
$formPath = "$CFG->libdir/formslib.php";
require_once($formPath);
require_once('../validate_admin.php');
require_once('../generate_summary.php');

?>
<title>Course Manager</title>
<link rel="stylesheet" type="text/css" href="css/main.css" />
<SCRIPT LANGUAGE="JavaScript" SRC="http://code.jquery.com/jquery-1.6.min.js">
</SCRIPT>

<script language="javascript" type="text/javascript">
<!--
function popitup(url) {
	newwindow=window.open(url,'name','height=300,width=350');
	if (window.focus) {newwindow.focus()}
	return false;
}

// -->
</script>

<?php

/** Navigation Bar **/
$PAGE->navbar->ignore_active();
$PAGE->navbar->add(get_string('cmanagerDisplay', 'block_cmanager'), new moodle_url('/blocks/cmanager/cmanager_admin.php'));
$PAGE->navbar->add(get_string('approvecourse', 'block_cmanager'));
require_login();

$PAGE->set_url('/blocks/cmanager/admin/approve_course.php');
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


	
 	$rec =  $DB->get_record('cmanager_records', array('id'=>$mid));



	$mform =& $this->_form; // Don't forget the underscore! 
	$mform->addElement('header', 'mainheader', get_string('courserequestadmin','block_cmanager'));

	// Page description text
	$mform->addElement('html', '<p></p>&nbsp;&nbsp;&nbsp;
				    <a href="../cmanager_admin.php">< '.get_string('back','block_cmanager').'</a>
				    <p></p>');
				    
	
	
	// Get a list of all the lecturers
	$lecturerHTML = '';
	
	
	$req_values = $rec->req_values;
	if(!empty($req_values)){
		$validCourse = True;
		if (! $course = $DB->get_record("course", array("id", $req_values)) ) {
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
		
	$lecturerHTML = '<a href="'.$CFG->wwwroot.'/user/view.php?id='.$rec->createdbyid.'&amp;course='.SITEID.'">'.$fullname.'</a>';
	}
	
	$page1_fieldname1 = $DB->get_field_select('cmanager_config', 'value', "varname = 'page1_fieldname1'");
	$page1_fieldname2 = $DB->get_field_select('cmanager_config', 'value', "varname = 'page1_fieldname2'");
	
	
	
	$outputHTML = '<center><div id="existingrequest"> 
		
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
				<b>' . $page1_fieldname1. ':</b>
			</td>
			<td>
				'. $rec->modcode . '
			</td>
		</tr>
		<tr>
			<td width="150px">
				<b> ' . $page1_fieldname2 . ':</b>
			</td>
			<td>
				'. $rec->modname . '
			</td>
		</tr>
		<tr>';
				
				
		if 	(isset($rec->modkey)){
			
			$outputHTML .= '
					<tr>
					<td width="150px">
						<b> ' . get_string('configure_EnrolmentKey','block_cmanager'). ': </b>
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
	 </table>

	
	<a href="#" onclick="return popitup(\'showcoursedetails.php?id='.$mid.'\')"
	>[' . get_string('requestReview_OpenDetails','block_cmanager'). ']</a> - <a href="approve_course_new.php">' . get_string('requestReview_ApproveRequest','block_cmanager'). '</a>	
	</center>
		';


	$mform->addElement('html', $outputHTML);


	}
}


   $mform = new courserequest_form();//name of the form you defined in file above.

   	if ($mform->is_cancelled()){
     
	} 
	
	else if ($fromform=$mform->get_data()){
	

  	} 
  
  	else {
        
 	print_header_simple($streditinga='', '',
	    
	"<a href=\"cmanager_admin.php\">".get_string('cmanagerDisplay','block_cmanager')."</a> ->
	", $mform->focus(), "", false);
	
	$mform->set_data($mform);
	$mform->display();
	
	
	echo $OUTPUT->footer();
	}





?>
