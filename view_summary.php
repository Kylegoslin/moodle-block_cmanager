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
$PAGE->set_context(context_system::instance());
$PAGE->set_heading(get_string('viewsummary', 'block_cmanager'));
$PAGE->set_title(get_string('viewsummary', 'block_cmanager'));
echo $OUTPUT->header();


if (isset($_GET['id'])) {
    $mid = required_param('id', PARAM_INT);
    $_SESSION['mid'] = $mid;
} else {
    $mid = $_SESSION['mid'];
}

/**
 * Course request form
 *
 * @package    block_cmanager
 * @copyright  2018 Kyle Goslin, Daniel McSweeney
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_cmanager_view_summary_form extends moodleform {

    function definition() {

        global $CFG;
        global $currentSess;
        global $mid;
        global $USER, $DB;



        $rec =  $DB->get_record('block_cmanager_records', array('id'=>$mid));
        $mform =& $this->_form; // Don't forget the underscore!

        // Page description text
        $mform->addElement('html', '<p><a href="module_manager.php" class="btn btn-default"><img src="icons/back.png" alt=""> '.get_string('back','block_cmanager').'</a></p>');

        $rec = $DB->get_recordset_select('block_cmanager_records', 'id = ' . $mid);
        $displayModHTML = block_cmanager_display_admin_list($rec, false, false, false, '');

        $mform->addElement('html', ''. $displayModHTML . '');
        $mform->addElement('html', '<p></p>&nbsp;');
        $whereQuery = "instanceid = '$mid' ORDER BY id DESC";
        $modRecords = $DB->get_recordset_select('block_cmanager_comments', $whereQuery);
        $htmlOutput = '';

        foreach($modRecords as $record){
            // Get the username of the person
            $username = $DB->get_field('user', 'username', array('id'=>$record->createdbyid));

            $htmlOutput .='	<tr>';
            $htmlOutput .=' <td>' . $record->dt . '</td>';
            $htmlOutput .=' <td>' . $record->message . '</td>';
            $htmlOutput .=' <td>' . $username .'</td>';
            $htmlOutput .=' </tr>';
        }

        $mform->addElement('html', '<h2>' . get_string('comments') . '</h2>');
        $mform->addElement('html', '
            <table class="table-striped w-75">
                <tr>
                    <th>'.get_string('comments_date','block_cmanager').'</td>
                    <th>'.get_string('comments_message','block_cmanager').'</td>
                    <th>'.get_string('comments_from','block_cmanager').'</td>
                </tr>
                ' . $htmlOutput . '
            </table>
        ');
	}
}


$mform = new block_cmanager_view_summary_form();


if ($mform->is_cancelled()) {
}

else if ($fromform=$mform->get_data()) {

} else {
		$mform->focus();
		$mform->set_data($mform);
		$mform->display();
 		echo $OUTPUT->footer();
}
