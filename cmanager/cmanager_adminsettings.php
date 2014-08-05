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
require_once("$CFG->libdir/formslib.php");
require_login();
require_once('validate_admin.php');

$PAGE->set_url('/blocks/cmanager/cmanager_othersettings.php');
$PAGE->set_context(context_system::instance());


/** Navigation Bar **/
$PAGE->navbar->ignore_active();
$PAGE->navbar->add(get_string('cmanagerDisplay', 'block_cmanager'), new moodle_url('/blocks/cmanager/cmanager_admin.php'));
$PAGE->navbar->add(get_string('configurecoursemanagersettings', 'block_cmanager'), new moodle_url('/blocks/cmanager/cmanager_confighome.php'));
$PAGE->navbar->add(get_string('configureadminsettings', 'block_cmanager'));
$PAGE->set_heading(get_string('pluginname', 'block_cmanager'));
$PAGE->set_title(get_string('pluginname', 'block_cmanager'));
echo $OUTPUT->header();


$context = context_system::instance();
if (has_capability('block/cmanager:viewconfig',$context)) {
} else {
  print_error(get_string('cannotviewconfig', 'block_cmanager'));
}

?>


<link rel="stylesheet" type="text/css" href="css/main.css" />
<script src="js/jquery/jquery-1.7.2.min.js"></script>

  <script>
// needed anymore????
  $(document).ready(function() {
    $("#accordion").accordion();
  });

  $(document).ready(function() {
    $("#tabs").tabs();
  });


function cancelConfirm(i,langString) {
	var answer = confirm(langString)
	if (answer){

		window.location = "cmanager_othersettings.php?t=d&&id=" + i;
	}
	else{

	}
}

/*
 * This function is used to save the text from the
 * list of textareas using ajax.
 */
function saveChangedText(object, idname, langString) {

    var fieldvalue = object.value;


    $.post("ajax_functions.php", { type: 'updatefield', value: fieldvalue, id: idname },
    		   function(data) {
    		     alert("Changes have been saved!");
    		   });

}

</script>
<?php

// If any records were set to be deleted.
if (isset($_GET['t']) && isset($_GET['id'])) {
    if (required_param('t', PARAM_TEXT) == 'd') {
        $deleteId = required_param('id', PARAM_INT);
        // Delete the record
        $deleteQuery = "id = $deleteId";
        $DB->delete_records_select('block_cmanager_config', $deleteQuery);
        echo "<script>window.location='cmanager_othersettings.php';</script>";
    }
}


//did we make a change to the course name, enrolment key or date?
if (isset($_POST['naming']) && isset($_POST['key']) && isset($_POST['course_date']) && isset($_POST['defaultmail']) 
    && isset($_POST['snaming'])) {

        //update autoKey
        $newrec = new stdClass();
        $rowId = $DB->get_field_select('block_cmanager_config', 'id', "varname = 'autoKey'");
		$newrec->id = $rowId;
        $newrec->varname = 'autoKey';
        $newrec->value = $_POST['key'];
        $DB->update_record('block_cmanager_config', $newrec);

        //update naming
        $newrec = new stdClass();
        $rowId = $DB->get_field_select('block_cmanager_config', 'id', "varname = 'naming'");
        $newrec->id = $rowId;
        $newrec->varname = 'naming';
        $newrec->value = $_POST['naming'];
        $DB->update_record('block_cmanager_config', $newrec);

        //self car
        $newrec = new stdClass();
        $rowId = $DB->get_field_select('block_cmanager_config', 'id', "varname = 'selfcat'");
        $newrec->id = $rowId;
        $newrec->varname = 'selfcat';
        $newrec->value = $_POST['selfcat'];
        $DB->update_record('block_cmanager_config', $newrec);


        //update snaming
        $newrec = new stdClass();
        $rowId = $DB->get_field_select('block_cmanager_config', 'id', "varname = 'snaming'");
        $newrec->id = $rowId;
        $newrec->varname = 'snaming';
        $newrec->value = $_POST['snaming'];
        $DB->update_record('block_cmanager_config', $newrec);

        //retrieve updated date and convert to timestamp
        $courseTimeStamp = $_POST['course_date'];
        $courseTimeStamp = mktime (0, 0, 0, $courseTimeStamp['M'], $courseTimeStamp['d'], $courseTimeStamp['Y']);

        //add the new date to the config
        $newrec = new stdClass();
        $rowId = $DB->get_field_select('block_cmanager_config', 'id', "varname = 'startdate'");
        $newrec->id = $rowId;
        $newrec->varname = 'startdate';
        $newrec->value = $courseTimeStamp;
        $DB->update_record('block_cmanager_config', $newrec);
        echo "<script>alert('".get_string('ChangesSaved','block_cmanager')."');</script>";


        //update no reply email
        $newrec = new stdClass();
        $rowId = $DB->get_field_select('block_cmanager_config', 'id', "varname = 'emailSender'");
        $newrec->id = $rowId;
        $newrec->varname = 'emailSender';
        $newrec->value = $_POST['defaultmail'];
        $DB->update_record('block_cmanager_config', $newrec);

        //DENY TEXT
        $newrec = new stdClass();
        $rowId = $DB->get_field_select('block_cmanager_config', 'id', "varname = 'denytext1'");
        $newrec->id = $rowId;
        $newrec->varname = 'denytext1';
        $newrec->value = $_POST['denytext1'];
        $DB->update_record('block_cmanager_config', $newrec);


        $newrec = new stdClass();
        $rowId = $DB->get_field_select('block_cmanager_config', 'id', "varname = 'denytext2'");
        $newrec->id = $rowId;
        $newrec->varname = 'denytext2';
        $newrec->value = $_POST['denytext2'];
        $DB->update_record('block_cmanager_config', $newrec);


        $newrec = new stdClass();
        $rowId = $DB->get_field_select('block_cmanager_config', 'id', "varname = 'denytext3'");
        $newrec->id = $rowId;
        $newrec->varname = 'denytext3';
        $newrec->value = $_POST['denytext3'];
        $DB->update_record('block_cmanager_config', $newrec);


        $newrec = new stdClass();
        $rowId = $DB->get_field_select('block_cmanager_config', 'id', "varname = 'denytext4'");
        $newrec->id = $rowId;
        $newrec->varname = 'denytext4';
        $newrec->value = $_POST['denytext4'];
        $DB->update_record('block_cmanager_config', $newrec);


        $newrec = new stdClass();
        $rowId = $DB->get_field_select('block_cmanager_config', 'id', "varname = 'denytext5'");
        $newrec->id = $rowId;
        $newrec->varname = 'denytext5';
        $newrec->value = $_POST['denytext5'];
        $DB->update_record('block_cmanager_config', $newrec);


}


$naming = $DB->get_field_select('block_cmanager_config', 'value', "varname = 'naming'");

echo '
<script>
function goBack(){
	window.location ="cmanager_confighome.php";
}
</script>
';


/**
 * Admin settings
 *
 * Main form for the admin settings
 * @package    block_socialbookmark
 * @copyright  2014 Kyle Goslin, Daniel McSweeney
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_cmanager_adminsettings_form extends moodleform {

    function definition() {

    global $CFG;
    global $currentSess;
	global $mid;
	global $USER, $DB;

	global $naming;

    $currentRecord =  $DB->get_record('block_cmanager_records', array('id'=> $currentSess));
    $mform =& $this->_form; // Don't forget the underscore!
	$mform->addElement('header', 'mainheader', '<span style="font-size:18px"> '.get_string('configureadminsettings','block_cmanager').'</span>');

    // Back Button
	$mform->addElement('html', '<p></p>&nbsp;&nbsp;&nbsp;   <button type="button" onclick="goBack();"><img src="icons/back.png"/> '.get_string('back','block_cmanager').'</button><p></p>');



    $statsCode = get_string('totalRequests','block_cmanager').':';
    $whereQuery = "varname = 'admin_email'";
    $modRecords = $DB->get_recordset_select('block_cmanager_config', $whereQuery);


    //get the current values for naming and autoKey from the database and use in the setting of seleted values for dropdowns

    $autoKey = $DB->get_field_select('block_cmanager_config', 'value', "varname = 'autoKey'");

    $snaming = $DB->get_field_select('block_cmanager_config', 'value', "varname = 'snaming'");
    $emailSender = $DB->get_field_select('block_cmanager_config', 'value', "varname = 'emailSender'");

    $selfcat = $DB->get_field_select('block_cmanager_config', 'value', "varname = 'selfcat'");


    // Deny Text
    $denytext1 = $DB->get_field_select('block_cmanager_config', 'value', "varname = 'denytext1'");
    $denytext2 = $DB->get_field_select('block_cmanager_config', 'value', "varname = 'denytext2'");
    $denytext3 = $DB->get_field_select('block_cmanager_config', 'value', "varname = 'denytext3'");
    $denytext4 = $DB->get_field_select('block_cmanager_config', 'value', "varname = 'denytext4'");
    $denytext5 = $DB->get_field_select('block_cmanager_config', 'value', "varname = 'denytext5'");

    /////////////////////////////////////////////////////////////////////////////////////////////////////////

    $fragment2 = '
    <div id="fragment-2" style="padding-left: 2em;"><b>'.

    get_string('namingConvetion','block_cmanager').'

    </b><div style="font-size: 12px">
    <p></p>'.get_string('namingConvetionInstruction','block_cmanager').'<br><br>
    <form action="cmanager_othersettings.php" method="post">
    <select id="naming" name="naming">';


    $fragment2 .='
    <option value="1">'.get_string('namingConvetion_option1','block_cmanager').'</option>
    <option value="2">'.get_string('namingConvetion_option2','block_cmanager').'</option>
    <option value="3">'.get_string('namingConvetion_option3','block_cmanager').'</option>
    <option value="4">'.get_string('namingConvetion_option4','block_cmanager').'</option>
    <option value="5">'.get_string('namingConvetion_option5','block_cmanager').'</option>
    </select>
    <p></p>
    <br>
    <hr>
    <br>
    <p></p>
    </div><b>

    '.get_string('snamingConvetion','block_cmanager').'

    </b><div style="font-size: 12px">
    <p></p>
    '.get_string('snamingConvetionInstruction','block_cmanager').'
    <br><br>
    <select name="snaming">';

    if ($snaming == 1) {
    	$fragment2 .='
    	<option value="1" selected="selected">'.get_string('snamingConvetion_option1','block_cmanager').'</option>
    	<option value="2">'.get_string('snamingConvetion_option2','block_cmanager').'</option>';
    }

    else if ($snaming == 2) {
    	$fragment2 .='
    	<option value="1">'.get_string('snamingConvetion_option1','block_cmanager').'</option>
    	<option value="2" selected="selected">'.get_string('snamingConvetion_option2','block_cmanager').'</option>';
    }


    //
    // User enrollment key option selection
    //
    $fragment2 .='
    	</select>
    	<p></p>
    	<br>
    	<hr>
    	<br>
    	<p></p>
    	</div><b>'.get_string('configure_EnrolmentKey','block_cmanager').' </b><div style="font-size: 12px">
    	<p></p>
    	'.get_string('cmanagerEnrolmentInstruction','block_cmanager').'<br><br>
    	<select name="key">';

        if ($autoKey == 0) {
            $fragment2 .='
            <option value="0" selected="selected">'.get_string('cmanagerEnrolmentOption2','block_cmanager').'</option>
            <option value="1">'.get_string('cmanagerEnrolmentOption1','block_cmanager').'</option>
            <option value="2">'.get_string('cmanagerEnrolmentOption3','block_cmanager').'</option>';
        }
        else if ($autoKey == 1) {
            $fragment2 .='
            <option value="0">'.get_string('cmanagerEnrolmentOption2','block_cmanager').'</option>
            <option value="1" selected="selected">'.get_string('cmanagerEnrolmentOption1','block_cmanager').'</option>
            <option value="2">'.get_string('cmanagerEnrolmentOption3','block_cmanager').'</option>';
        }
        else if ($autoKey == 2) {
            $fragment2 .='
            <option value="0">'.get_string('cmanagerEnrolmentOption2','block_cmanager').'</option>
            <option value="1">'.get_string('cmanagerEnrolmentOption1','block_cmanager').'</option>
            <option value="2" selected="selected">'.get_string('cmanagerEnrolmentOption3','block_cmanager').'</option>';
        }


	//
	// Clear history options
	//
	$fragment2 .='
		</select>
			<hr>
			<p></p>
			<div style="font-size: 14px"><b>
			'.get_string('clearHistoryTitle','block_cmanager').'
			</b></div>
			<p></p>
				<div style="font-size: 12px">


			<script>
			function deleteAll(){
				window.location="history/delete.php?delete=all";
			}
			function deleteArchOnly(){
				window.location="history/delete.php?delete=archonly";
			}
			</script>
				<input type="button" onClick="deleteAll()" value="'.get_string('deleteAllRequests', 'block_cmanager').'"><p></p>
				<input type="button" onClick="deleteArchOnly()" value="'.get_string('deleteOnlyArch', 'block_cmanager').'">


				</div>
		<p></p>
		<br>
		<hr>
		<br>
			<p></p>
			<div style="font-size: 14px"><b>
			'.get_string('allowSelfCategorization','block_cmanager').'
			</b></div>
			<p></p>
			'.get_string('allowSelfCategorization_desc', 'block_cmanager').'
			<p></p>';

		//
		//  User select category
		//
		if ($selfcat == 'yes') {
		 $fragment2 .= '
		 	<div style="font-size: 12px">
					<select name="selfcat">
					<option value="yes" selected="selected">'.get_string('selfCatOn', 'block_cmanager').'</option>
					<option value="no">'.get_string('selfCatOff', 'block_cmanager').'</option>
					</select>
			</div>
		 ';
		} else if ($selfcat == 'no') {
			 $fragment2 .= '
		 	<div style="font-size: 12px">
					<select name="selfcat">
					<option value="yes">'.get_string('selfCatOn', 'block_cmanager').'</option>
					<option value="no" selected="selected">'.get_string('selfCatOff', 'block_cmanager').'</option>
					</select>
				</div>
		 ';
		}




		$fragment2 .='

		<p></p>
		<br>
		<hr>
		<br>
		<p></p>
	</div><b>

	'.get_string('email_noReply','block_cmanager').'

	</b><div style="font-size: 12px">
		<p></p>
		'.get_string('email_noReplyInstructions','block_cmanager').'
		<p></p>
		'.get_string('config_addemail','block_cmanager').'
		<input type="text" name="defaultmail"  size="50" id="defaultemail" value="'.$emailSender.'"/>
		<p></p>
		<br>
		<hr>
		<br>
	</div>


	<div style="font-size: 14px"><b>
		<p></p>
		'.get_string('customdeny','block_cmanager').'
		<p></p>
		</b><div>
		'.get_string('customdenydesc','block_cmanager').'
		<br>
		<p> </p>
	<table  cellpadding="10">
		<tr>
			<td><textarea id="denytext1" name="denytext1" rows="10" cols="80" maxlength="250">'.$denytext1.'</textarea></td>
			<td><b>'.get_string('denytext1','block_cmanager').'</b></td>
		</tr>
		<tr>
			<td><textarea id="denytext2" name="denytext2" rows="10"cols="80" maxlength="250">'.$denytext2.'</textarea></td>
			<td><b>'.get_string('denytext2','block_cmanager').'</b></td>
		</tr>
		<tr>
			<td><textarea id="denytext3" name="denytext3" rows="10"cols="80" maxlength="250">'.$denytext3.'</textarea></td>
			<td><b>'.get_string('denytext3','block_cmanager').'</b></td>
		</tr>
		<tr>
			<td><textarea id="denytext4" name="denytext4" rows="10"cols="80" maxlength="250">'.$denytext4.'</textarea></td>
			<td><b>'.get_string('denytext4','block_cmanager').'</b></td>
		</tr>
		<tr>
			<td><textarea id="denytext5" name="denytext5" rows="10"cols="80" maxlength="250">'.$denytext5.'</textarea></td>
			<td><b>'.get_string('denytext5','block_cmanager').'</b></td>
		</tr>

	</table>

		</div>

		</div>
		<p></p>
    <hr><b>


	'.get_string('configure_defaultStartDate','block_cmanager').'

	</b><div style="font-size: 12px">

	<p></p>
	'.get_string('configure_defaultStartDateInstructions','block_cmanager').'<br><br>


	<p></p>


	';




/////////////////////////////////////////////////////////////////////////////////////////////////////////
$saveall = '
</div>
<br><br><br>
<span style="font-size:12px"><center><input type="submit" value="'.get_string('SaveAll','block_cmanager').'" /></center></span>
</form>
</div> <!--end of fragment 2 -->
</div><!--tabs tag -->
';

$mainSlider = '
	<p></p>
	&nbsp;
	<p></p>'.$fragment2;

    //add the main slider
    $mform->addElement('html', $mainSlider);


    $timestamp_startdate = $DB->get_field_select('block_cmanager_config', 'value', "varname = 'startdate'");
    //convert to date
    $startdate = getdate($timestamp_startdate);
    //add the date selector and set defaults
    $date_options = array('format' => 'dMY', 'minYear' => 2012, 'maxYear' => 2020);

    $mform->addElement('date', 'course_date', 'Date:', $date_options);
    $date_defaults = array('d' => $startdate['mday'], 'M' => $startdate['mon'], 'Y' => $startdate['year']);
    $mform->setDefaults(array('course_date' => $date_defaults));

    //close off the html and form
    $mform->addElement('html', $saveall);
	}
}


$mform = new block_cmanager_adminsettings_form();

if ($mform->is_cancelled()) {
    echo "<script>window.location='../cmanager_admin.php';</script>";
    die;
} else if (isset($_POST['addemailbutton'])) {
    global $USER;
    global $CFG;

    // Add an email address
    $post_email = addslashes($_POST['newemail']);

    if ($post_email != '' && block_cmanager_validate_email($post_email)) {
        $newrec = new stdClass();
        $newrec->varname = 'admin_email';
        $newrec->value = $post_email;
        $DB->insert_record('block_cmanager_config', $newrec);
    }

    echo "<script>window.location='cmanager_othersettings.php';</script>";
    die;

} else {
    $mform->focus();
    $mform->set_data($mform);
    $mform->display();
    echo $OUTPUT->footer();
}


/**
 * Very basic funciton for validating an email address.
 * This should really be replaced with something a little better!
 */
function block_cmanager_validate_email($email) {

    $valid = true;

    if ($email == '') {
	   $valid = false;
    }

	$pos = strpos($email, '.');
	if ($pos === false) {
		$valid = false;
	}

	$pos = strpos($email, '@');
	if ($pos === false) {
		$valid = false;
	}

	if ($valid) {
	   return true;
	} else {
	    return false;
	}

}
if (!empty($naming)) {
	echo '<script> document.getElementById("naming").value = '.$naming.'; </script> ';
}


