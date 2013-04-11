<?php
/* -------------------------------------------------------
 * 
 * 
 *  Course Request Manager
 *  2012 - 2013
 *  by Kyle Goslin, Daniel McSweeney
 * 
 * 
 * -------------------------------------------------------
 * */

global $CFG, $DB;

require_once("../../config.php");
$formPath = "$CFG->libdir/formslib.php";
require_once($formPath);
require_once('generate_summary.php');
require_once('lib/displayLists.php');
require_login();

/** Navigation Bar **/
$PAGE->navbar->ignore_active();
$PAGE->navbar->add(get_string('cmanagerDisplay', 'block_cmanager'), new moodle_url('/blocks/cmanager/module_manager.php'));
$PAGE->set_url('/blocks/cmanager/module_manager.php');
$PAGE->set_context(get_system_context());
$PAGE->set_heading(get_string('pluginname', 'block_cmanager'));

echo $OUTPUT->header();
?>


<link rel="stylesheet" type="text/css" href="css/main.css" />
 
<link href="js/jquery/jquery-ui18.css" rel="stylesheet" type="text/css"/>
<script src="js/jquery/jquery-1.7.2.min.js"></script>
<script src="js/jquery/jquery-ui.1.8.min.js"></script>
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

class courserequest_form extends moodleform {
 
    function definition() {

	global $CFG, $DB, $USER;
 
    $mform =& $this->_form; // Don't forget the underscore! 
	$mform->addElement('header', 'mainheader','<span style="font-size:18px"> '.get_string('myarchivedrequests','block_cmanager').'</span>');
	$mform->addElement('html', '<p></p>&nbsp;&nbsp;&nbsp;'.get_string('cmanagerWelcome','block_cmanager').' &nbsp;
			<p></p><br>
			&nbsp;&nbsp;<INPUT TYPE="BUTTON" VALUE="'.get_string('cmanagerRequestBtn','block_cmanager').'" ONCLICK="window.location.href=\'course_request.php?new=1\'"><br>
			<p></p><p></p>&nbsp;');

	$uid = $USER->id;
	


	

    global $USER;
	$uid = $USER->id;   
	$selectQuery = "createdbyid = $uid AND status = 'COMPLETE' OR createdbyid = $uid AND status = 'REQUEST DENIED' ORDER BY id DESC";
	//$DB->sql_order_by_text('id', $numchars=32);
	$pendingList = $DB->get_recordset_select('block_cmanager_records', $select=$selectQuery);



		$page1_fieldname1 = $DB->get_field_select('block_cmanager_config', 'value', "varname = 'page1_fieldname1'");
		$page1_fieldname2 = $DB->get_field_select('block_cmanager_config', 'value', "varname = 'page1_fieldname2'");
		$page1_fieldname4 = $DB->get_field_select('block_cmanager_config', 'value', "varname = 'page1_fieldname4'");

 


  		$outputHTML = '';
    	$modsHTML = displayAdminList($pendingList, true, false, false, 'user_history');
 	

		$outputHTML .= '<center><div id="existingrequest" style="border-bottom:1px solid black; height:300px; background:transparent"></div></center> ';
		
		$outputHTML = '<center>' . $modsHTML . '</center>';
		$mform->addElement('html', $outputHTML);
 	
	
	


    } // Close the function
    
    
  
    
} // Close the class



  



		$mform = new courserequest_form();

		if ($mform->is_cancelled()){
			echo "<script>window.location='module_manager.php';</script>";
			die;

		} else if ($fromform=$mform->get_data()){

		} else {

		    
			
		    $mform->focus();
		    $mform->display();
		  
		
		}


	echo $OUTPUT->footer();


?>
 