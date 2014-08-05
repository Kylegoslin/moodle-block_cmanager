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

$PAGE->set_url('/blocks/cmanager/cmanager_config.php');
$PAGE->set_context(context_system::instance());


/** Navigation Bar **/
$PAGE->navbar->ignore_active();
$PAGE->navbar->add(get_string('cmanagerDisplay', 'block_cmanager'), new moodle_url('/blocks/cmanager/cmanager_admin.php'));
$PAGE->navbar->add(get_string('configurecoursemanagersettings', 'block_cmanager'), new moodle_url('/blocks/cmanager/cmanager_confighome.php'));
$PAGE->navbar->add(get_string('emailConfig', 'block_cmanager'));
$PAGE->set_heading(get_string('pluginname', 'block_cmanager'));
$PAGE->set_title(get_string('pluginname', 'block_cmanager'));
echo $OUTPUT->header();



$context = context_system::instance();
if (has_capability('block/cmanager:viewconfig',$context)) {
} else {
  print_error(get_string('cannotviewconfig', 'block_cmanager'));
}


?>
<head>


<link rel="stylesheet" type="text/css" href="css/main.css" />
<script src="js/jquery/jquery-1.7.2.min.js"></script>


  <script>
  $(document).ready(function() {
    $("#accordion").accordion();
  });

  $(document).ready(function() {
    $("#tabs").tabs();
  });


function cancelConfirm(i,langString) {
	var answer = confirm(langString)
	if (answer){

		window.location = "cmanager_config.php?t=d&&id=" + i;
	}
	else{

	}
}

/**
 * This function is used to save the text from the
 * list of textareas using ajax.
 */
function saveChangedText(object, idname, langString){

    var fieldvalue = object.value;


    $.post("ajax_functions.php", { type: 'updatefield', value: fieldvalue, id: idname },
    		   function(data) {
    		     alert("Changes have been saved!");
    		   });

}

</script>

</head>

<?php
// If any records were set to be deleted.
if (isset($_GET['t']) && isset($_GET['id'])) {
    if (required_param('t', PARAM_TEXT) == 'd') {
        $deleteId = required_param('id', PARAM_INT);  
        // Delete the record
        $deleteQuery = "id = $deleteId";
        $DB->delete_records_select('block_cmanager_config', $deleteQuery);
        echo "<script>window.location='cmanager_config.php';</script>";
    }
}


//did we make a change to the course name, enrolment key or date?
if (isset($_POST['naming']) && isset($_POST['key']) && isset($_POST['course_date']) 
    && isset($_POST['defaultmail']) &&isset($_POST['snaming'])) {

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


}

?>

<script>
function goBack(){
	window.location ="cmanager_confighome.php";
}
</script>
<?php


/**
 * Config form form cmanager
 *
 * @package    block_cmanager
 * @copyright  2014 Kyle Goslin, Daniel McSweeney
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_cmanager_config_form extends moodleform {

    function definition() {
    global $CFG;
    global $currentSess;
    global $mid;
    global $USER, $DB;


    $currentRecord =  $DB->get_record('block_cmanager_records', array('id'=> $currentSess));
    $mform =& $this->_form; // Don't forget the underscore!
    $mform->addElement('header', 'mainheader','<span style="font-size:18px"> '.get_string('emailConfig','block_cmanager').'</span>');

    // Back Button
    $mform->addElement('html', '<p></p>&nbsp;&nbsp;&nbsp;   <button type="button" onclick="goBack();"><img src="icons/back.png"/> '.get_string('back','block_cmanager').'</button><p></p>');

    // Email text box
    $approvedTextRecord = $DB->get_record('block_cmanager_config', array('varname'=>'approved_text'));

    $emailText = '';
    if ($approvedTextRecord != null) {
        $emailText = $approvedTextRecord->value;
    }

    // Approved user email
    $approved_user_email =  $DB->get_record('block_cmanager_config', array('varname'=>'approveduseremail'));
    $approved_user_email_value = '';
    if (!empty($approved_user_email)) {
        $approved_user_email_value = stripslashes($approved_user_email->value);
    }

    // Approved admin email
    $approved_admin_email =  $DB->get_record('block_cmanager_config', array('varname'=>'approvedadminemail'));
    $approved_admin_email_value = '';
    if (!empty($approved_admin_email)) {
        $approved_admin_email_value = stripslashes($approved_admin_email->value);
    }


    // Request new module user
    $request_new_module_user =  $DB->get_record('block_cmanager_config', array('varname'=>'requestnewmoduleuser'));
    $request_new_module_user_value = '';
    if (!empty($request_new_module_user)) {
    $request_new_module_user_value = stripslashes($request_new_module_user->value);
    }


    // Request new module admin
    $request_new_module_admin =  $DB->get_record('block_cmanager_config', array('varname'=>'requestnewmoduleadmin'));
    $request_new_module_admin_value = '';
    if (!empty($request_new_module_admin)) {
        $request_new_module_admin_value = stripslashes($request_new_module_admin->value);
    }


    // Comment email admin
    $comment_email_admin =  $DB->get_record('block_cmanager_config', array('varname'=>'commentemailadmin'));
    $comment_email_admin_value = '';
    if (!empty($comment_email_admin)) {
        $comment_email_admin_value = stripslashes($comment_email_admin->value);
    }

    // Comment email user
    $comment_email_user =  $DB->get_record('block_cmanager_config', array('varname'=>'commentemailuser'));
    $comment_email_user_value = '';
    if (!empty($comment_email_user)) {
        $comment_email_user_value = stripslashes($comment_email_user->value);
    }

    // Request denied admin
    $module_request_denied_admin =  $DB->get_record('block_cmanager_config', array('varname'=>'modulerequestdeniedadmin'));
    $module_request_denied_admin_value = '';
    if (!empty($module_request_denied_admin)) {
        $module_request_denied_admin_value = stripslashes($module_request_denied_admin->value);
    }

    // Request denied user
    $module_request_denied_user =  $DB->get_record('block_cmanager_config', array('varname'=>'modulerequestdenieduser'));
    $module_request_denied_user_value = '';
    if (!empty($module_request_denied_user)) {
        $module_request_denied_user_value = stripslashes($module_request_denied_user->value);
    }


    // Handover current
    $handover_current =  $DB->get_record('block_cmanager_config', array('varname'=>'handovercurrent'));
    $handover_current_value = '';
    if (!empty($handover_current)) {
        $handover_current_value = stripslashes($handover_current->value);
    }

    //Handover user
    $handover_user =  $DB->get_record('block_cmanager_config', array('varname'=>'handoveruser'));
    $handover_user_value = '';
    if (!empty($handover_user)) {
         $handover_user_value = stripslashes($handover_user->value);
    }

    // Handover admin
    $handover_admin =  $DB->get_record('block_cmanager_config', array('varname'=>'handoveradmin'));
    $handover_admin_value = '';
    if (!empty($handover_admin)) {
        $handover_admin_value = stripslashes($handover_admin->value);
    }


    $statsCode = get_string('totalRequests','block_cmanager').':'; 
    $whereQuery = "varname = 'admin_email'";
    $modRecords = $DB->get_recordset_select('block_cmanager_config', $whereQuery);

    //get the current values for naming and autoKey from the database and use in the setting of seleted values for dropdowns
    $autoKey = $DB->get_field_select('block_cmanager_config', 'value', "varname = 'autoKey'");
    $naming = $DB->get_field_select('block_cmanager_config', 'value', "varname = 'naming'");
    $snaming = $DB->get_field_select('block_cmanager_config', 'value', "varname = 'snaming'");
    $emailSender = $DB->get_field_select('block_cmanager_config', 'value', "varname = 'emailSender'");

    $selfcat = $DB->get_field_select('block_cmanager_config', 'value', "varname = 'selfcat'");
    /////////////////////////////////////////////////////////////////////////////////////////////////////////
    //fragment 1 (placed on tab 2)
    /////////////////////////////////////////////////////////////////////////////////////////////////////////


   $fragment1 = '
   <div id="fragment-1" style="padding-left: 2em;">
   <p></p><h4><b>'.
   get_string('emailConfigSectionHeader','block_cmanager').'</b></h4><br><p></p>
   <span style="font-size:12px">'.get_string('emailConfigInfo','block_cmanager').'</span>
   <div style="font-size: 12px">
   <br> '.get_string('config_addemail','block_cmanager').'
   <input type="text" name="newemail" id="newemail"/>
   <input type="submit" name="addemailbutton" id="addemailbutton" value="'.get_string('SaveEMail','block_cmanager').'"/>
   </div>
   <p></p> 
   &nbsp;
   <p></p>
   <table>';

    foreach ($modRecords as $record) {
        $fragment1 .='<tr>';
        $fragment1 .=' <td width="230px"><span style="font-size: 12px">' . $record->value . '</span></td>';
        $fragment1 .=' <td width="100px"><a onclick="cancelConfirm('. $record->id .',\''.get_string('configure_deleteMail','block_cmanager').'\')" href="#"><span style="font-size: 12px"><b>'.get_string('delete','block_cmanager').'</b></span></a></td>';
        $fragment1 .='<tr>';
    }


    $fragment1 .= '
    </table>
    <br>
    <hr>
    <br><h4><b>'.get_string('emailConfigContents','block_cmanager').'
    </b></h4><div style="font-size: 12px">
    <p></p>'.get_string('emailConfigHeader','block_cmanager').'
    <p></p>
    <b>'.get_string('email_courseCode','block_cmanager').':</b> [course_code]<br>
    <b>'.get_string('email_courseName','block_cmanager').':</b> [course_name]<br>
    <b>'.get_string('email_enrolmentKey','block_cmanager').':</b> [e_key]<br>
    <b>'.get_string('email_fullURL','block_cmanager').':</b> [full_link]<br>
    <b>'.get_string('email_sumLink','block_cmanager').':</b> [req_link]<br>
    <p></p>
    <br>

    <div>
    <br><h4>'.get_string('email_newCourseApproved','block_cmanager').' - '.get_string('email_UserMail','block_cmanager').'</h4>
    <div>
    '.get_string('configure_leaveblankmail','block_cmanager').'
    <p>
    <textarea name="approveduseremail" id="approveduseremail"  style="width:70%; height: 250px;">'.$approved_user_email_value.'</textarea>
    <br>
    <input type="button" value="'.get_string('SaveChanges','block_cmanager').'" onClick="saveChangedText(approveduseremail, \'approveduseremail\',\''.get_string('ChangesSaved','block_cmanager').'\')"/>
    </p>
    </div>

    <br><h4>'.get_string('email_newCourseApproved','block_cmanager').' - '.get_string('email_AdminMail','block_cmanager').'</h4>
    <div>'.get_string('configure_leaveblankmail','block_cmanager').'
    <p>
    <textarea name="approvedadminemail" id="approvedadminemail" style="width:70%; height: 250px;">'.$approved_admin_email_value.'</textarea>
    <br>
    <input type="button" value="'.get_string('SaveChanges','block_cmanager').'" onClick="saveChangedText(approvedadminemail, \'approvedadminemail\')"/>
    </p>
    </div>

    <br><h4>'.get_string('email_requestNewModule','block_cmanager').' - '.get_string('email_UserMail','block_cmanager').'</h4>
    <div>
    '.get_string('configure_leaveblankmail','block_cmanager').'
    <p>
    <textarea name="requestnewmoduleuser" id="requestnewmoduleuser" style="width:70%; height: 250px;">'.$request_new_module_user_value.'</textarea><br>
    <input type="button" value="'.get_string('SaveChanges','block_cmanager').'" onClick="saveChangedText(requestnewmoduleuser, \'requestnewmoduleuser\')"/>
    </p>
    </div>

    <br><h4>'.get_string('email_requestNewModule','block_cmanager').' - '.get_string('email_AdminMail','block_cmanager').'</h4>
    <div>
    '.get_string('configure_leaveblankmail','block_cmanager').'
    <p>
    <textarea name="requestnewmoduleadmin" id="requestnewmoduleadmin" style="width:70%; height: 250px;">'.$request_new_module_admin_value.'</textarea>
    <br>
    <input type="button" value="'.get_string('SaveChanges','block_cmanager').'" onClick="saveChangedText(requestnewmoduleadmin, \'requestnewmoduleadmin\')"/>
    </p>
    </div>

    <br><h4>'.get_string('email_commentNotification','block_cmanager').' - '.get_string('email_AdminMail','block_cmanager').'</h4>
    <div>
    '.get_string('configure_leaveblankmail','block_cmanager').'
    <p>
    <textarea name="commentemailadmin" id="commentemailadmin" style="width:70%; height: 250px;">'.$comment_email_admin_value.'</textarea>
    <br>
    <input type="button" value="'.get_string('SaveChanges','block_cmanager').'" onClick="saveChangedText(commentemailadmin, \'commentemailadmin\')"/>
    </p>
    </div>

    <br><h4>'.get_string('email_commentNotification','block_cmanager').' - '.get_string('email_UserMail','block_cmanager').'</h4>
    <div>
    '.get_string('configure_leaveblankmail','block_cmanager').'
    <p>
    <textarea name="commentemailuser" id="commentemailuser" style="width:70%; height: 250px;">'.$comment_email_user_value.'</textarea><br>
    <input type="button" value="'.get_string('SaveChanges','block_cmanager').'" onClick="saveChangedText(commentemailuser, \'commentemailuser\')"/>
    </p>
    </div>


    <br><h4>'.get_string('email_requestDenied','block_cmanager').' - '.get_string('email_AdminMail','block_cmanager').'</h4>
    <div>
    '.get_string('configure_leaveblankmail','block_cmanager').'
    <p>
    <textarea name="modulerequestdeniedadmin" id="modulerequestdeniedadmin" style="width:70%; height: 250px;">'.$module_request_denied_admin_value.'</textarea>
    <br>
    <input type="button" value="'.get_string('SaveChanges','block_cmanager').'" onClick="saveChangedText(modulerequestdeniedadmin, \'modulerequestdeniedadmin\')"/>
    </p>
    </div>

    <br><h4>'.get_string('email_requestDenied','block_cmanager').' - '.get_string('email_UserMail','block_cmanager').'</h4>
    <div>
    '.get_string('configure_leaveblankmail','block_cmanager').'
    <p>
    <textarea name="modulerequestdenieduser" id="modulerequestdenieduser" style="width:70%; height: 250px;">'.$module_request_denied_user_value.'</textarea><br>
    <input type="button" value="'.get_string('SaveChanges','block_cmanager').'" onClick="saveChangedText(modulerequestdenieduser, \'modulerequestdenieduser\')"/>
    </p>
    </div>


    <br><h4>'.get_string('email_handover','block_cmanager').' - '.get_string('email_currentOwner','block_cmanager').'</h4>
    <div>
    '.get_string('configure_leaveblankmail','block_cmanager').'
    <p>
    <textarea name="handovercurrent" id="handovercurrent" style="width:70%; height: 250px;">'.$handover_current_value.'</textarea><br>
    <input type="button" value="'.get_string('SaveChanges','block_cmanager').'" onClick="saveChangedText(handovercurrent, \'handovercurrent\')"/>
    </p>
    </div>

    <br><h4>'.get_string('email_handover','block_cmanager').' - '.get_string('email_UserMail','block_cmanager').'</h4>
    <div>
    '.get_string('configure_leaveblankmail','block_cmanager').'
    <p>
    <textarea name="handoveruser" id="handoveruser" style="width:70%; height: 250px;">'.$handover_user_value.'</textarea><br>
    <input type="button" value="'.get_string('SaveChanges','block_cmanager').'" onClick="saveChangedText(handoveruser, \'handoveruser\')"/>
    </p>
    </div>


    <br><h4>'.get_string('email_handover','block_cmanager').' - '.get_string('email_AdminMail','block_cmanager').'</h4>
    <div>
    '.get_string('configure_leaveblankmail','block_cmanager').'
    <p>
    <textarea name="handoveradmin" id="handoveradmin" style="width:70%; height: 250px;">'.$handover_admin_value.'</textarea><br>
    <input type="button" value="'.get_string('SaveChanges','block_cmanager').'" onClick="saveChangedText(handoveradmin, \'handoveradmin\')"/>
    </p>
    </div>
    <br>
    </div>
    </div> <!--end of div for email boxes -->
    </div><!--end of div for fragment1 -->
    ';



    /////////////////////////////////////////////////////////////////////////////////////////////////////////

    $mainSlider = '
    <p></p>
    &nbsp;
    <p></p>
    '. $fragment1.'


    ';

    //add the main slider
    $mform->addElement('html', $mainSlider);


}
}

$mform = new block_cmanager_config_form();

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

   echo "<script>window.location='cmanager_config.php';</script>";
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


?>

