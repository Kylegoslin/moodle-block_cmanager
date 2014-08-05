<?php 
// --------------------------------------------------------- 
// block_cmanager is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// block_cmanager is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.
//
// COURSE REQUEST MANAGER BLOCK FOR MOODLE
// by Kyle Goslin & Daniel McSweeney
// Copyright 2012-2014 - Institute of Technology Blanchardstown.
// --------------------------------------------------------- 
/**
 * COURSE REQUEST MANAGER
  *
 * @package    block_cmanager
 * @copyright  2014 Kyle Goslin, Daniel McSweeney
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once("../../config.php");
require_once("$CFG->libdir/formslib.php");
require_once("lib.php");
require_login();
global $CFG, $DB, $USER;


/** Navigation Bar **/
$PAGE->navbar->ignore_active();
$PAGE->navbar->add(get_string('cmanagerDisplay', 'block_cmanager'), new moodle_url('/blocks/cmanager/module_manager.php'));
$PAGE->navbar->add(get_string('courseexists', 'block_cmanager'));
$PAGE->set_url('/blocks/cmanager/course_exists.php');
$PAGE->set_context(context_system::instance());
$PAGE->set_heading(get_string('pluginname', 'block_cmanager'));
$PAGE->set_title(get_string('pluginname', 'block_cmanager'));


// Main variable for storing the current session id.
$currentSess = '00';
$currentSess = $_SESSION['cmanager_session'];
?>
<link rel="stylesheet" type="text/css" href="css/main.css" />

<?php

class block_cmanager_course_exists_form extends moodleform {
 
    function definition() {
    	
        global $CFG, $DB, $currentSess;
		
        $currentRecord =  $DB->get_record('block_cmanager_records', array('id'=>$currentSess));
        $mform =& $this->_form; // Don't forget the underscore! 
        $mform->addElement('header', 'mainheader', '<span style="font-size:18px">'. get_string('modrequestfacility','block_cmanager') . '</span>');


		// Page description text
		$mform->addElement('html', '<p></p>&nbsp;&nbsp;&nbsp;' . get_string('modexists','block_cmanager'). '<p></p>&nbsp;');



		 $mform->addElement('html', '<center>
				<div id="twobordertitlewide" style="background:transparent; width:820px">
					<div style="text-align: left; float: left; width:160px">&nbsp;<b>' . get_string('modcode','block_cmanager'). '</b></div> 
					<div style="text-align: left; float: left; width:160px">&nbsp;<b>' . get_string('modname','block_cmanager'). '</b></div>
					<div style="text-align: left; float: left; width:160px">&nbsp;<b>' . get_string('catlocation','block_cmanager'). '</b></div>

					<div style="text-align: left; float: left; width:160px">&nbsp;<b>' . get_string('lecturingstaff','block_cmanager'). '</b></div> 
					<div style="text-align: left; float: left; width:160px">&nbsp;<b>' . get_string('actions','block_cmanager'). '</b></div> 
	
				</div>
				');

   


	// Get out record
	$currentRecord =  $DB->get_record('block_cmanager_records', array('id'=>$currentSess));
	

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
	
     // Get lecturer info
 	$lecturerHTML = block_cmanager_get_lecturer_info($record->id);

	// Check if the category name is blank
	if(!empty($categoryName->name)){
		$catLocation = $categoryName->name;
	} else {
		$catLocation = '&nbsp';
	}


 	$mform->addElement('html', '

	<div id="singleborderwide" style="background:transparent">
	<div style="text-align: left; float: left; width:160px">' . $record->shortname . '</div> 
	<div style="text-align: left; float: left; width:160px">' . $record->fullname .'</div>
	<div style="text-align: left; float: left; width:160px"> ' . $catLocation . '</div>

	<div style="text-align: left; float: left; width:160px">' . $lecturerHTML. ' </div> 
	<div style="text-align: left; float: left; width:160px"><span style="font-size: 10px;"><a href="requests/request_control.php?id=' . $record->id . '">'.get_string('request_requestControl','block_cmanager').'</a>
								<p></p>
								<a href="mailto:' .  block_cmanager_get_list_of_lecturer_emails($record->id). '">'.get_string('emailSubj_requester','block_cmanager').'</a></span></div> 
	</div>
       ');
        }


	echo '<script>function noneOfThese(){
		
		
		window.location="course_new.php?status=None";
	}</script>';
 	$mform->addElement('html', '</center>');
	// Page description text
	$mform->addElement('html', '<p></p><center>' . get_string('noneofthese','block_cmanager'). ', <input type="button" value="'.get_string('clickhere','block_cmanager').'" onclick="noneOfThese()"><p></p></center>');
 	

	$mform->closeHeaderBefore('buttonar');
	}
}


$mform = new block_cmanager_course_exists_form();//name of the form you defined in file above.

  
  if ($mform->is_cancelled()){

  } else if ($fromform=$mform->get_data()){
  	
  } else {
 	  	echo $OUTPUT->header();
	    $mform->focus();
	    $mform->set_data($mform);
	    $mform->display();
	    echo $OUTPUT->footer();
	  
 
}



?>
