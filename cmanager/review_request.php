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
global $CFG;
$formPath = "$CFG->libdir/formslib.php";
require_once($formPath);
require_once('lib/displayLists.php');
require_login();


/** Navigation Bar **/
$PAGE->navbar->ignore_active();
$PAGE->navbar->add(get_string('cmanagerDisplay', 'block_cmanager'), new moodle_url('/blocks/cmanager/module_manager.php'));
$PAGE->navbar->add(get_string('modrequestfacility', 'block_cmanager'));
$PAGE->set_url('/blocks/cmanager/review_request.php');
$PAGE->set_context(context_system::instance());
$PAGE->set_heading(get_string('pluginname', 'block_cmanager'));
$PAGE->set_title(get_string('pluginname', 'block_cmanager'));
echo $OUTPUT->header();

if (isset($_GET['id'])) {
	$mid = required_param('id', PARAM_INT);
	$_SESSION['mid'] = $mid;
} else {
	$mid = $_SESSION['mid'];
}


$context = context_system::instance();
if (has_capability('block/cmanager:addrecord',$context)) {
} else {
  print_error(get_string('cannotrequestcourse', 'block_cmanager'));
}


?>

<link rel="stylesheet" type="text/css" href="css/main.css" />
<style>
tr:nth-child(odd)		{ background-color:#eee; }
tr:nth-child(even)		{ background-color:#fff; }

</style>
<?php

/**
 * Review request
 *
 * Allow the user to review their request
 * @package    block_cmanager
 * @copyright  2014 Kyle Goslin, Daniel McSweeney
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_cmanager_review_request_form extends moodleform {

    function definition() {
        global $CFG, $currentSess, $mid, $USER, $DB;

        $mform =& $this->_form; // Don't forget the underscore!
        $mform->addElement('header', 'mainheader', '<span style="font-size:18px">'. get_string('requestReview_Summary','block_cmanager'). '</span>');
        $mform->addElement('html', '<p></p><center>'.get_string('requestReview_intro1','block_cmanager').'<br>'.get_string('requestReview_intro2','block_cmanager').'</center><p></p>&nbsp;<p></p>&nbsp;');

        $rec = $DB->get_recordset_select('block_cmanager_records', 'id = ' . $mid);
	    $displayModHTML = block_cmanager_display_admin_list($rec, false, false, false, '');
        $mform->addElement('html', $displayModHTML);
        $buttonarray=array();
        $buttonarray[] = &$mform->createElement('submit', 'submitbutton', get_string('requestReview_SubmitRequest','block_cmanager'));
        $buttonarray[] = &$mform->createElement('submit', 'alter', get_string('requestReview_AlterRequest','block_cmanager'));
        $buttonarray[] = &$mform->createElement('cancel', 'cancel', get_string('requestReview_CancelRequest','block_cmanager'));
        $mform->addGroup($buttonarray, 'buttonar', '', array(' '), false);

	}
}

$mform = new block_cmanager_review_request_form();



if ($mform->is_cancelled()) {
    // Delete the record
	$DB->delete_records_select('block_cmanager_records', "id = $mid");
	echo "<script>window.location='module_manager.php'</script>";
    die;
} else if ($fromform=$mform->get_data()) {

    // If alter was pressed.
    if (isset($_POST['alter'])) {
	    echo "<script>window.location='course_request.php?mode=2&edit=$mid'</script>";
		die;
    }

    require_once('cmanager_email.php');

    global $mid;
    global $CFG;
    global $USER;


    $rec = $DB->get_record('block_cmanager_records', array('id'=>$mid));
    $replaceValues = array();
    $replaceValues['[course_code'] = $rec->modcode;
    $replaceValues['[course_name]'] = $rec->modname;
    //$replaceValues['[p_code]'] = $rec->progcode;
    //$replaceValues['[p_name]'] = $rec->progname;
    $replaceValues['[e_key]'] = 'No key set';
    $replaceValues['[full_link]'] = 'Course currently does not exist.';
    $replaceValues['[loc]'] = 'Location: ';
    $replaceValues['[req_link]'] = $CFG->wwwroot .'/blocks/cmanager/view_summary.php?id=' . $mid;

	// Send email to admin saying we are requesting a new mod
	block_cmanager_request_new_mod_email_admins($replaceValues);

	// Send email to user to track that we are requestig a new mod
	block_cmanager_request_new_mod_email_user($USER->id, $replaceValues);


	// Return to module manager
	if (isset($_SESSION['CRMisAdmin'])) {
		if ($_SESSION['CRMisAdmin'] == true) {
			echo "<script>window.location='cmanager_admin.php'</script>";
			die;
		}
	} else {
        echo "<script>window.location='module_manager.php'</script>";
		die;
	}


  } else {
        $mform->focus();
        $mform->set_data($mform);
        $mform->display();
        echo $OUTPUT->footer();

}

/**
* Get a user id
*/
function block_cmanager_get_username($id){

	global $DB;

	return $DB->get_field_select('user', 'username', "id = '$id'");

}


?>
