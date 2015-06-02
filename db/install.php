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

function xmldb_block_cmanager_install() {
global $CFG, $DB;

echo '<center><h2>Course Manager Configuration Builder</h2>';

echo 'Building Config Variables...';



$newrec = new stdClass();
$newrec->varname = 'selfcat';
$newrec->value = 'no';
$DB->insert_record('block_cmanager_config', $newrec, false);


$newrec = new stdClass();
$newrec->varname = 'autoKey';
$newrec->value = '1';
$DB->insert_record('block_cmanager_config', $newrec, false);

$newrec = new stdClass();
$newrec->varname = 'naming';
$newrec->value = '1';
$DB->insert_record('block_cmanager_config', $newrec, false);

$newrec = new stdClass();
$newrec->varname = 'snaming';
$newrec->value = '1';
$DB->insert_record('block_cmanager_config', $newrec, false);

$newrec = new stdClass();
$newrec->varname = 'startdate';
$newrec->value = time(array('d' => date('d'), 'M' => date('n'), 'Y' => date('Y')));
$DB->insert_record('block_cmanager_config', $newrec, false);

$newrec = new stdClass();
$newrec->varname = 'emailSender';
$newrec->value = 'NOREPLY@moodle';
$DB->insert_record('block_cmanager_config', $newrec, false);

$newrec = new stdClass();
$newrec->varname = 'admin_email';
$newrec->value = 'youremail@domain.com';
$DB->insert_record('block_cmanager_config', $newrec, false);


$newrec = new stdClass();
$newrec->varname = 'approved_text';
$newrec->value = '';
$DB->insert_record('block_cmanager_config', $newrec, false);

$newrec = new stdClass();
$newrec->varname = 'approvedadminemail';
$newrec->value = 'Approved Request Confirmation

Course code: [course_code]
Course name: [course_name]
Enrolment key: [e_key]
Link to course: [full_link]
Request link:  [req_link]';
$DB->insert_record('block_cmanager_config', $newrec, false);

$newrec = new stdClass();
$newrec->varname = 'approveduseremail';
$newrec->value = 'Your moodle course request has been approved. The details of your new course are shown below

Course code: [course_code]
Course name: [course_name]

Enrolment key: [e_key]
Link to course: [full_link]

Your original request can be viewed at the following link
[req_link]

_________________
Moodle Administrator

Note: This is a server generated e-mail. Please do not reply to this mail.';
$DB->insert_record('block_cmanager_config', $newrec, false);


$newrec = new stdClass();
$newrec->varname = 'requestnewmoduleuser';
$newrec->value = 'Your moodle course request has been logged for approval. The details of the request are shown below:

Course code: [course_code]
Course name: [course_name]

The full request details can be viewed at the following link
Request link:  [req_link]

_________________
Moodle Administrator

Note: This is a server generated e-mail. Please do not reply to this mail.';
$DB->insert_record('block_cmanager_config', $newrec, false);


$newrec = new stdClass();
$newrec->varname = 'requestnewmoduleadmin';
$newrec->value = 'A new moodle course request has been logged on course manager.

Details are

Course code: [course_code]
Course name: [course_name]

The full request details can be viewed at the following link
Request link:  [req_link]

_________________
Moodle Administrator

Note: This is a server generated e-mail. Please do not reply to this mail.';
$DB->insert_record('block_cmanager_config', $newrec, false);


$newrec = new stdClass();
$newrec->varname = 'commentemailadmin';
$newrec->value = 'A new comment has been added to a request

The full request details can be viewed at the following link
Request link:  [req_link]

_________________
Moodle Administrator

Note: This is a server generated e-mail. Please do not reply to this mail.';
$DB->insert_record('block_cmanager_config', $newrec, false);

$newrec = new stdClass();
$newrec->varname = 'commentemailuser';
$newrec->value = 'A new comment has been added to your request for a course setup on moodle.

The comment and full request details can be viewed at the following link
Request link:  [req_link]

_________________
Moodle Administrator

Note: This is a server generated e-mail. Please do not reply to this mail.';
$DB->insert_record('block_cmanager_config', $newrec, false);

$newrec = new stdClass();
$newrec->varname = 'modulerequestdeniedadmin';
$newrec->value = 'The following course request has been denied

Course code: [course_code]
Course name: [course_name]
Request link:  [req_link]

_________________
Moodle Administrator

Note: This is a server generated e-mail. Please do not reply to this mail.';
$DB->insert_record('block_cmanager_config', $newrec, false);

$newrec = new stdClass();
$newrec->varname = 'modulerequestdenieduser';
$newrec->value = 'Your request for a moodle course setup has been denied. This may have been due

1. to a clash with an existing course
2. a duplicate request
3. insufficient details for the request.

Course code: [course_code]
Course name: [course_name]

The original link and comments from the moodle administrator can be viewed at the following link
Request link:  [req_link]

_________________
Moodle Administrator

Note: This is a server generated e-mail. Please do not reply to this mail.';
$DB->insert_record('block_cmanager_config', $newrec, false);

$newrec = new stdClass();
$newrec->varname = 'handovercurrent';
$newrec->value = 'A handover request has been made for one of your courses on moodle.
This request may be a request for access to your course or transfer to another member of academic staff.

To view the request, please visit the following link.

Request link:  [req_link]

_________________
Moodle Administrator

Note: This is a server generated e-mail. Please do not reply to this mail.';
$DB->insert_record('block_cmanager_config', $newrec, false);


$newrec = new stdClass();
$newrec->varname = 'handoveruser';
$newrec->value = 'Your handover request has been sent to the owner of the current moodle course.
Please communicate with the owner for access to the moodle course.

To view the request, please visit the following link.

Request link:  [req_link]

In the event that the handover cannot be facilitated, please contact your moodle administrator.

_________________
Moodle Administrator

Note: This is a server generated e-mail. Please do not reply to this mail.';
$DB->insert_record('block_cmanager_config', $newrec, false);

$newrec = new stdClass();
$newrec->varname = 'handoveradmin';
$newrec->value = 'A handover request has been submitted to course manager.
To view the request, please visit the following link.

Request link:  [req_link]

_________________
Moodle Administrator

Note: This is a server generated e-mail. Please do not reply to this mail.';
$DB->insert_record('block_cmanager_config', $newrec, false);

$newrec = new stdClass();
$newrec->varname = 'page1_fieldname1';
$newrec->value = 'Short Name';
$DB->insert_record('block_cmanager_config', $newrec, false);

$newrec = new stdClass();
$newrec->varname = 'page1_fielddesc1';
$newrec->value = 'A shorthand way of referring to the course';
$DB->insert_record('block_cmanager_config', $newrec, false);

$newrec = new stdClass();
$newrec->varname = 'page1_fieldname2';
$newrec->value = 'Full Name';
$DB->insert_record('block_cmanager_config', $newrec, false);


$newrec = new stdClass();
$newrec->varname = 'page1_fielddesc2';
$newrec->value = 'The full name of the course is displayed at the top of the screen and in the course listings.';
$DB->insert_record('block_cmanager_config', $newrec, false);


$newrec = new stdClass();
$newrec->varname = 'page1_field3status';
$newrec->value = 'disabled';
$DB->insert_record('block_cmanager_config', $newrec, false);


$newrec = new stdClass();
$newrec->varname = 'page1_field3value';
$newrec->value = 'Full Time';
$DB->insert_record('block_cmanager_config', $newrec, false);


$newrec = new stdClass();
$newrec->varname = 'page1_field3value';
$newrec->value = 'Part Time';
$DB->insert_record('block_cmanager_config', $newrec, false);


$newrec = new stdClass();
$newrec->varname = 'page1_fielddesc3';
$newrec->value = 'Mode';
$DB->insert_record('block_cmanager_config', $newrec, false);

$newrec = new stdClass();
$newrec->varname = 'page1_fieldname4';
$newrec->value = 'Enrolment Key';
$DB->insert_record('block_cmanager_config', $newrec, false);

$newrec = new stdClass();
$newrec->varname = 'page1_fielddesc4';
$newrec->value = 'Students who are trying to get in for the FIRST TIME ONLY will be asked to supply this word or phrase.';
$DB->insert_record('block_cmanager_config', $newrec, false);



// Forms
$newrec = new stdClass();
$newrec->varname = 'page2form';
$newrec->value = 'Default Form';
$DB->insert_record('block_cmanager_config', $newrec, false);

//$activeFormId = get_field_select('block_cmanager_config', 'id', "varname = 'page2form'");
$activeFormId = $DB->get_field('block_cmanager_config', 'id', array('varname'=>'page2form'));



$newrec = new stdClass();
$newrec->varname = 'current_active_form_id';
$newrec->value = $activeFormId;
$DB->insert_record('block_cmanager_config', $newrec, false);


// DENY TEXT FIELD RECORDS
$newrec = new stdClass();
$newrec->varname = 'denytext1';
$newrec->value = 'You may enter a denial reason here.';
$DB->insert_record('block_cmanager_config', $newrec, false);

$newrec = new stdClass();
$newrec->varname = 'denytext2';
$newrec->value = 'You may enter a denial reason here.';
$DB->insert_record('block_cmanager_config', $newrec, false);

$newrec = new stdClass();
$newrec->varname = 'denytext3';
$newrec->value = 'You may enter a denial reason here.';
$DB->insert_record('block_cmanager_config', $newrec, false);

$newrec = new stdClass();
$newrec->varname = 'denytext4';
$newrec->value = 'You may enter a denial reason here.';
$DB->insert_record('block_cmanager_config', $newrec, false);

$newrec = new stdClass();
$newrec->varname = 'denytext5';
$newrec->value = 'You may enter a denial reason here.';
$DB->insert_record('block_cmanager_config', $newrec, false);


$newrec = new stdClass();
$newrec->type = 'textarea';
$newrec->lefttext = 'Other Information';
$newrec->position = 1;
$newrec->reqfield = 1;
$newrec->formid = $activeFormId;
$DB->insert_record('block_cmanager_formfields', $newrec, false);






echo '<p></p>';
echo 'All config variables have been created';
echo '<p></p>';
echo '<b>Thats it! Your Course manager is now ready to use!</b> <p></p>&nbsp <p></p> <a href="../../../">Return to your Moodle</a>';


echo '</center>';

}
?>