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
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

global $CFG, $DB;

require_once("../../config.php");
$formPath = "$CFG->libdir/formslib.php";
require_once($formPath);
require_once('lib/displayLists.php');
require_login();

/** Navigation Bar **/
$PAGE->navbar->ignore_active();
$PAGE->navbar->add(get_string('cmanagerDisplay', 'block_cmanager'), new moodle_url('/blocks/cmanager/module_manager.php'));
$PAGE->navbar->add(get_string('myarchivedrequests', 'block_cmanager'));
$PAGE->set_url('/blocks/cmanager/module_manager.php');
$PAGE->set_context(context_system::instance());
$PAGE->set_heading(get_string('myarchivedrequests', 'block_cmanager'));
$PAGE->set_title(get_string('myarchivedrequests', 'block_cmanager'));

echo $OUTPUT->header();
$context = context_system::instance();

// check permissions
if (has_capability('block/cmanager:viewrecord',$context)) {
} else {
       print_error(get_string('cannotviewrecords', 'block_cmanager'));
}
?>


<link rel="stylesheet" type="text/css" href="css/main.css" />
<script src="js/jquery/jquery-3.3.1.min.js"></script>
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

<style>
	tr:nth-child(odd)		{ background-color:#eee; }
	tr:nth-child(even)		{ background-color:#fff; }
 </style>

<?php
/**
 * History manager
 * The management front end for the modules which have been processed
 * in the past.
 * @package    block_cmanager
 * @copyright  2018 Kyle Goslin, Daniel McSweeney
 * @copyright  2021 Michael Milette (TNG Consulting Inc.), Daniel Keaman
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_cmanager_module_manager_history_form extends moodleform {

	function definition() {

	global $CFG, $DB, $USER;

	$mform =& $this->_form; // Don't forget the underscore!
	$mform->addElement('html', '<p>' . get_string('cmanagerWelcome','block_cmanager') . '</p>');
	$mform->addElement('html', '<p><input class="btn btn-default" type="button" value="'.get_string('cmanagerRequestBtn','block_cmanager').'" onclick="window.location.href=\'course_request.php?mode=1\'"></p>');

	$uid = $USER->id;

	$selectQuery = "createdbyid = $uid AND status = 'COMPLETE' OR createdbyid = $uid AND status = 'REQUEST DENIED' ORDER BY id DESC";
	//$DB->sql_order_by_text('id', $numchars=32);
	$pendingList = $DB->get_recordset_select('block_cmanager_records', $select=$selectQuery);

	$page1_fieldname1 = $DB->get_field_select('block_cmanager_config', 'value', "varname = 'page1_fieldname1'");
	$page1_fieldname2 = $DB->get_field_select('block_cmanager_config', 'value', "varname = 'page1_fieldname2'");
	$page1_fieldname4 = $DB->get_field_select('block_cmanager_config', 'value', "varname = 'page1_fieldname4'");

 	$outputHTML = '';
	$modsHTML = block_cmanager_display_admin_list($pendingList, true, false, false, 'user_history');


	$outputHTML .= '<div id="existingrequest" style="border-bottom:1px solid black; height:300px; background:transparent"></div>';
	$outputHTML = $modsHTML;
	$mform->addElement('html', $outputHTML);


    } // Close the function




} // Close the class




$mform = new block_cmanager_module_manager_history_form();

if ($mform->is_cancelled()) {
	echo "<script>window.location='module_manager.php';</script>";
	die;

} else if ($fromform=$mform->get_data()) {

} else {

	$mform->focus();
	$mform->display();
}


echo $OUTPUT->footer();

