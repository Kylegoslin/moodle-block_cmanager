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
global $CFG, $DB;
$formPath = "$CFG->libdir/formslib.php";
require_once($formPath);

/** Navigation Bar **/
$PAGE->navbar->ignore_active();
$PAGE->navbar->add(get_string('cmanagerDisplay', 'block_cmanager'), new moodle_url('/blocks/cmanager/module_manager.php'));
$PAGE->navbar->add(get_string('addviewcomments', 'block_cmanager'));
$context = context_system::instance();
$PAGE->set_url('/blocks/cmanager/comment.php');
$PAGE->set_context($context);
$PAGE->set_heading(get_string('pluginname', 'block_cmanager'));
$PAGE->set_title(get_string('pluginname', 'block_cmanager'));
echo $OUTPUT->header();

if (has_capability('block/cmanager:addcomment',$context)) {
} else {
  print_error(get_string('cannotcomment', 'block_cmanager'));
}

$type = optional_param('type', '', PARAM_TEXT);
if(!empty($type)){
	$_SESSION['type'] = $type;
} else {
	$type = '';
	$type = $_SESSION['type'];
}



$backlink = '';
if($type == 'userarch'){
	$backlink = 'module_manager_history.php';
}
else if($type == 'userq'){
	$backlink = 'module_manager.php';
}
else if($type == 'adminq'){
	$backlink = 'cmanager_admin.php';
}


if(isset($_GET['id'])){
	$mid = required_param('id', PARAM_INT);
	$_SESSION['mid'] = $mid;
} else {
	$mid = $_SESSION['mid'];
}

echo '
<script>
function goBack(){
	window.location ="'.$backlink.'";
}
</script>
';

/**
 * cmanager comment form
 *
 * Comment form
 * @package    block_cmanager
 * @copyright  2014 Kyle Goslin, Daniel McSweeney
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_cmanager_comment_form extends moodleform {

    function definition() {

    global $CFG, $currentsess, $mid, $USER, $DB, $backlink;

    $mform =& $this->_form; // Don't forget the underscore!
 	$mform->addElement('header', 'mainheader', '<span style="font-size:18px">'.get_string('comments_Header','block_cmanager'). '</span>');

	// Page description text
	$mform->addElement('html', '<p></p>&nbsp;&nbsp;&nbsp;
			  <button type="button" value="" onclick="goBack();"><img src="icons/back.png"/> '.get_string('back','block_cmanager').'</button>

				    <p></p>
				    &nbsp;&nbsp;&nbsp;'.get_string('comments_Forward','block_cmanager').'<p></p>&nbsp;<center>');


	$mform->addElement('html', '<p></p>&nbsp;');

	$wherequery = "instanceid = '$mid' ORDER BY id DESC";
 	$modrecords = $DB->get_recordset_select('block_cmanager_comments', $wherequery);
	$htmloutput = '';

	$htmloutput .='	<table style="width:100%">';

	foreach($modrecords as $record){

		$createdbyid = $record->createdbyid;
		$username = $DB->get_field_select('user', 'username', "id = '$createdbyid'");



		$htmloutput .=' <tr ><td><b>Date:</b> ' . $record->dt . '</td></tr>';
		$htmloutput .=' <tr><td><b>Author:</b> ' . $username . '</td></tr>';

		$htmloutput .=' <tr><td><b>Comment:</b> ' . $record->message .'</td></tr>';
	  	$htmloutput .=' <tr style=" border-bottom:1pt solid black;"><td></td></tr>';
		$htmloutput .='<tr><td></td></tr> ';
	}
	$htmloutput .='</table>';

	?>
	<style>
	 #wrapper {
    width: 950px;
    border: 1px solid black;
    overflow: hidden; /* will contain if #first is longer than #second */
}
#left {
    width: 600px;
    float:left; /* add this */

}
#right {
    border: 0px solid green;
    overflow: hidden; /* if you dont want #second to wrap below #first */
}

	 </style>

<?php
	 $mform->addElement('html', '

	 <div id="wrapper" style="padding:10px">

	 		<div id="left" style="padding-right:10px">



						 <div style="border: 1px #000000 solid; width:605px; background:  #E0E0E0">
						 '.get_string('comments','block_cmanager').'
						 </div>




						' . $htmloutput . '

				</div>

			<div id="right">
				<form action ="comment.php" method ="post">
				<textarea id="newcomment" name="newcomment" rows="6" cols="50"></textarea>
				<p></p>
				<input type="submit" value="'.get_string('comments_PostComment','block_cmanager').'"/>
				</form>
			</div>


	 </div>


	<p></p>
	<p></p>');



	}
}




   $mform = new block_cmanager_comment_form();//name of the form you defined in file above.



   if ($mform->is_cancelled()) {

	echo "<script>window.location='".$backlink."';</script>";
			die;

  } else if ($fromform=$mform->get_data()){


  } else {



 		$mform->focus();
	    $mform->set_data($mform);
	    $mform->display();

	    echo $OUTPUT->footer();


}


if($_POST){

global $USER, $CFG, $DB;

		// Add a record to the database
		$userid = $USER->id;
		$newrec = new stdClass();
		$newrec->instanceid = $mid;
		$newrec->createdbyid = $userid;
		$newrec->message = $_POST['newcomment'];
		$newrec->dt = date("Y-m-d H:i:s");
		$DB->insert_record('block_cmanager_comments', $newrec, false);

		// Send an email to everyone concerned.
		require_once('cmanager_email.php');
		$message = $_POST['newcomment'];
		// Get all user id's from the record
		$currentrecord =  $DB->get_record('block_cmanager_records', array('id'=>$mid));


		$user_id = '';
		$user_id = $currentrecord->createdbyid; // Add the current user

		// Get info about the current object.
		$currentrecord =  $DB->get_record('block_cmanager_records', array('id'=>$mid));

		// Send email to the user
		$replaceValues = array();
	    $replaceValues['[course_code'] = $currentrecord->modcode;
	    $replaceValues['[course_name]'] = $currentrecord->modname;
	    //$replaceValues['[p_code]'] = $currentrecord->progcode;
	    //$replaceValues['[p_name]'] = $currentrecord->progname;
	    $replaceValues['[e_key]'] = '';
	    $replaceValues['[full_link]'] = $CFG->wwwroot .'/blocks/cmanager/comment.php?id=' . $mid;
	    $replaceValues['[loc]'] = '';
		$replaceValues['[req_link]'] = $CFG->wwwroot .'/blocks/cmanager/view_summary.php?id=' . $mid;


		block_cmanager_email_comment_to_user($message, $user_id, $mid, $replaceValues);
		block_cmanager_email_comment_to_admin($message, $mid, $replaceValues);

		echo "<script> window.location = 'comment.php?type=".$type."&id=$mid';</script>";

}



