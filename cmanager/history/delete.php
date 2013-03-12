<?php
/* -----------------------------------------------------------
 * 
 *  COURSE REQUEST MANAGER
 *  
 *  2012-2013
 * 
 *  Kyle Goslin, Daniel McSweeney
 * 
 * -----------------------------------------------------------
 * */
require_once("../../../config.php");
global $CFG, $DB;
$formPath = "$CFG->libdir/formslib.php";
require_once($formPath);
require_login();
require_once('../validate_admin.php');

$PAGE->set_url('/blocks/cmanager/history/delete.php');
$PAGE->set_context(get_system_context());

/** Navigation Bar **/
$PAGE->navbar->ignore_active();
$PAGE->navbar->add(get_string('cmanagerDisplay', 'block_cmanager'), new moodle_url('/blocks/cmanager/cmanager_admin.php'));
$PAGE->navbar->add(get_string('configurecoursemanagersettings', 'block_cmanager'), new moodle_url('/blocks/cmanager/cmanager_confighome.php'));
$PAGE->navbar->add(get_string('configureadminsettings', 'block_cmanager'), new moodle_url('/blocks/cmanager/cmanager_adminsettings.php'));
$PAGE->navbar->add(get_string('historynav', 'block_cmanager'));
$PAGE->set_heading(get_string('pluginname', 'block_cmanager'));
$PAGE->set_title(get_string('pluginname', 'block_cmanager'));
echo $OUTPUT->header();

class courserequest_form extends moodleform {
 
    function definition() {
		
			$mform =& $this->_form; 

			if(isset($_GET['delete'])){
				
				//$type = $_GET['delete'];
				$type = required_param('delete', PARAM_TEXT);
				
				// Back Button
				$mform->addElement('html', '<p></p>&nbsp;&nbsp;&nbsp;<a href="../cmanager_adminsettings.php">&lt ' . get_string('back','block_cmanager') . '</a><p></p>');
	
				
				if($type == 'all'){
					  $mform->addElement('html', '<center><div style="font-size: 14px">'.get_string('sureDeleteAll', 'block_cmanager').'</div></center>');
					  $mform->addElement('html', '<center><p></p>&nbsp;<p></p>&nbsp; <input type="submit" value="'.get_string('yesDeleteRecords', 'block_cmanager').'" name="deleteall"></center>');
				}	
				else if($type == 'archonly'){
					$mform->addElement('html', '<center><div style="font-size: 14px">'.get_string('sureOnlyArch', 'block_cmanager').'</div></center>');
					$mform->addElement('html', '<center><p></p>&nbsp;<p></p>&nbsp;<input type="submit" value="'.get_string('yesDeleteRecords', 'block_cmanager').'" name="archonly"></center>');
				} 	
			}
			
			if(isset($_POST['deleteall']) || isset($_POST['archonly'])){
				$mform->addElement('html', '<center><div style="font-size: 14px">'.get_string('recordsHaveBeenDeleted', 'block_cmanager').'<br>&nbsp<p></p>&nbsp<p></p><a href="../cmanager_adminsettings.php">'.get_string('clickHereToReturn', 'block_cmanager').'</a>&nbsp<p></p>&nbsp<p></p></div></center>');
			}
		   
		
	}
	
}
 		$mform = new courserequest_form();//name of the form you defined in file above.
 		
		if(isset($_POST['deleteall'])){

			$DB->delete_records('block_cmanager_records', array('status'=>'COMPLETE'));
			$DB->delete_records('block_cmanager_records', array('status'=>'REQUEST DENIED'));
			$DB->delete_records('block_cmanager_records', array('status'=>'PENDING'));
			$DB->delete_records('block_cmanager_records', array('status'=>NULL));
			
      	}
		else if(isset($_POST['archonly'])){
			
      		$DB->delete_records('block_cmanager_records', array('status'=>'COMPLETE'));
			$DB->delete_records('block_cmanager_records', array('status'=>'REQUEST DENIED'));
			$DB->delete_records('block_cmanager_records', array('status'=>NULL));
      	}

 
		
		$mform->focus();
		$mform->set_data($mform);
		$mform->display();
		
		echo $OUTPUT->footer();
 


?>