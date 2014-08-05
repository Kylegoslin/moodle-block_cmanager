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
require_once("../../../config.php");
global $CFG, $DB;
$formPath = "$CFG->libdir/formslib.php";
require_once($formPath);
require_login();
require_once('../validate_admin.php');

$PAGE->set_url('/blocks/cmanager/history/delete.php');
$PAGE->set_context(context_system::instance());

/** Navigation Bar **/
$PAGE->navbar->ignore_active();
$PAGE->navbar->add(get_string('cmanagerDisplay', 'block_cmanager'), new moodle_url('/blocks/cmanager/cmanager_admin.php'));
$PAGE->navbar->add(get_string('configurecoursemanagersettings', 'block_cmanager'), new moodle_url('/blocks/cmanager/cmanager_confighome.php'));
$PAGE->navbar->add(get_string('configureadminsettings', 'block_cmanager'), new moodle_url('/blocks/cmanager/cmanager_adminsettings.php'));
$PAGE->navbar->add(get_string('historynav', 'block_cmanager'));
$PAGE->set_heading(get_string('pluginname', 'block_cmanager'));
$PAGE->set_title(get_string('pluginname', 'block_cmanager'));
echo $OUTPUT->header();


/**
 * DELETE
 *
 * Delete a record
 * @package    block_cmanager
 * @copyright  2014 Kyle Goslin, Daniel McSweeney
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_cmanager_delete_form extends moodleform {

    function definition() {

        $mform =& $this->_form; 

        if (isset($_GET['delete'])) {
            //$type = $_GET['delete'];
            $type = required_param('delete', PARAM_TEXT);

            // Back Button
            $mform->addElement('html', '<p></p>&nbsp;&nbsp;&nbsp;<a href="../cmanager_adminsettings.php">&lt ' . get_string('back','block_cmanager') . '</a><p></p>');

            if ($type == 'all') {
                $mform->addElement('html', '<center><div style="font-size: 14px">'.get_string('sureDeleteAll', 'block_cmanager').'</div></center>');
                $mform->addElement('html', '<center><p></p>&nbsp;<p></p>&nbsp; <input type="submit" value="'.get_string('yesDeleteRecords', 'block_cmanager').'" name="deleteall"></center>');
            }	
            else if ($type == 'archonly') {
                $mform->addElement('html', '<center><div style="font-size: 14px">'.get_string('sureOnlyArch', 'block_cmanager').'</div></center>');
                $mform->addElement('html', '<center><p></p>&nbsp;<p></p>&nbsp;<input type="submit" value="'.get_string('yesDeleteRecords', 'block_cmanager').'" name="archonly"></center>');
            } 	
        }

        if (isset($_POST['deleteall']) || isset($_POST['archonly'])) {
            $mform->addElement('html', '<center><div style="font-size: 14px">'.get_string('recordsHaveBeenDeleted', 'block_cmanager').'<br>&nbsp<p></p>&nbsp<p></p><a href="../cmanager_adminsettings.php">'.get_string('clickHereToReturn', 'block_cmanager').'</a>&nbsp<p></p>&nbsp<p></p></div></center>');
        }


    }
	
}
 		$mform = new block_cmanager_delete_form();//name of the form you defined in file above.
 		
		if (isset($_POST['deleteall'])) {

			$DB->delete_records('block_cmanager_records', array('status'=>'COMPLETE'));
			$DB->delete_records('block_cmanager_records', array('status'=>'REQUEST DENIED'));
			$DB->delete_records('block_cmanager_records', array('status'=>'PENDING'));
			$DB->delete_records('block_cmanager_records', array('status'=>NULL));
			
      	}
		else if (isset($_POST['archonly'])) {
			
      		$DB->delete_records('block_cmanager_records', array('status'=>'COMPLETE'));
			$DB->delete_records('block_cmanager_records', array('status'=>'REQUEST DENIED'));
			$DB->delete_records('block_cmanager_records', array('status'=>NULL));
      	}

 
		
		$mform->focus();
		$mform->set_data($mform);
		$mform->display();
		
		echo $OUTPUT->footer();
 


?>