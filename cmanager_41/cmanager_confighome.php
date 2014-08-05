<?php
/* --------------------------------------------------------- 
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
 --------------------------------------------------------- */

require_once("../../config.php");
require_once("$CFG->libdir/formslib.php");

require_login();
require_once('validate_admin.php');

/** Navigation Bar **/
$PAGE->navbar->ignore_active();
$PAGE->navbar->add(get_string('cmanagerDisplay', 'block_cmanager'), new moodle_url('/blocks/cmanager/cmanager_admin.php'));
$PAGE->navbar->add(get_string('configurecoursemanagersettings', 'block_cmanager'));

$PAGE->set_url('/blocks/cmanager/block_cmanager_confighome.php');
$PAGE->set_context(get_system_context());
$PAGE->set_heading(get_string('pluginname', 'block_cmanager'));
$PAGE->set_title(get_string('pluginname', 'block_cmanager'));
echo $OUTPUT->header();

class courserequest_form extends moodleform {

	function definition() {

		$mform =& $this->_form; // Don't forget the underscore!
		$mform->addElement('header', 'mainheader', '<span style="font-size:18px">'. get_string('configurecoursemanagersettings','block_cmanager'). '</span>');

		$mainSlider = "
		<p></p>
		<table style=\"width:100%; \">
		<tr>
		<td style=\"padding:25px; width:30px\"><img src=\"icons/config/admin.png\"></td>
		<td><b><a href=\"cmanager_adminsettings.php\">".get_string('configureadminsettings','block_cmanager')."</a></b><br>".get_string('configureadminsettings_desc','block_cmanager')."</td>
	</tr>

	<tr>
		<td style=\"padding:25px; width:30px\"><img src=\"icons/config/email.png\"></td>

		<td><b><a href=\"cmanager_config.php\">".get_string('configureemailsettings','block_cmanager')."</a></b><br>".get_string('configureemailsettings_desc','block_cmanager')."</td>

	</tr>

	<tr>

		<td style=\"padding:25px; width:30px\"><img src=\"icons/config/config.png\"> </td>
		<td><b><a href=\"formeditor/page1.php\">".get_string('configurecourseformfields','block_cmanager')."</a></b><br>".get_string('configure_instruction2','block_cmanager')."</td>
	</tr>

	<tr>
		<td style=\"padding:25px; width:30px\"><img src=\"icons/config/config.png\"></td>
		<td><b><a href=\"formeditor/form_builder.php\">".get_string('informationform','block_cmanager')."</a></b><br>".get_string('configure_instruction3','block_cmanager')."
		</td>

	</tr>

		</table>";

		$mform->addElement('html', $mainSlider);

    } // Close the function
}  // Close the class






$mform = new courserequest_form();

if ($mform->is_cancelled()){

}

else if ($fromform=$mform->get_data()){

}

else {

	$mform->focus();
	$mform->display();
	echo $OUTPUT->footer();

}

?>
