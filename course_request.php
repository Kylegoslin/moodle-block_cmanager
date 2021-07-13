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
 * @copyright  2014-2018 Kyle Goslin, Daniel McSweeney
 * @copyright  2021 Michael Milette (TNG Consulting Inc.), Daniel Keaman
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once("../../config.php");

global $CFG, $USER, $DB;
require_once("$CFG->libdir/formslib.php");
require_once('../../course/lib.php');

if ($CFG->branch < 36) {
    require_once($CFG->libdir.'/coursecatlib.php');
}

require_login();

$currentmode = optional_param('mode', '', PARAM_INT);
if ($currentmode == 1) { // Make a new request
    $pagetitle = 'block_request';
} else if ($currentmode == 2) { // editing mode
    $pagetitle = 'requestReview_AlterRequest';
} else {
    $pagetitle = 'modrequestfacility';
}

/** Navigation Bar **/
$PAGE->navbar->ignore_active();
$PAGE->navbar->add(get_string('cmanagerDisplay', 'block_cmanager'), new moodle_url('/blocks/cmanager/module_manager.php'));
$PAGE->navbar->add(get_string($pagetitle, 'block_cmanager'));
$PAGE->set_url('/blocks/cmanager/course_request.php');
$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_heading(get_string($pagetitle, 'block_cmanager'));
$PAGE->set_title(get_string($pagetitle, 'block_cmanager'));
echo $OUTPUT->header();





/** Main variable for storing the current session id. **/
$currentsess = '00';


if ($currentmode == 1) { // Make a new request




    if (has_capability('block/cmanager:addrecord',$context)) {
        $_SESSION['cmanager_addedmods'] = '';
        $_SESSION['editingmode'] = 'false';

        $newrec = new stdClass();
        $newrec->modname = '';
        $newrec->createdbyid = $USER->id;
        $newrec->createdate = date("d/m/y H:i:s");
        $newrec->formid = $DB->get_field('block_cmanager_config', 'value', array('varname'=>'current_active_form_id'));

        $currentsess = $DB->insert_record('block_cmanager_records', $newrec, true);
        $_SESSION['cmanager_session'] = $currentsess;

    } else {
        print_error(get_string('cannotrequestcourse', 'block_cmanager'));
    }

}
else if ($currentmode == 2) { // editing mode


    if (has_capability('block/cmanager:editrecord',$context)) {
        $_SESSION['editingmode'] = 'true';
        $currentsess = optional_param('edit', '0', PARAM_INT);
        $_SESSION['cmanager_session'] = $currentsess;
        $_SESSION['cmanagermode'] = 'admin';
    } else {
        print_error(get_string('cannoteditrequest', 'block_cmanager'));
    }

} else { // if a session has already been started
    $currentsess = $_SESSION['cmanager_session'];
}

$currentrecord =  $DB->get_record('block_cmanager_records', array('id'=>$currentsess), '*', IGNORE_MULTIPLE);

// Stop guests from making requests!
if (isguestuser()) {
    echo error(get_string('guestsarenotallowed', 'error'));
    die;
}



/**
 * Course request form
 *
 * Main course request form
 * @package    block_cmanager
 * @copyright  2014-2018 Kyle Goslin, Daniel McSweeney
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_cmanager_courserequest_form extends moodleform {

    function definition() {

        global $CFG;
        global $currentsess, $DB, $currentrecord;

        $mform =& $this->_form; // Don't forget the underscore!

        $field1desc = $DB->get_field('block_cmanager_config', 'value', array('varname'=>'page1_fielddesc1'), IGNORE_MULTIPLE);
        $field2desc = $DB->get_field('block_cmanager_config', 'value', array('varname'=>'page1_fielddesc2'), IGNORE_MULTIPLE);

        // Get the field values.
        $field1title = $DB->get_field('block_cmanager_config', 'value', array('varname'=>'page1_fieldname1'), IGNORE_MULTIPLE);
        $field2title = $DB->get_field('block_cmanager_config', 'value', array('varname'=>'page1_fieldname2'), IGNORE_MULTIPLE);
        $field3desc = $DB->get_field('block_cmanager_config', 'value', array('varname'=>'page1_fielddesc3'), IGNORE_MULTIPLE);
        $field4title = $DB->get_field('block_cmanager_config', 'value', array('varname'=>'page1_fieldname4'), IGNORE_MULTIPLE);
        $field4desc = $DB->get_field('block_cmanager_config', 'value', array('varname'=>'page1_fielddesc4'), IGNORE_MULTIPLE);
        // Get field 3 status.
        $field3status = $DB->get_field('block_cmanager_config', 'value', array('varname'=>'page1_field3status'), IGNORE_MULTIPLE);

        // Get the value for autokey - the config variable that determines enrolment key auto or prompt
        $autoKey = $DB->get_field_select('block_cmanager_config', 'value', "varname = 'autoKey'");

        // Get the value for category selection - the config variable that determines if user can choose a category.
        $selfcat = $DB->get_field_select('block_cmanager_config', 'value', "varname = 'selfcat'");

        // Page description text
        $mform->addElement('html', '<p>'.get_string('courserequestline1','block_cmanager').'</p>');
        $mform->addElement('html', '<h2>' . get_string('step1text','block_cmanager'). '</h2>');

        // Course shortname.
        $attributes = [];
        $attributes['value'] = $currentrecord->modcode;
        $mform->addElement('text', 'programmecode', format_string($field1title), $attributes, '');
        $mform->addHelpButton('programmecode', 'shortnamecourse');
        $mform->addRule('programmecode', get_string('request_rule1','block_cmanager'), 'required', null, 'server', false, false);
        $mform->setType('programmecode', PARAM_TEXT);
        $mform->addElement('static', 'description', '', format_string($field1desc));

        // Course fullname.
        $attributes = [];
        $attributes['value'] = $currentrecord->modname;
        $mform->addElement('text', 'programmetitle', format_string($field2title), $attributes);
        $mform->addHelpButton('programmetitle', 'fullnamecourse');
        $mform->addRule('programmetitle', get_string('request_rule1','block_cmanager'), 'required', null, 'server', false, false);
        $mform->setType('programmetitle', PARAM_TEXT);
        $mform->addElement('static', 'description', '', format_string($field2desc));

        // Optional Dropdown
        if ($field3status == 'enabled') {
            $options = [];
            $selectQuery = "varname = 'page1_field3value'";
            $field3Items = $DB->get_recordset_select('block_cmanager_config', $select=$selectQuery);

            foreach ($field3Items as $item) {
                $value = $item->value;
                if ($value != '') {
                    $options[$value] = format_string($value);
                }
            }

            $mform->addElement('select', 'programmemode', format_string($field3desc) , $options);
            $mform->addRule('programmemode', get_string('request_rule2','block_cmanager'), 'required', null, 'server', false, false);
            $mform->setDefault('programmemode', $currentrecord->modmode);
            $mform->setType('programmemode', PARAM_TEXT);
        }

        // If enabled, give the user the option to select a category location for the course.
        if ($selfcat == 'yes') {
            //  $movetocategories = array();
            if ($CFG->branch > 35) {
                $options = core_course_category::make_categories_list();
            } else {
                $options = coursecat::make_categories_list();
            }
            $mform->addElement('select', 'menucategory', get_string('requestForm_category', 'block_cmanager'), $options);
            $mform->addHelpButton('menucategory', 'coursecategory');

            if ($_SESSION['editingmode'] == 'true') {
                $mform->setDefault('menucategory', $currentrecord->cate);
            } else {
                $mform->setDefault('menucategory', $CFG->defaultrequestcategory);
            }
        }

        // Enrolment key.
        if (!$autoKey) {
            $attributes = [];
            $attributes['value'] = $currentrecord->modkey;
            $mform->addElement('text', 'enrolkey', $field4title, $attributes);
            $mform->addRule('enrolkey', get_string('request_rule3','block_cmanager'), 'required', null, 'server', false, false);
            $mform->setType('enrolkey', PARAM_TEXT);
        }

        // Hidden form element to pass the key.
        if (isset($_GET['edit'])) {

            $mform->addElement('hidden', 'editingmode', $currentsess);
            $mform->setType('editingmode', PARAM_TEXT);
        }

        // Course Summary.
        if (false) { // This functionality has not yet been implemented.
            $mform->addElement('editor', 'summary_editor', get_string('summary'), null, course_request::summary_editor_options());
            $mform->addHelpButton('summary_editor', 'coursesummary');
            $mform->setType('summary_editor', PARAM_RAW);
        }

        // Submit / Cancel buttons.
        $buttonarray=array();
        $buttonarray[] = &$mform->createElement('submit', 'submitbutton', get_string('Continue','block_cmanager'));
        $buttonarray[] = &$mform->createElement('cancel', 'cancel', get_string('cancel','block_cmanager'));
        $mform->addGroup($buttonarray, 'buttonar', '', array(' '), false);

    }
}


$mform = new block_cmanager_courserequest_form();//name of the form you defined in file above.

if ($mform->is_cancelled()) {
    echo '<script>window.location="module_manager.php";</script>';
    die;
} else if ($fromform=$mform->get_data()) {

    global $USER;
    global $COURSE;
    global $CFG;

    $newrec = new stdClass();
    $newrec->id = $currentsess;

    $newrec->modname = $fromform->programmetitle;
    $newrec->modcode = $fromform->programmecode;

    if (!empty($fromform->menucategory)) {
        $newrec->cate = $fromform->menucategory;
    }

    if (!empty($fromform->enrolkey)) {
        $newrec->modkey = $fromform->enrolkey;
    }

    if (!empty($fromform->programmemode)) {
        $newrec->modmode = $fromform->programmemode;
    }

    $DB->update_record('block_cmanager_records', $newrec);


    $postcode = $fromform->programmecode;

    $postmode = '';
    if (!empty($fromform->programmemode)) {
      $postmode = $fromform->programmemode;
    }
    // Find which records are similar to the one which we are currently looking for.
    $spacecheck =  substr($postcode, 0, 4) . ' ' . substr($postcode, 4, strlen($postcode));

    if (strpos($spacecheck, '?') !== false) {
        $spacecheck = str_replace('?', '', $spacecheck);
    }

    if (strpos($postmode, '?') !== false) {
        $postmode = str_replace('?', '', $postmode);
    }

    if (strpos($postcode, '?') !== false) {
        $postcode = str_replace('?', '', $postcode);
    }


    // If we are in editing mode move to editing

    $editingmode = $_SESSION['editingmode'];

    if ($editingmode == 'true'){
        $editsessid = $_SESSION['cmanager_session'];
        echo "<script>window.location='course_new.php?mode=2&edit=$editsessid';</script>";
        die;
    }




    // If we are not in editing mode, continue search or creation
    $selectquery = "shortname LIKE '%".addslashes($postcode)."%'
                    OR (shortname LIKE '%".addslashes($spacecheck)."%'
                    AND shortname LIKE '%".addslashes($postmode)."%')
                    OR shortname LIKE '%".addslashes($spacecheck)."%' ";


    $recordsexist = $DB->record_exists_select('course', $selectquery);
    if ($recordsexist) {
        echo "<script>window.location='course_exists.php';</script>";
        die;
    } else{
        echo "<script>window.location='course_new.php';</script>";
        die;
    }
}


$mform->focus();
$mform->set_data($mform);
$mform->display();


if (!empty($currentrecord->cate)) {
    echo '<script> document.getElementById("menucategory").value = '.$currentrecord->cate.'; </script> ';
}


echo $OUTPUT->footer();
