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
require_once('lib/displayLists.php');

/** Navigation Bar **/
$PAGE->navbar->ignore_active();
$PAGE->navbar->add(get_string('cmanagerDisplay', 'block_cmanager'), new moodle_url('/blocks/cmanager/module_manager.php'));
$PAGE->navbar->add(get_string('viewsummary', 'block_cmanager'));

$PAGE->set_url('/blocks/cmanager/view_summary.php');
$PAGE->set_context(get_system_context());
$PAGE->set_heading(get_string('pluginname', 'block_cmanager'));
$PAGE->set_title(get_string('pluginname', 'block_cmanager'));
echo $OUTPUT->header();


if(isset($_GET['id'])){
	$mid = required_param('id', PARAM_INT);
	$_SESSION['mid'] = $mid;
} else {

	$mid = $_SESSION['mid'];
}

?>

<link rel="stylesheet" type="text/css" href="css/main.css" />

<?php


class courserequest_form extends moodleform {
 
    function definition() {
        global $CFG;
        global $currentSess;
	    global $mid;
	    global $USER, $DB;


    
    $rec =  $DB->get_record('block_cmanager_records', array('id'=>$mid));

    $mform =& $this->_form; // Don't forget the underscore! 
 
	$mform->addElement('header', 'mainheader', '<span style="font-size:18px">'.get_string('viewsummary','block_cmanager'). '</span>');



	// Page description text
	$mform->addElement('html', '<p></p>&nbsp;&nbsp;&nbsp;
				    <a href="module_manager.php">< '.get_string('back','block_cmanager').'</a>
				    <p></p>');
				    




	$rec = $DB->get_recordset_select('block_cmanager_records', 'id = ' . $mid);
   	$displayModHTML = displayAdminList($rec, false, false, false, '');
	



	$mform->addElement('html', '<center>'. $displayModHTML . '</center>');

	$mform->addElement('html', '<p></p>&nbsp;');
	
	$whereQuery = "instanceid = '$mid' ORDER BY id DESC";
 	$modRecords = $DB->get_recordset_select('block_cmanager_comments', $whereQuery);
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


	 $mform->addElement('html', '<p></p><center>
	 <div align="left" style="border: 1px #000000 solid; width:700px; background:  #E0E0E0">
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
	
		
		$mform->focus();
		$mform->set_data($mform);
		$mform->display();
		
 		echo $OUTPUT->footer();
}




?>
