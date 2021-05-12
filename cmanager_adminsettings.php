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
 * @copyright  2014-2018 Kyle Goslin, Daniel McSweeney
 * @copyright  2021 Michael Milette (TNG Consulting Inc.), Daniel Keaman
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once('../../config.php');
require_once($CFG->libdir . '/formslib.php');
$PAGE->set_url('/blocks/cmanager/cmanager_adminsettings.php');

require_login(null, false);

if (!is_siteadmin()) {
    print_error('accessdenied', 'admin');
}

$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('standard');

require_once('lib/boot.php');

/** Navigation Bar **/
//$PAGE->navbar->ignore_active();
$PAGE->navigation->clear_cache();
$PAGE->navbar->add(get_string('cmanagerDisplay', 'block_cmanager'), new moodle_url('/blocks/cmanager/cmanager_admin.php'));
$PAGE->navbar->add(get_string('configurecoursemanagersettings', 'block_cmanager'), new moodle_url('/blocks/cmanager/cmanager_confighome.php'));
$PAGE->navbar->add(get_string('configureadminsettings', 'block_cmanager'));
$PAGE->set_heading(get_string('configureadminsettings', 'block_cmanager'));
$PAGE->set_title(get_string('configureadminsettings', 'block_cmanager'));

echo $OUTPUT->header();

$context = context_system::instance();
if (!has_capability('block/cmanager:viewconfig',$context)) {
  print_error(get_string('cannotviewconfig', 'block_cmanager'));
  exit();
}

// If any records were set to be deleted.
$t = optional_param('t', null, PARAM_TEXT);
if ($t == 'd') {
    $deleteId = required_param('id', PARAM_INT);
    // Delete the record
    $deleteQuery = "id = $deleteId";
    $DB->delete_records_select('block_cmanager_config', $deleteQuery);
    header('Location: cmanager_othersettings.php');
}

if (isset($_POST['naming']) && isset($_POST['key']) && isset($_POST['course_date']) && isset($_POST['defaultmail']) && isset($_POST['snaming'])) {

    // Did we make a change to the course name, enrolment key or date?
    $key = required_param('key', PARAM_TEXT);
    $naming = required_param('naming', PARAM_TEXT);
    $selfcat = required_param('selfcat', PARAM_TEXT);
    $snaming = required_param('snaming', PARAM_TEXT);
    $defaultmail = required_param('defaultmail', PARAM_EMAIL);
    // Retrieve updated date and convert to timestamp.
    $course_date = required_param_array('course_date', PARAM_TEXT);
    $course_date = mktime (0, 0, 0, $course_date['M'], $course_date['d'], $course_date['Y']);

    $denytext1 = required_param('denytext1', PARAM_TEXT);
    $denytext2 = required_param('denytext2', PARAM_TEXT);
    $denytext3 = required_param('denytext3', PARAM_TEXT);
    $denytext4 = required_param('denytext4', PARAM_TEXT);
    $denytext5 = required_param('denytext5', PARAM_TEXT);

    // Update autoKey.
    $newrec = new stdClass();
    $rowId = $DB->get_field_select('block_cmanager_config', 'id', "varname = 'autoKey'");
    $newrec->id = $rowId;
    $newrec->varname = 'autoKey';
    $newrec->value = $key;
    $DB->update_record('block_cmanager_config', $newrec);

    // Update naming.
    $newrec = new stdClass();
    $rowId = $DB->get_field_select('block_cmanager_config', 'id', "varname = 'naming'");
    $newrec->id = $rowId;
    $newrec->varname = 'naming';
    $newrec->value = $naming;
    $DB->update_record('block_cmanager_config', $newrec);

    // Update Selfcat.
    $newrec = new stdClass();
    $rowId = $DB->get_field_select('block_cmanager_config', 'id', "varname = 'selfcat'");
    $newrec->id = $rowId;
    $newrec->varname = 'selfcat';
    $newrec->value = $selfcat;
    $DB->update_record('block_cmanager_config', $newrec);

    // Update snaming.
    $newrec = new stdClass();
    $rowId = $DB->get_field_select('block_cmanager_config', 'id', "varname = 'snaming'");
    $newrec->id = $rowId;
    $newrec->varname = 'snaming';
    $newrec->value = $snaming;
    $DB->update_record('block_cmanager_config', $newrec);

    // Add the new date to the config.
    $newrec = new stdClass();
    $rowId = $DB->get_field_select('block_cmanager_config', 'id', "varname = 'startdate'");
    $newrec->id = $rowId;
    $newrec->varname = 'startdate';
    $newrec->value = $course_date;
    $DB->update_record('block_cmanager_config', $newrec);

    // Update no-reply email address.
    $newrec = new stdClass();
    $rowId = $DB->get_field_select('block_cmanager_config', 'id', "varname = 'emailSender'");
    $newrec->id = $rowId;
    $newrec->varname = 'emailSender';
    $newrec->value = $defaultmail;
    $DB->update_record('block_cmanager_config', $newrec);

    // Denial Text Templates for e-mail messages.

    // Update reason 1.
    $newrec = new stdClass();
    $rowId = $DB->get_field_select('block_cmanager_config', 'id', "varname = 'denytext1'");
    $newrec->id = $rowId;
    $newrec->varname = 'denytext1';
    $newrec->value = $denytext1;
    $DB->update_record('block_cmanager_config', $newrec);

    // Update reason 2.
    $newrec = new stdClass();
    $rowId = $DB->get_field_select('block_cmanager_config', 'id', "varname = 'denytext2'");
    $newrec->id = $rowId;
    $newrec->varname = 'denytext2';
    $newrec->value = $denytext2;
    $DB->update_record('block_cmanager_config', $newrec);

    // Update reason 3.
    $newrec = new stdClass();
    $rowId = $DB->get_field_select('block_cmanager_config', 'id', "varname = 'denytext3'");
    $newrec->id = $rowId;
    $newrec->varname = 'denytext3';
    $newrec->value = $denytext3;
    $DB->update_record('block_cmanager_config', $newrec);

    // Update reason 4.
    $newrec = new stdClass();
    $rowId = $DB->get_field_select('block_cmanager_config', 'id', "varname = 'denytext4'");
    $newrec->id = $rowId;
    $newrec->varname = 'denytext4';
    $newrec->value = $denytext4;
    $DB->update_record('block_cmanager_config', $newrec);

    // Update reason 5.
    $newrec = new stdClass();
    $rowId = $DB->get_field_select('block_cmanager_config', 'id', "varname = 'denytext5'");
    $newrec->id = $rowId;
    $newrec->varname = 'denytext5';
    $newrec->value = $denytext5;
    $DB->update_record('block_cmanager_config', $newrec);

    echo generateGenericPop('saved', get_string('ChangesSaved','block_cmanager'), get_string('ChangesSaved','block_cmanager'), get_string('ok','block_cmanager') );
    echo '<script>$("#saved").modal(); </script>';

    //echo "<script>alert('".get_string('ChangesSaved','block_cmanager')."');</script>";

}

$naming = $DB->get_field_select('block_cmanager_config', 'value', "varname = 'naming'");

/**
 * Admin settings
 *
 * Main form for the admin settings
 * @package    block_cmanager
 * @copyright  2018 Kyle Goslin, Daniel McSweeney
 * @copyright  2021 Michael Milette (TNG Consulting Inc.), Daniel Keaman
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

        // Back Button.

        $mform->addElement('html', '<p><a href="cmanager_confighome.php" class="btn btn-default"><img src="icons/back.png" alt=""> '.get_string('back','block_cmanager').'</a></p>');

        $statsCode = get_string('totalRequests','block_cmanager').':';
        $whereQuery = "varname = 'admin_email'";
        $modRecords = $DB->get_recordset_select('block_cmanager_config', $whereQuery);

        // Get the current values for naming and autoKey from the database and use in the setting of selected values for dropdowns.

        $autoKey = $DB->get_field_select('block_cmanager_config', 'value', "varname = 'autoKey'");

        $snaming = $DB->get_field_select('block_cmanager_config', 'value', "varname = 'snaming'");
        $emailSender = $DB->get_field_select('block_cmanager_config', 'value', "varname = 'emailSender'");

        $selfcat = $DB->get_field_select('block_cmanager_config', 'value', "varname = 'selfcat'");


        // Deny text.
        $denytext1 = $DB->get_field_select('block_cmanager_config', 'value', "varname = 'denytext1'");
        $denytext2 = $DB->get_field_select('block_cmanager_config', 'value', "varname = 'denytext2'");
        $denytext3 = $DB->get_field_select('block_cmanager_config', 'value', "varname = 'denytext3'");
        $denytext4 = $DB->get_field_select('block_cmanager_config', 'value', "varname = 'denytext4'");
        $denytext5 = $DB->get_field_select('block_cmanager_config', 'value', "varname = 'denytext5'");

        /////////////////////////////////////////////////////////////////////////////////////////////////////////

        $fragment2 = '
        <form action="cmanager_othersettings.php" method="post">';

        // Course Naming Convention.
        $fragment2 .= '
            <h2 class="mt-3">' . get_string('namingConvetion','block_cmanager') . '</h2>
            <label for="naming">' . get_string('namingConvetionInstruction','block_cmanager') . '</label><br>
            <select id="naming" name="naming">
                <option value="1">'.get_string('namingConvetion_option1','block_cmanager').'</option>
                <option value="2">'.get_string('namingConvetion_option2','block_cmanager').'</option>
                <option value="3">'.get_string('namingConvetion_option3','block_cmanager').'</option>
                <option value="4">'.get_string('namingConvetion_option4','block_cmanager').'</option>
                <option value="5">'.get_string('namingConvetion_option5','block_cmanager').'</option>
            </select><br>
        ';

        // Short Name Format.
        $fragment2 .= '
            <h2 class="mt-3">' . get_string('snamingConvetion','block_cmanager').'</h2>
            <label for="snaming">' . get_string('snamingConvetionInstruction','block_cmanager') . '</label><br>
            <select id="snaming" name="snaming">
                <option value="1"' . ($snaming == 1 ? ' selected' : '') . '>' . get_string('snamingConvetion_option1','block_cmanager').'</option>
                <option value="2"' . ($snaming == 2 ? ' selected' : '') . '>' . get_string('snamingConvetion_option2','block_cmanager') . '</option>
            </select></br>
        ';

        // Enrolment Key.
        $fragment2 .='
            <h2 class="mt-3">' . get_string('configure_EnrolmentKey','block_cmanager') . '</h2>
            <p>' . get_string('cmanagerEnrolmentInstruction','block_cmanager') . '</p>
            <select name="key">
                <option value="0"'. ($autoKey == 0 ? ' selected' : '') . '>' .get_string('cmanagerEnrolmentOption2','block_cmanager').'</option>
                <option value="1"'. ($autoKey == 1 ? ' selected' : '') . '>' .get_string('cmanagerEnrolmentOption1','block_cmanager').'</option>
                <option value="2"'. ($autoKey == 2 ? ' selected' : '') . '>' .get_string('cmanagerEnrolmentOption3','block_cmanager').'</option>
            </select>';

        $fragment2 .= '<hr>';

        // Clear History.
        $fragment2 .='
            <h2 class="mt-3">' . get_string('clearHistoryTitle','block_cmanager') . '</h2>
            <input class="btn btn-default" type="button" onClick="deleteAll()" value="'.get_string('deleteAllRequests', 'block_cmanager').'">
            <input class="btn btn-default" type="button" onClick="deleteArchOnly()" value="'.get_string('deleteOnlyArch', 'block_cmanager').'">
        ';

        // Allow User to Select Category.
        $fragment2 .= '
            <h2 class="mt-3">' . get_string('allowSelfCategorization','block_cmanager') . '</h2>
            <p>' . get_string('allowSelfCategorization_desc', 'block_cmanager') . '</p>
            <select name="selfcat">
                <option value="yes"' . ($selfcat == 'yes' ? ' selected' : '') . '>' . get_string('selfCatOn', 'block_cmanager') . '</option>
                <option value="no"' . ($selfcat == 'no' ? ' selected' : '') . '>' . get_string('selfCatOff', 'block_cmanager') . '</option>
            </select>
        ';

        // Communications Email Address.
        $fragment2 .='
            <h2 class="mt-3">' . get_string('email_noReply','block_cmanager') . '</h2>
            <p>' . get_string('email_noReplyInstructions','block_cmanager') . '</p>
            <label for="defaultemail">' . get_string('config_addemail','block_cmanager') . '</label>
            <input type="text" name="defaultmail"  size="50" id="defaultemail" value="'.$emailSender.'">
        ';

        // Denial Text Templates.
        $fragment2 .='
            <h2 class="mt-3">' . get_string('customdeny','block_cmanager') . '</h2>
            <p>' . get_string('customdenydesc','block_cmanager').'</p>
            <label for="denytext1">' . get_string('denytext1','block_cmanager') . '</label><br>
            <textarea id="denytext1" name="denytext1" rows="10" cols="80" maxlength="250">'.$denytext1.'</textarea><br>
            <label for="denytext2">' . get_string('denytext2','block_cmanager') . '</label><br>
            <td><textarea id="denytext2" name="denytext2" rows="10"cols="80" maxlength="250">'.$denytext2.'</textarea><br>
            <label for="denytext3">' . get_string('denytext3','block_cmanager') . '</label><br>
            <td><textarea id="denytext3" name="denytext3" rows="10"cols="80" maxlength="250">'.$denytext3.'</textarea><br>
            <label for="denytext4">' . get_string('denytext4','block_cmanager') . '</label><br>
            <td><textarea id="denytext4" name="denytext4" rows="10"cols="80" maxlength="250">'.$denytext4.'</textarea><br>
            <label for="denytext5">' . get_string('denytext5','block_cmanager') . '</label><br>
            <td><textarea id="denytext5" name="denytext5" rows="10"cols="80" maxlength="250">'.$denytext5.'</textarea><br>
        ';
        $fragment2 .= '<hr>';

        // Start Date.
        $fragment2 .='
            <h2>' . get_string('configure_defaultStartDate','block_cmanager') . '</h2>
            <p>' . get_string('configure_defaultStartDateInstructions','block_cmanager') . '</p>
        ';

        // Add the main form.
        $mform->addElement('html', $fragment2);

        $timestamp_startdate = $DB->get_field_select('block_cmanager_config', 'value', "varname = 'startdate'");
        // Convert to date.
        $startdate = getdate($timestamp_startdate);
        // Add the date selector and set defaults.
        $date_options = array('format' => 'dMY', 'minYear' => 2012, 'maxYear' => date("Y") + 5);

        $mform->addElement('date', 'course_date', get_string('date'), $date_options);
        $date_defaults = array('d' => $startdate['mday'], 'M' => $startdate['mon'], 'Y' => $startdate['year']);
        $mform->setDefaults(array('course_date' => $date_defaults));

        $saveall = '
                <hr>
                <input class="btn btn-primary" type="submit" value="' . get_string('SaveAll','block_cmanager') . '">
            </form>
        ';

        // Close off the html and form.
        $mform->addElement('html', $saveall);

        /////////////////////////////////////////////////////////////////////////////////////////////////////////
    }
}

$mform = new block_cmanager_adminsettings_form();

if ($mform->is_cancelled()) {
    header("Location: ../cmanager_admin.php");
    exit();
} else if (isset($_POST['addemailbutton'])) {
    global $USER;
    global $CFG;

    // Add an email address
    $post_email = required_param('newemail', 'PARAM_EMAIL');
    if (filter_var($post_email, FILTER_VALIDATE_EMAIL)) {
        $newrec = new stdClass();
        $newrec->varname = 'admin_email';
        $newrec->value = $post_email;
        $DB->insert_record('block_cmanager_config', $newrec);
    }

    header("Location: cmanager_othersettings.php");
    exit();

} else {
    $mform->focus();
    $mform->set_data($mform);
    $mform->display();
    if (!empty($naming)) {
        echo '<script> document.getElementById("naming").value = '.$naming.'; </script> ';
    }
    ?>
    <script>
        function cancelConfirm(i,langString) {
            var answer = confirm(langString)
            if (answer){
                window.location = "cmanager_othersettings.php?t=d&&id=" + i;
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
                        // alert("Changes have been saved!");
                    }
            );
        }

        function deleteAll(){
            window.location="history/delete.php?delete=all";
        }
        function deleteArchOnly(){
            window.location="history/delete.php?delete=archonly";
        }
    </script>
    <?php
    echo $OUTPUT->footer();
}
