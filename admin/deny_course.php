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
global $CFG;
$formPath = "$CFG->libdir/formslib.php";
require_once($formPath);
require_login();

/** Navigation Bar **/
$PAGE->navbar->ignore_active();
$PAGE->navbar->add(get_string('cmanagerDisplay', 'block_cmanager'), new moodle_url('/blocks/cmanager/cmanager_admin.php'));
$PAGE->navbar->add(get_string('denycourse', 'block_cmanager'));
$PAGE->set_url('/blocks/cmanager/admin/deny_course.php');
$PAGE->set_context(context_system::instance());
$PAGE->set_heading(get_string('pluginname', 'block_cmanager'));
$PAGE->set_title(get_string('pluginname', 'block_cmanager'));
echo $OUTPUT->header();


$context = context_system::instance();
if (has_capability('block/cmanager:denyrecord',$context)) {
} else {
  print_error(get_string('cannotdenyrecord', 'block_cmanager'));
}


?>



<?php

if(isset($_GET['id'])){
	$mid = required_param('id', PARAM_INT);
	$_SESSION['mid'] = $mid;
} else {

	$mid = $_SESSION['mid'];
}


echo '<script>
function goBack(){
	window.location ="../cmanager_admin.php";
}
</script>';



class block_cmanager_deny_form extends moodleform {

    function definition() {
    global $CFG;
    global $currentSess;
	global $mid;
	global $USER, $DB;

	$currentRecord =  $DB->get_record('block_cmanager_records', array('id'=>$currentSess));
	$mform =& $this->_form; // Don't forget the underscore!

	$denytext1 = $DB->get_field_select('block_cmanager_config', 'value', "varname = 'denytext1'");
	$denytext2 = $DB->get_field_select('block_cmanager_config', 'value', "varname = 'denytext2'");
	$denytext3 = $DB->get_field_select('block_cmanager_config', 'value', "varname = 'denytext3'");
	$denytext4 = $DB->get_field_select('block_cmanager_config', 'value', "varname = 'denytext4'");
	$denytext5 = $DB->get_field_select('block_cmanager_config', 'value', "varname = 'denytext5'");
	$mform->addElement('header', 'mainheader', '<span style="font-size:18px">'. get_string('denyrequest_Title','block_cmanager'). '</span>');


	// Page description text
	$mform->addElement('html', '<button type="button" onclick="goBack();"><img src="../icons/back.png"/>'.get_string('back','block_cmanager').'</button><p></p>

		<script>
		function addSelectedText(num){
			var value = document.getElementById(\'cusvalue\' + num).value;
			document.getElementById(\'newcomment\').value += value;

		}
		</script>

	<style>
			#wrapper {
		    width: 950px;
		    border: 1px solid black;
		    overflow: hidden; /* will contain if #first is longer than #second */
			}
			#left {
			    width: 400px;
			    float:left; /* add this */

			}
			#right {
			    border: 0px solid green;
			    overflow: hidden; /* if you dont want #second to wrap below #first */
			}

	 </style>
	 <center>

	 <div id="wrapper" style="padding:10px">


		<div id="left">
		<p></p><br>
		<form>
		<table width="300">
			 <tr>


		 		<td><textarea id="cusvalue1" rows="5"cols="60">'.$denytext1.'</textarea></td>
				<td>


							<button type="button" onclick="addSelectedText(1); return false;"> >> </button>

		 		</td>
		 	</tr>
		 	<tr>
		 	<td>
		 			<textarea type="text" id="cusvalue2" rows="5"cols="60">'.$denytext2.'</textarea>
		 		</td>
		 		<td>
		 			<button type="button" onclick="addSelectedText(2); return false;"> >> </button>


		 		</td>


		 	</tr>
		 	<tr>

		 	<td>
				<textarea type="text" id="cusvalue3" rows="5"cols="60">'.$denytext3.'</textarea>
				</td>

		 		<td>
		 			<button type="button" onclick="addSelectedText(3); return false;"> >> </button>

		 		</td>



		 	</tr>
		 	<tr>

		 	<td>
				<textarea type="text" id="cusvalue4" rows="5"cols="60">'.$denytext4.'</textarea>
				</td>
		 		<td>
		 			<button type="button" onclick="addSelectedText(4); return false;"> >> </button>

		 		</td>

		 	</tr>
		 	<tr>

		 	<td>
					<textarea type="text" id="cusvalue5" rows="5"cols="60">'.$denytext5.'</textarea>
		 		</td>
		 		<td>
		 			<button type="button" onclick="addSelectedText(5); return false;"> >> </button>

		 		</td>


		 	</tr>
	 		</table>
	      </form>
		</div>
		<div id="right">
 			'.get_string('denyrequest_reason','block_cmanager').'.
 			<p></p>
		<textarea id="newcomment" name="newcomment" rows="30" cols="70" maxlength="280"></textarea>
		<p></p>
	</div>
		<input type="submit" value="'.get_string('denyrequest_Btn','block_cmanager').'"/>




		</div>
		</center>
	');






	}
}

/** 
* Get custom text
*/
function customText(){

	global $DB;

	$optionHTML = 'hh';
	// Deny Text
			$denytext1 = $DB->get_field_select('block_cmanager_config', 'value', "varname = 'denytext1'");
			if(!empty($denytext1)){
				$optionHTML .= '<option value="'.$denytext1.'">'.$denytext1.'</option>';
			}

			$denytext2 = $DB->get_field_select('block_cmanager_config', 'value', "varname = 'denytext2'");
			if(!empty($denytext2)){
				$optionHTML .= '<option value="'.$denytext2.'">'.$denytext2.'</option>';
			}
			$denytext3 = $DB->get_field_select('block_cmanager_config', 'value', "varname = 'denytext3'");

			if(!empty($denytext3)){
				$optionHTML .= '<option value="'.$denytext3.'">'.$denytext3.'</option>';
			}

			$denytext4 = $DB->get_field_select('block_cmanager_config', 'value', "varname = 'denytext4'");
		if(!empty($denytext4)){
				$optionHTML .= '<option value="'.$denytext4.'">'.$denytext4.'</option>';
			}

			$denytext5 = $DB->get_field_select('block_cmanager_config', 'value', "varname = 'denytext5'");
			if(!empty($denytext5)){
				$optionHTML .= '<option value="'.$denytext5.'">'.$denytext5.'</option>';
			}


	return $optionHTML;
    }

   $mform = new block_cmanager_deny_form();//name of the form you defined in file above.



   if ($mform->is_cancelled()){

	echo "<script>window.location='../cmanager_admin.php';</script>";
			die;

  } else if ($fromform=$mform->get_data()){
		global $USER;

  } else {




		$mform->focus();
	    $mform->set_data($mform);
	    $mform->display();
	  	echo $OUTPUT->footer();

}
/**
* Get a username for a given ID from Moodle
*/
function block_cmanager_get_username($id){

	global $DB;
	return $username = get_field('user', 'username', array('id'=>$id));

}


if($_POST){
	global $CFG, $DB;

		// Send Email to all concerned about the request deny.
		require_once('../cmanager_email.php');


		$message = $_POST['newcomment'];



		// update the request record
		$newrec = new stdClass();
		$newrec->id = $mid;
		$newrec->status = 'REQUEST DENIED';
		$DB->update_record('block_cmanager_records', $newrec);

		// Add a comment to the module
		$userid = $USER->id;
		$newrec = new stdClass();
		$newrec->instanceid = $mid;
		$newrec->createdbyid = $userid;
		$newrec->message = $message;
		$newrec->dt = date("Y-m-d H:i:s");
		$DB->insert_record('block_cmanager_comments', $newrec, false);



		$currentRecord =  $DB->get_record('block_cmanager_records', array('id'=>$mid));

		$requesterId = 	$currentRecord->createdbyid; // Store the ID of the person who made the request


		$replaceValues = array();
	    $replaceValues['[course_code'] = $currentRecord->modcode;
	    $replaceValues['[course_name]'] = $currentRecord->modname;
	    //$replaceValues['[p_code]'] = $currentRecord->progcode;
	    //$replaceValues['[p_name]'] = $currentRecord->progname;
	    $replaceValues['[e_key]'] = '';
	    $replaceValues['[full_link]'] = $CFG->wwwroot.'/blocks/cmanager/comment.php?id=' . $mid;
	    $replaceValues['[loc]'] = '';
	    $replaceValues['[req_link]'] = $CFG->wwwroot.'/blocks/cmanager/view_summary.php?id=' . $mid;


		block_cmanager_send_deny_email_user($message, $requesterId, $mid, $replaceValues);

	   	block_cmanager_send_deny_email_admin($message, $mid, $replaceValues);


		echo "<script> window.location = '../cmanager_admin.php';</script>";

}

?>
