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
$formpath = "$CFG->libdir/formslib.php";
require_once($formpath);
require_login();
require_once('lib/displayLists.php');
require_once('lib/boot.php');

/** Navigation Bar **/
$PAGE->navbar->ignore_active();
$PAGE->navbar->add(get_string('cmanagerDisplay', 'block_cmanager'), new moodle_url('/blocks/cmanager/module_manager.php'));
$PAGE->set_url('/blocks/cmanager/module_manager.php');
$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_heading(get_string('pluginname', 'block_cmanager'));
$PAGE->set_title(get_string('pluginname', 'block_cmanager'));

if (has_capability('block/cmanager:viewrecord',$context)) {
} else {
  print_error(get_string('cannotviewrecords', 'block_cmanager'));
}



echo $OUTPUT->header();


$context = context_system::instance();
if (has_capability('block/cmanager:viewrecord',$context)) {
} else {
  print_error(get_string('cannotviewrecords', 'block_cmanager'));
}



?>

<script type="text/javascript">
var id = 0;
// pop up a modal to ask the user are
// they sure that they want to cancel the
// request
function cancelConfirm(cid,langString) {
	$("#conf1").modal();
    id = cid;

}
</script>

<?php
/**
 * Module manager
 *
 * Main module manager form
 * Course request manager block for moodle main block interface
 * @package    block_cmanager
 * @copyright  2018 Kyle Goslin, Daniel McSweeney
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_cmanager_module_manager_form extends moodleform {

    function definition() {
        global $CFG, $DB, $USER;

	    $mform =& $this->_form; // Don't forget the underscore!
        $mform->addElement('html', '<p>' . get_string('cmanagerWelcome','block_cmanager') . '</p>');
        $mform->addElement('html', '<p><a class="btn btn-default" href="course_request.php?mode=1">'.get_string('cmanagerRequestBtn','block_cmanager').'</a></p>');
	    $uid = $USER->id;
        // Get the list of pending requests
	    $pendinglist = $DB->get_records('block_cmanager_records',array('createdbyid' => "$uid" , 'status' => 'PENDING'), 'id ASC');
	    $outputhtml = '<div id="pendingrequestcontainer">';

        $outputhtml .= block_cmanager_display_admin_list($pendinglist, true, false, false, 'user_manager');


        $outputhtml .= generateGenericConfirm('conf1', get_string('alert', 'block_cmanager') ,
                                     get_string('cmanagerConfirmCancel', 'block_cmanager'),
                                     get_string('yes', 'block_cmanager'));

        $outputhtml .= '
        <script>
        // cancel request click handler
        // just does a hard redirect to the delete page and back.
        $("#okconf1").click(function(){

              console.log("deleting request");
              window.location = "deleterequest.php?id=" + id;


            });

        </script>
        ';
        // Existing Requests

        $mform->addElement('html', '<div style="">	'.$outputhtml.'</div>');
	    } // Close the function

    } // Close the class

$mform = new block_cmanager_module_manager_form();

if ($mform->is_cancelled()) {
	echo "<script>window.location='module_manager.php';</script>";
	die;
} else if ($fromform=$mform->get_data()) {

} else {

}

$mform->display();
$mform->focus();
$mform->focus();
echo $OUTPUT->footer();
?>
<script src="js/bootstrap.min.js"/>