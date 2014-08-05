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
$formpath = "$CFG->libdir/formslib.php";
require_once($formpath);
require_login();
require_once('lib/displayLists.php');

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
<link rel="stylesheet" type="text/css" href="css/main.css" />
<link href="js/jquery/jquery-ui18.css" rel="stylesheet" type="text/css"/>
<script src="js/jquery/jquery-1.7.2.min.js"></script>
<script src="js/jquery/jquery-ui.1.8.min.js"></script>
<script type="text/javascript">
function cancelConfirm(id,langString) {
	//var answer = confirm("Are you sure you want to cancel this request?")
	var answer = confirm(langString)
	if (answer){

		window.location = "deleterequest.php?id=" + id;
	}
	else{

	}
}
</script>
<style>
	tr:nth-child(odd)		{ background-color:#eee; }
	tr:nth-child(even)		{ background-color:#fff; }
 </style>

<?php
/**
 * Module manager
 *
 * Main module manager form
 * Course request manager block for moodle main block interface
 * @package    block_cmanager
 * @copyright  2014 Kyle Goslin, Daniel McSweeney
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_cmanager_module_manager_form extends moodleform {

    function definition() {
        global $CFG, $DB, $USER;

	    $mform =& $this->_form; // Don't forget the underscore!
	    $mform->addElement('header', 'mainheader',' <span style="font-size:18px"> '.get_string('cmanagerExstingTab','block_cmanager').'</span>');
        $mform->addElement('html', '<p></p>&nbsp;&nbsp;&nbsp;'.get_string('cmanagerWelcome','block_cmanager').' &nbsp;
		                            <p></p><br>
		                            &nbsp;&nbsp;<INPUT TYPE="BUTTON" VALUE="'.get_string('cmanagerRequestBtn','block_cmanager').'" ONCLICK="window.location.href=\'course_request.php?mode=1\'"><br>
		                            <p></p><p></p>&nbsp;');
	    $uid = $USER->id;
        // Get the list of pending requests
	    $pendinglist = $DB->get_records('block_cmanager_records',array('createdbyid' => "$uid" , 'status' => 'PENDING'), 'id ASC');
	    $outputhtml = '<div id="pendingrequestcontainer">';

        // Existing Requests
	    $outputhtml = block_cmanager_display_admin_list($pendinglist, true, false, false, 'user_manager');
        $mform->addElement('html', '
	                                <p></p>
	                                &nbsp;
	                                <p></p>
	                                <div style="">	'.$outputhtml.'</div>');
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
