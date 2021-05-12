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
// Copyright 2012-2018 - Institute of Technology Blanchardstown.
// ---------------------------------------------------------
/**
 * COURSE REQUEST MANAGER
  *
 * @package    block_cmanager
 * @copyright  2018 Kyle Goslin, Daniel McSweeney
 * @copyright  2021 Michael Milette (TNG Consulting Inc.), Daniel Keaman
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
$PAGE->set_heading(get_string('courseexists', 'block_cmanager'));
$PAGE->set_title(get_string('courseexists', 'block_cmanager'));


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

		// Page description text
		$mform->addElement('html', '<p>' . get_string('modexists','block_cmanager') . '</p>');

		$mform->addElement('html', '<table class="table table-striped">');

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

        // Table heading.
        $mform->addElement('html', '
            <tr>
                <th>' . get_string('modcode','block_cmanager') . '</th>
                <th>' . get_string('modname','block_cmanager') . '</th>
                <th>' . get_string('catlocation','block_cmanager'). '</th>
                <th>' . get_string('lecturingstaff','block_cmanager')  . '</th>
                <th>' . get_string('actions','block_cmanager') . '</th>
            </tr>
        ');


        foreach($allRecords as $record){

            // Get the full category name
            $categoryName = $DB->get_record('course_categories', array('id'=>$record->category));

             // Get lecturer info
            $lecturersHTML = block_cmanager_get_lecturers($record->id);

            // Check if the category name is blank
            if(!empty($categoryName->name)){
                $catLocation = $categoryName->name;
            } else {
                $catLocation = '&nbsp';
            }

            $mform->addElement('html', '
                <tr>
                    <th>' . format_string($record->shortname) . '</th>
                    <td>' . format_string($record->fullname) .'</td>
                    <td>' . format_string($catLocation) . '</td>
                    <td>' . $lecturersHTML . '</td>
                    <td><a href="requests/request_control.php?id=' . $record->id . '">'.get_string('request_requestControl','block_cmanager') . '</a></td>
                </tr>
            ');
        }

        $mform->addElement('html', '</table>');

        // Button: None of these? Continue.
        $mform->addElement('html', '<p><a class="btn btn-default" href="course_new.php?status=None">' . get_string('noneofthese','block_cmanager') . '</a></p>');

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
