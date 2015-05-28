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
global $CFG, $DB;


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
	$dataobject->value = $value;
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
	
	$id = $_POST['id'];
	$value = required_param('value', PARAM_TEXT);  
	
	$object->id = '';
	$object->fieldid = $id;
	$object->value = addslashes($value);
	
	
	$id = $DB->insert_record('block_cmanager_form_data', $object, true); 
		
	
}

/**
* Update a field 
*/
function block_cmanager_update_field(){
	
	global $CFG, $DB;
	echo $elementId = $_POST['id'];
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
   
   	$fieldType = $_POST['fieldtype'];
	$formId = $_POST['formid'];
	
	$query = "SELECT * FROM ".$CFG->prefix."block_cmanager_formfields where formid = $formId ORDER BY position DESC";
	$record = $DB->get_record_sql($query, null, IGNORE_MISSING); 
	$pos = $record->position;
	$pos++;
		
		
	if($fieldType == 'textfield'){
		
		$object;
		$object->id = '';
		$object->type = 'textfield';
		$object->position = $pos;
		$object->formid = $formId;
		$object->reqfield = '1';
		
		$id = $DB->insert_record('block_cmanager_formfields', $object, true); 
		echo $id;
		
	}
	else if($fieldType == 'textarea'){
		
		$object;
		$object->id = '';
		$object->type = 'textarea';
		$object->position = $pos;
		$object->formid = $formId;
		$object->reqfield = '1';
		$id = $DB->insert_record('block_cmanager_formfields', $object, true); 
		
		echo $id;
		
	
	}
	else if($fieldType == 'dropdown'){
		
	
			
		$object;
		$object->id = '';
		$object->type = 'dropdown';
		$object->position = $pos;
		$object->formid = $formId;
		$object->reqfield = '1';
		$id = $DB->insert_record('block_cmanager_formfields', $object, true); 
		
		echo $id;
	}
	
	else if($fieldType == 'radio'){
		
		
		$object;
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
	
	$id = $_POST['id'];
	global $DB;
	$field3ItemsHTML = '';	
	$selectQuery = "fieldid = '$id'";
	$formid = $_SESSION['formid'];
	$field3Items = $DB->get_recordset_select('block_cmanager_form_data', $select=$selectQuery);
	
	$field3ItemsHTML .= '<table width="300px">';							  
				  foreach($field3Items as $item){
				  	$field3ItemsHTML .= '<tr>';
				  	$field3ItemsHTML .= '<td>' . $item->value . '</td> <td><a href="page2.php?id=' . $formid.'&t=dropitem&fid='.$id.'&del=' . $item->id . '"><img src="../images/deleteIcon.png" width="20" height="20" alt="delete" /></a></td>';
					$field3ItemsHTML .= '</tr>';
				  } 
	$field3ItemsHTML .= '</table>';

	echo $field3ItemsHTML;
	
	
}

/** 
* Save changes that have been made
*/
function block_cmanager_save_changes(){
	global $CFG;
	global $DB;
	
	$f1t = $_POST['f1t'];
	$f1d = $_POST['f1d'];
	$f2t = $_POST['f2t'];
	$f2d = $_POST['f2d'];
	$f3d = $_POST['f3d'];
	$dStat = $_POST['dstat'];	
	
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

	$newValue = $_POST['valuetoadd'];

	$object;
	$object->varname = 'page1_field3value';
	$object->value = $newValue;
	$DB->insert_record('block_cmanager_config', $object, false, $primarykey='id'); 


}
/** 
* Save an optional value
*/
function block_cmanager_save_optional_value(){
	
	
		
	global $CFG, $DB;

	$id = $_POST['id'];
	$value = $_POST['value'];
	
	
	$dataobject = new stdClass();
	$dataobject->id = $id;
	$dataobject->reqfield = $value;
	
	$DB->update_record('block_cmanager_formfields', $dataobject);
	

}


?>