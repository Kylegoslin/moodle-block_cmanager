<?php
/* --------------------------------------------------------- 

     COURSE REQUEST BLOCK FOR MOODLE  

     2012 Kyle Goslin & Daniel McSweeney
     Institute of Technology Blanchardstown
     Dublin 15, Ireland
 --------------------------------------------------------- */
?>

<link rel="stylesheet" type="text/css" href="css/main.css" />
<SCRIPT LANGUAGE="JavaScript" SRC="http://code.jquery.com/jquery-1.6.min.js">
</SCRIPT>

<?php
require_once("../../config.php");
require_once("$CFG->libdir/formslib.php");
require_login();
global $CFG, $DB, $USER;


/** Navigation Bar **/
$PAGE->navbar->ignore_active();
$PAGE->navbar->add(get_string('cmanagerDisplay', 'block_cmanager'), new moodle_url('/blocks/cmanager/module_manager.php'));
$PAGE->navbar->add(get_string('courseexists', 'block_cmanager'));

$PAGE->set_url('/blocks/cmanager/course_exists.php');
$PAGE->set_context(get_system_context());




// Main variable for storing the current session id.
$currentSess = '00';
$currentSess = $_SESSION['cmanager_session'];


class courserequest_form extends moodleform {
 
    function definition() {
    	
        global $CFG, $DB, $currentSess;
		
        $currentRecord =  $DB->get_record('cmanager_records', array('id'=>$currentSess));
        $mform =& $this->_form; // Don't forget the underscore! 
        $mform->addElement('header', 'mainheader', get_string('modrequestfacility','block_cmanager'));


		// Page description text
		$mform->addElement('html', '<p></p>&nbsp;&nbsp;&nbsp;' . get_string('modexists','block_cmanager'). '<p></p>&nbsp;');



		 $mform->addElement('html', '<center>
				<div id="twobordertitlewide">
					<div style="text-align: left; float: left; width:160px">&nbsp;<b>' . get_string('modcode','block_cmanager'). '</b></div> 
					<div style="text-align: left; float: left; width:160px">&nbsp;<b>' . get_string('modname','block_cmanager'). '</b></div>
					<div style="text-align: left; float: left; width:160px">&nbsp;<b>' . get_string('catlocation','block_cmanager'). '</b></div>

					<div style="text-align: left; float: left; width:160px">&nbsp;<b>' . get_string('lecturingstaff','block_cmanager'). '</b></div> 
					<div style="text-align: left; float: left; width:160px">&nbsp;<b>' . get_string('actions','block_cmanager'). '</b></div> 
	
				</div>
				');

   


	// Get out record
	$currentRecord =  $DB->get_record('cmanager_records', array('id'=>$currentSess));
	

	$modCode = $currentRecord->modcode;
	$modTitle = $currentRecord->modname;
	$modMode = $currentRecord->modmode;
	   
	$spaceCheck =  substr($modCode, 0, 4) . ' ' . substr($modCode, 4, strlen($modCode));
	
	$selectQuery = "shortname LIKE '%$modCode%' 
					
				    OR (shortname LIKE '%$spaceCheck%' 
					AND shortname LIKE '%$modMode%')
					OR shortname LIKE '%$spaceCheck%'";
	
	$recordsExist = $DB->record_exists_select('course', $selectQuery);
	
	
	
	$allRecords = $DB->get_recordset_select('course', $select=$selectQuery);



	
foreach($allRecords as $record){

$lecturerHTML = '';



	// Get the full category name
	$categoryName = $DB->get_record('course_categories', array('id'=>$record->category));
	

	// Get a list of all the lecturers
	if (! $course = $DB->get_record("course", array("id"=>$record->id))) {
		    error("That's an invalid course id");
	}
	    

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
		            $namesarray[] = format_string(role_get_name($role, $context)).': <a href="'.$CFG->wwwroot.'/user/view.php?id='.
		                            $teacher->id.'&amp;course='.SITEID.'">'.$fullname.'</a>';
		        }
		    }          
		}
		if (!empty($namesarray)) {
		    $lecturerHTML =  implode('<br>', $namesarray);
		   
		} else {
			$lecturerHTML = '&nbsp;';
		}
	    }



	// Check if the category name is blank
	if(!empty($categoryName->name)){
		$catLocation = $categoryName->name;
	} else {
		$catLocation = '&nbsp';
	}


 	$mform->addElement('html', '

	<div id="singleborderwide">
	<div style="text-align: left; float: left; width:160px">' . $record->shortname . '</div> 
	<div style="text-align: left; float: left; width:160px">' . $record->fullname .'</div>
	<div style="text-align: left; float: left; width:160px"> ' . $catLocation . '</div>

	<div style="text-align: left; float: left; width:160px">' . $lecturerHTML. ' </div> 
	<div style="text-align: left; float: left; width:160px"><span style="font-size: 10px;"><a href="requests/request_control.php?id=' . $record->id . '">'.get_string('request_requestControl','block_cmanager').'</a>
								<p></p>
								'.get_string('emailSubj_requester','block_cmanager').'</span></div> 
	</div>
       ');
        }



 	$mform->addElement('html', '</center>');
	// Page description text
	$mform->addElement('html', '<p></p><center>' . get_string('noneofthese','block_cmanager'). ', <a href="course_new.php?status=None">'.get_string('clickhere','block_cmanager').'</a><p></p></center>');
 	

	$mform->closeHeaderBefore('buttonar');
	}
}







$mform = new courserequest_form();//name of the form you defined in file above.



  
  if ($mform->is_cancelled()){

  } else if ($fromform=$mform->get_data()){
  	
  } else {
 	    print_header_simple('', '', $mform->focus(), "", false);
	    $mform->set_data($mform);
	    $mform->display();
	    echo $OUTPUT->footer();
	  
 
}



?>
