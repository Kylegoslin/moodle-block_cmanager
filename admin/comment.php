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
require_once("../../../config.php");
global $CFG;
$formPath = "$CFG->libdir/formslib.php";
require_once($formPath);
require_login();

$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_url('/blocks/cmanager/admin/comment.php');

/** Navigation Bar **/
$PAGE->navbar->ignore_active();
$PAGE->navbar->add(get_string('cmanagerDisplay', 'block_cmanager'), new moodle_url('/blocks/cmanager/cmanager_admin.php'));
$PAGE->navbar->add(get_string('currentrequests', 'block_cmanager'), new moodle_url('/cmanager_admin.php'));
$PAGE->navbar->add(get_string('addviewcomments', 'block_cmanager'));
$PAGE->set_heading(get_string('addviewcomments', 'block_cmanager'));
$PAGE->set_title(get_string('addviewcomments', 'block_cmanager'));
echo $OUTPUT->header();

$context = context_system::instance();
if (has_capability('block/cmanager:addcomment',$context)) {
} else {
  print_error(get_string('cannotcomment', 'block_cmanager'));
}



if(isset($_GET['id'])){
	$mid = required_param('id', PARAM_INT);
	$_SESSION['mid'] = $mid;
} else {
	$mid = $_SESSION['mid'];
}

$type = optional_param('type', '', PARAM_TEXT);
if(!empty($type)){
	$_SESSION['type'] = $type;

} else {
	$type = '';
	$type = $_SESSION['type'];
}

$backLink = '';
if($type == 'adminarch'){
	$backLink = '../cmanager_admin_arch.php';
}
else if($type == 'adminq'){
	$backLink = '../cmanager_admin.php';
}

$PAGE->set_url('/blocks/cmanager/admin/comment.php', array('id'=>$mid));

class block_cmanager_comment_form extends moodleform {

    function definition() {
        global $CFG;
        global $currentSess;
        global $mid;
        global $USER;
        global $DB;
        global $backLink;

        $currentRecord =  $DB->get_record('block_cmanager_records', array('id'=>$currentSess));
        $mform =& $this->_form; // Don't forget the underscore!

        // Page description text
        $mform->addElement('html', '<p><a href="'.$backLink.'" class="btn btn-default"><img src="../icons/back.png" alt=""> '.get_string('back','block_cmanager').'</a></p>');
        $mform->addElement('html', '<p>' . get_string('comments_Forward','block_cmanager') . '.</p>');

        // Add a comment box.
        $mform->addElement('html', '
                <textarea id="newcomment" name="newcomment" rows="5" cols="60"></textarea><br>
                <input class="btn btn-default mt-3" type="submit" value="' . get_string('comments_PostComment','block_cmanager') . '">
        ');

        // Previous comments.
        $whereQuery = "instanceid = '$mid'  ORDER BY id DESC";
        $modRecords = $DB->get_recordset_select('block_cmanager_comments', $whereQuery);
        $htmlOutput = '<h2 class="h4 mt-3 p-2" style="border: 1px #000000 solid; width:100%; background: #E0E0E0">'.get_string('comments_comment','block_cmanager').'</h2>';
        foreach($modRecords as $record){
            $createdbyid = $record->createdbyid;
            $username = $DB->get_field_select('user', 'username', "id = '$createdbyid'");
            $htmlOutput .= '<p><strong>' . get_string('comments_date','block_cmanager').':</strong> ' . $record->dt . '</p>';
            $htmlOutput .= '<p><strong>'.get_string('comments_author','block_cmanager').':</strong> ' . $username . '</p>';
            $htmlOutput .= '<p><strong>'.get_string('comments_comment','block_cmanager').':</strong> ' . $record->message .'</p>';
            $htmlOutput .= '<hr>';
        }
        $mform->addElement('html', $htmlOutput);
    }
}
$mform = new block_cmanager_comment_form();//name of the form you defined in file above.



if ($mform->is_cancelled()){
    echo "<script>window.location='" . $backLink."';</script>";
    die;
} else if ($fromform=$mform->get_data()){
} else {
    $mform->focus();
    $mform->set_data($mform);
    $mform->display();
    echo $OUTPUT->footer();
}
if($_POST){
    global $USER, $CFG, $DB, $mid;

    $userid = $USER->id;

    $newrec = new stdClass();
    $newrec->instanceid = $mid;
    $newrec->createdbyid = $userid;
    $newrec->message = $_POST['newcomment'];
    $newrec->dt = date("Y-m-d H:i:s");
    $DB->insert_record('block_cmanager_comments', $newrec, false);

    // Send an email to everyone concerned.
    require_once('../cmanager_email.php');
    $message = required_param('newcomment', PARAM_TEXT);

    // Get all user id's from the record
    $currentRecord =  $DB->get_record('block_cmanager_records', array('id'=>$mid));



    $user_ids = ''; // Used to store all the user IDs for the people we need to email.
    $user_ids = $currentRecord->createdbyid; // Add the current user

    // Get info about the current object.

    // Send email to the user
    $replaceValues = array();
    $replaceValues['[course_code'] = $currentRecord->modcode;
    $replaceValues['[course_name]'] = $currentRecord->modname;
    //$replaceValues['[p_code]'] = $currentRecord->progcode;
   // $replaceValues['[p_name]'] = $currentRecord->progname;
    $replaceValues['[e_key]'] = '';
    $replaceValues['[full_link]'] = $CFG->wwwroot.'/blocks/cmanager/comment.php?id=' . $mid;
    $replaceValues['[loc]'] = '';
    $replaceValues['[req_link]'] = $CFG->wwwroot.'/blocks/cmanager/view_summary.php?id=' . $mid;


    block_cmanager_email_comment_to_user($message, $user_ids, $mid, $replaceValues);
    block_cmanager_email_comment_to_admin($message, $mid, $replaceValues);

    echo "<script> window.location = 'comment.php?type=".$type."&id=$mid';</script>";
}
