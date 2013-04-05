<?php
/* --------------------------------------------------------- 

     COURSE REQUEST BLOCK FOR MOODLE  

     2012 Kyle Goslin & Daniel McSweeney
     Institute of Technology Blanchardstown
     Dublin 15, Ireland
 --------------------------------------------------------- */

require_once("../../../config.php");
global $CFG; $DB;
$formPath = "$CFG->libdir/formslib.php";
require_once($formPath);
require_login();
require_once('../validate_admin.php');
/** Navigation Bar **/
$PAGE->navbar->ignore_active();
$PAGE->navbar->add(get_string('cmanagerDisplay', 'block_cmanager'), new moodle_url('/blocks/cmanager/cmanager_admin.php'));
$PAGE->navbar->add(get_string('bulkapprove', 'block_cmanager'));
$PAGE->set_url('/blocks/cmanager/admin/bulk_approve.php');
$PAGE->set_context(get_system_context());
$PAGE->set_title(get_string('pluginname', 'block_cmanager'));
?>


<SCRIPT LANGUAGE="JavaScript" SRC="http://code.jquery.com/jquery-1.6.min.js">
</SCRIPT>
<?php



if(isset($_GET['mul'])){
	$_SESSION['mul'] = required_param('mul', PARAM_TEXT);
}

class courserequest_form extends moodleform {
 
    function definition() {
        global $CFG;
        global $currentSess;
		global $USER;
		global $DB;
 	
        $currentRecord =  $DB->get_record('block_cmanager_records', array('id'=>$currentSess));
        $mform =& $this->_form; // Don't forget the underscore! 
		 
        $mform->addElement('header', 'mainheader', get_string('approvingcourses','block_cmanager'));

		// Page description text
		$mform->addElement('html', '<p></p>&nbsp;&nbsp;&nbsp;
					    	<a href="../cmanager_admin.php">< Back</a>');

		$mform->addElement('html', '<p></p><center>'.get_string('approvingcourses', 'block_cmanager').'</center>');
		
		
		global $USER, $CFG, $DB;
		
		// Send Email to all concerned about the request deny.
		require_once('../lib/course_lib.php');
		
		$denyIds = explode(',',$_SESSION['mul']);
		    
			foreach($denyIds as $cid){
			
				// If the id isn't blank
				if($cid != 'null'){
				
						//echo $cid . '-';
						$mid = createNewCourseByRecordId($cid, true);
							
							
				}
			
		
			}	
		
		$_SESSION['mul'] = '';
		echo "<script> window.location = '../cmanager_admin.php';</script>";
		

	}
}




   $mform = new courserequest_form();//name of the form you defined in file above.



   if ($mform->is_cancelled()){
        
	echo "<script>window.location='../cmanager_admin.php';</script>";
			die;

  } else if ($fromform=$mform->get_data()){
	


  } else {
        echo $OUTPUT->header();
        $mform->focus();
	    $mform->set_data($mform);
	    $mform->display();
	  	echo $OUTPUT->footer();
}





?>
