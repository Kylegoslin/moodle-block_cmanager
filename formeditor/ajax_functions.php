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
global $CFG, $DB;
require_login();
require_once('../validate_admin.php');

$context = context_system::instance();
if (has_capability('block/cmanager:approverecord',$context)) {
} else {
  print_error(get_string('cannotviewconfig', 'block_cmanager'));
}

// check the type of ajax call
// that has been made to this page and redirect
// to that function.
$type = required_param('type', PARAM_TEXT);

if($type == 'add'){
	block_cmanager_add_new_item();
}
else if($type == 'save'){
	block_cmanager_save_changes();
}
else if($type == 'page2addfield'){
	block_cmanager_add_field();
}
else if($type == 'updatefield'){
	block_cmanager_update_field();
}
else if($type == 'addvaluetodropdown'){
	block_cmanager_add_value_to_dropdown();
}
else if($type == 'getdropdownvalues'){
	block_cmanager_get_dropdown_values();
}
else if($type == 'addnewform'){
	block_cmanager_add_new_form();
}
else if($type == 'saveselectedform'){
	block_cmanager_save_selected_form();
}
else if($type == 'saveoptionalvalue'){
	block_cmanager_save_optional_value();
}


/** Save a selected form */
function block_cmanager_save_selected_form(){

	global $DB;
	//echo 'saving form';

	$value =  required_param('value', PARAM_TEXT);
	$rowId = $DB->get_field_select('block_cmanager_config', 'id', "varname = 'current_active_form_id'");

	$dataobject->id = $rowId;
	$dataobject->value = addslashes($value);
	$DB->update_record('block_cmanager_config', $dataobject);



}

/** Add a new form */
function block_cmanager_add_new_form(){

	global $DB;

	$formName = required_param('value', PARAM_TEXT);


	$object->id = '';
	$object->varname = 'page2form';
	$object->value = $formName;


	$id = $DB->insert_record('block_cmanager_config', $object, true);
}

/**
*
* Add a value to a dropdown menu
*/
function block_cmanager_add_value_to_dropdown(){

	global $DB;

	$id = required_param('id', PARAM_INT);
	$value = required_param('value', PARAM_TEXT);

	$object->id = '';
	$object->fieldid = $id;
	$object->value = $value;


	$id = $DB->insert_record('block_cmanager_form_data', $object, true);


}

/**
* Update a field
*/
function block_cmanager_update_field(){

	global $CFG, $DB;
	echo $elementId = required_param('id', PARAM_INT);
	echo $value = required_param('value', PARAM_TEXT);

	$dataobject->id = $elementId;
	$dataobject->lefttext = addslashes($value);
	$DB->update_record('block_cmanager_formfields', $dataobject);



}

/**
* Add a new field
*/
function block_cmanager_add_field(){

    global $CFG, $DB;


   	$fieldType = required_param('fieldtype', PARAM_TEXT);
	$formId = required_param('formid', PARAM_TEXT);

	$query = "SELECT * FROM ".$CFG->prefix."block_cmanager_formfields where formid = $formId ORDER BY position DESC";
	$record = $DB->get_record_sql($query, null, IGNORE_MISSING);

    // if no record exists, just start of with 1000 and
    // then add one on to the numbering
    $pos = 1000;
    if($record) {
        $pos = $record->position;
    }



	$pos++;


	if($fieldType == 'textfield'){

		$object = new stdClass();
		$object->id = '';
		$object->type = 'textfield';
		$object->position = $pos;
		$object->formid = $formId;
		$object->reqfield = '1';

		$id = $DB->insert_record('block_cmanager_formfields', $object, true);
		echo $id;

	}
	else if($fieldType == 'textarea'){

		$object = new stdClass();
		$object->id = '';
		$object->type = 'textarea';
		$object->position = $pos;
		$object->formid = $formId;
		$object->reqfield = '1';
		$id = $DB->insert_record('block_cmanager_formfields', $object, true);

		echo $id;


	}
	else if($fieldType == 'dropdown'){

		$object = new stdClass();
		$object->id = '';
		$object->type = 'dropdown';
		$object->position = $pos;
		$object->formid = $formId;
		$object->reqfield = '1';
		$id = $DB->insert_record('block_cmanager_formfields', $object, true);

		echo $id;
	}

	else if($fieldType == 'radio'){

		$object = new stdClass();
		$object->id = '';
		$object->type = 'radio';
		$object->position = $pos;
		$object->formid = $formId;
		$object->reqfield = '1';
		$id = $DB->insert_record('block_cmanager_formfields', $object, true);

		echo $id;
	}

}

/**
* Get a collection of dropdown menu values
*/
function block_cmanager_get_dropdown_values(){

	$id = required_param('id', PARAM_INT);
	global $DB;
	$field3ItemsHTML = '';
	$selectQuery = "fieldid = '$id'";
	$formid = $_SESSION['formid'];
	$field3Items = $DB->get_recordset_select('block_cmanager_form_data', $select=$selectQuery);

    if ($field3Items->valid()) {
        foreach ($field3Items as $item) {
            $field3ItemsHTML .= '<div class="row">';
            $field3ItemsHTML .= '<div class="col-sm-2">' . format_string($item->value, true, ['context' => context_system::instance()]) . '</div>';
            $field3ItemsHTML .= '<div class="col-sm-1"><a href="page2.php?id=' . $formid.'&t=dropitem&fid='.$id.'&del=' . $item->id . '"><i class="icon fa fa-trash fa-fw " title="' . get_string('delete') . '" aria-label="' . get_string('delete') . '"></i></a></div>';
            $field3ItemsHTML .= '</div>';
        }
    }

	echo $field3ItemsHTML;
}

/**
* Save changes that have been made
*/
function block_cmanager_save_changes(){
	global $CFG;
	global $DB;

	$f1t = required_param('f1t', PARAM_TEXT);
	$f1d = required_param('f1d', PARAM_TEXT);
	$f2t = required_param('f2t', PARAM_TEXT);
	$f2d = required_param('f2d', PARAM_TEXT);
	$f3d = required_param('f3d', PARAM_TEXT);
	$dStat = required_param('dstat', PARAM_TEXT);

	$field1title_id = $DB->get_field_select('block_cmanager_config', 'id', "varname = 'page1_fieldname1'");
    $field1desc_id = $DB->get_field_select('block_cmanager_config', 'id', "varname = 'page1_fielddesc1'");
    $field2title_id = $DB->get_field_select('block_cmanager_config', 'id', "varname = 'page1_fieldname2'");
    $field2desc_id = $DB->get_field_select('block_cmanager_config', 'id', "varname = 'page1_fielddesc2'");
	$field3desc_id = $DB->get_field_select('block_cmanager_config', 'id', "varname = 'page1_fielddesc3'");

	$statusField_id = $DB->get_field_select('block_cmanager_config', 'id', "varname = 'page1_field3status'");

	$dataobject->id = $field1title_id;
	$dataobject->varname['page1_fieldname1'];
	$dataobject->value = $f1t;
	$DB->update_record('block_cmanager_config', $dataobject);

	$dataobject->id = $field1desc_id;
	$dataobject->varname['page1_fielddesc1'];
	$dataobject->value = $f1d;
	$DB->update_record('block_cmanager_config', $dataobject);

	$dataobject->id = $field2title_id;
	$dataobject->varname['page1_fieldname2'];
	$dataobject->value = $f2t;
	$DB->update_record('block_cmanager_config', $dataobject);

	$dataobject->id = $field2desc_id;
	$dataobject->varname['page1_fielddesc2'];
	$dataobject->value = $f2d;
	$DB->update_record('block_cmanager_config', $dataobject);

	$dataobject->id = $field3desc_id;
	$dataobject->varname['page1_fielddesc3'];
	$dataobject->value = $f3d;
	$DB->update_record('block_cmanager_config', $dataobject);


	$dataobject->id = $statusField_id;
	$dataobject->varname['page1_field3status'];
	$dataobject->value = $dStat;
	$DB->update_record('block_cmanager_config', $dataobject);


}
/**
* Add a new item
*/
function block_cmanager_add_new_item(){
	global $CFG, $DB;

	$newValue = required_param('valuetoadd', PARAM_TEXT);

	$object;
	$object->varname = 'page1_field3value';
	$object->value = addslashes($newValue);
	$DB->insert_record('block_cmanager_config', $object, false, $primarykey='id');


}
/**
* Save an optional value
*/
function block_cmanager_save_optional_value(){



	global $CFG, $DB;

	$id = required_param('id', PARAM_INT);
	$value = required_param('value', PARAM_TEXT);

	$dataobject = new stdClass();
	$dataobject->id = $id;
	$dataobject->reqfield = addslashes($value);

	$DB->update_record('block_cmanager_formfields', $dataobject);


}
