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
 * @copyright  2018 Kyle Goslin, Daniel McSweeney
 * @copyright  2021 Michael Milette (TNG Consulting Inc.), Daniel Keaman
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once("../../../config.php");
global $CFG, $DB;


require_login();

$context = context_system::instance();
if (has_capability('block/cmanager:viewconfig',$context)) {
} else {
  print_error(get_string('cannotviewrecords', 'block_cmanager'));
}

require_once('../validate_admin.php');

$formPath = "$CFG->libdir/formslib.php";
require_once($formPath);

$PAGE->navbar->ignore_active();
$PAGE->navbar->add(get_string('cmanagerDisplay', 'block_cmanager'), new moodle_url('/blocks/cmanager/cmanager_admin.php'));
$PAGE->navbar->add(get_string('configurecoursemanagersettings', 'block_cmanager'), new moodle_url('/blocks/cmanager/cmanager_confighome.php'));
$PAGE->navbar->add(get_string('formpage2builder', 'block_cmanager'), new moodle_url('/blocks/cmanager/formeditor/form_builder.php'));
$PAGE->navbar->add(get_string('previewform', 'block_cmanager'));
$mid = optional_param('id', '', PARAM_INT);
$PAGE->set_url('/blocks/cmanager/formeditor/preview.php', ['id' => $mid]);
$PAGE->set_context(context_system::instance());
$PAGE->set_heading(get_string('formBuilder_previewHeader', 'block_cmanager'));
$PAGE->set_title(get_string('formBuilder_previewHeader', 'block_cmanager'));
echo $OUTPUT->header();


if(!empty($mid)){
	$formId = $mid;
} else {
	echo 'Error: No ID specified.';
	die;
}

?>
<script>
function goBack(){
	window.location ="form_builder.php";
}
</script>
<?php
/**
 * cmanager new course form
 *
 * Preview form
 * @package    block_cmanager
 * @copyright  2018 Kyle Goslin, Daniel McSweeney
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_cmanager_preview_form extends moodleform {

    function definition() {
        global $CFG;
        global $USER, $DB;



        $mform =& $this->_form; // Don't forget the underscore!


        $fieldnameCounter = 1; // This counter is used to increment the naming conventions of each field.

		// Back Button

		$mform->addElement('html', '<p><a class="btn btn-default" href="form_builder.php"><img src="../icons/back.png"/> '.get_string('back','block_cmanager').'</a></p>');


		// Page description text
		$mform->addElement('html', '<p>'.get_string('formBuilder_previewInstructions1','block_cmanager').'</p><p>'.get_string('formBuilder_previewInstructions2','block_cmanager').'</p>');

		$mform->addElement('html', '<h2>' . get_string('formBuilder_step2', 'block_cmanager') . '</h2>');



	    global $formId;

	   	$selectQuery = "";
		//$formFields = $DB->get_records('block_cmanager_formfields', 'formid', $formId, $sort='position ASC', $fields='*', $limitfrom='', $limitnum='');
		$formFields = $DB->get_records('block_cmanager_formfields', array('formid'=>$formId), 'position ASC');

		foreach($formFields as $field){


			  $fieldName = 'f' . $fieldnameCounter; // Give each field an incremented fieldname.

			   if($field->type == 'textfield'){
			       block_cmanager_create_textfield(format_string($field->lefttext), $mform, $fieldName, $field->reqfield);
			   }
			   else if($field->type == 'textarea'){
			  	   block_cmanager_create_textarea(format_string($field->lefttext), $mform, $fieldName, $field->reqfield);
			   }
			   else if($field->type == 'dropdown'){
			       block_cmanager_create_dropdown(format_string($field->lefttext), $field->id, $mform, $fieldName, $field->reqfield);
			   }

			   else if($field->type == 'radio'){
			       block_cmanager_create_radio(format_string($field->lefttext), $field->id, $mform, $fieldName, $field->reqfield);
			   }


			   $fieldnameCounter++;
		}



	}
}






/**
* Create a text field
*/
function block_cmanager_create_textfield($leftText, $form, $fieldName, $reqfield) {

	$form->addElement('text', $fieldName, $leftText, '');
    $form->setType($fieldName, PARAM_TEXT);
	if ($reqfield == 1) {
		$form->addRule($fieldName, '', 'required', null, 'server', false, false);
	}
}

/**
* Create text area
*/
function block_cmanager_create_textarea($leftText, $form, $fieldName, $reqfield) {


	$form->addElement('textarea', $fieldName, $leftText, 'wrap="virtual" rows="5" cols="60"');
	  $form->setType($fieldName, PARAM_TEXT);
	if($reqfield == 1){
		$form->addRule($fieldName, '', 'required', null, 'server', false, false);
	}

}

/**
* Create a radio button
*/
function block_cmanager_create_radio($leftText, $id, $form, $fieldName, $reqfield) {
	global $DB;

    $form->setType($fieldName, PARAM_TEXT);
	$selectQuery = "fieldid = '$id'";
	$field3Items = $DB->get_recordset_select('block_cmanager_form_data', $select=$selectQuery);

    $attributes = '';
    $radioarray = [];
    foreach ($field3Items as $item) {
        $radioarray[] = $form->createElement('radio', $fieldName, '', $item->value,  $item->value, $attributes);
    }
    $form->addGroup($radioarray, $fieldName, $leftText, [(count($radioarray) > 1 ? '<br>' : '')], false);
    if($reqfield == 1){
        $form->addRule($fieldName, '', 'required', null, 'server', false, false);
    }
}


/**
 * Create a Moodle form dropdown menu
 *
 *
 */
function block_cmanager_create_dropdown($leftText, $id, $form, $fieldName, $reqfield){

	global $DB;

	$options = array();
	   $form->setType($fieldName, PARAM_TEXT);
	$selectQuery = "fieldid = '$id'";

	$field3Items = $DB->get_recordset_select('block_cmanager_form_data', $select=$selectQuery);



		  foreach($field3Items as $item){
		  	         $value = $item->value;
					 if($value != ''){
						$options[$value] = format_string($value);
						// $options[$value] = $value;
					}
		  }

		$form->addElement('select', $fieldName, $leftText , $options);
		if($reqfield == 1){
			$form->addRule($fieldName, get_string('preview_modmode', 'block_cmanager'), 'required', null, 'server', false, false);
		}

}

?>


<?php
$mform = new block_cmanager_preview_form();

if ($mform->is_cancelled()) {


} else if ($fromform=$mform->get_data()) {




} else {


}



$mform->focus();
$mform->display();
echo $OUTPUT->footer();
