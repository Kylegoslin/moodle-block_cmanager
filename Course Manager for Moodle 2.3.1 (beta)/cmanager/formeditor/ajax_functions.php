<?php
require_once("../../../config.php");
global $CFG, $DB;


$type = $_POST['type'];


if($type == 'add'){
	addNewItem();
}
else if($type == 'save'){
	saveChanges();
}
else if($type == 'page2addfield'){
	addField();
}
else if($type == 'updatefield'){
	updateField();
}
else if($type == 'addvaluetodropdown'){
	addValueToDropdown();
}
else if($type == 'getdropdownvalues'){
	getDropdownValues();
}
else if($type == 'addnewform'){
	addNewForm();
}
else if($type == 'saveselectedform'){
	saveSelectedForm();
}



function saveSelectedForm(){
	
	global $DB;
	//echo 'saving form';
	
	$value = $_POST['value'];
	$rowId = $DB->get_field_select('cmanager_config', 'id', "varname = 'current_active_form_id'");	
	
	$dataobject->id = $rowId;
	$dataobject->value = $value;
	$DB->update_record('cmanager_config', $dataobject);
	
	
	
}


function addNewForm(){
	
	global $DB;
	
	$formName = $_POST['value'];
	
	
	$object->id = '';
	$object->varname = 'page2form';
	$object->value = $formName;
	
	
	$id = $DB->insert_record('cmanager_config', $object, true); 
}


function addValueToDropdown(){
		
	global $DB;
	
	$id = $_POST['id'];
	$value = $_POST['value'];
	
	$object->id = '';
	$object->fieldid = $id;
	$object->value = $value;
	
	
	$id = $DB->insert_record('cmanager_formfields_data', $object, true); 
		
	
}

function updateField(){
	
	global $CFG, $DB;
	echo $elementId = $_POST['id'];
	echo $value = $_POST['value'];
	
	$dataobject->id = $elementId;
	$dataobject->lefttext = $value;
	$DB->update_record('cmanager_formfields', $dataobject);
	
}


function addField(){
 
    global $CFG, $DB;
   
   	$fieldType = $_POST['fieldtype'];
	$formId = $_POST['formid'];
	
	$query = "SELECT * FROM mdl_cmanager_formfields where formid = $formId ORDER BY position DESC";
	$record = $DB->get_record_sql($query, null, IGNORE_MISSING); 
	$pos = $record->position;
	$pos++;
		
		
	if($fieldType == 'textfield'){
		
		$object;
		$object->id = '';
		$object->type = 'textfield';
		$object->position = $pos;
		$object->formid = $formId;
		
		$id = $DB->insert_record('cmanager_formfields', $object, true); 
		echo $id;
		
	}
	else if($fieldType == 'textarea'){
		
		$object;
		$object->id = '';
		$object->type = 'textarea';
		$object->position = $pos;
		$object->formid = $formId;
		
		$id = $DB->insert_record('cmanager_formfields', $object, true); 
		
		echo $id;
		
	
	}
	else if($fieldType == 'dropdown'){
		
	
			
		$object;
		$object->id = '';
		$object->type = 'dropdown';
		$object->position = $pos;
		$object->formid = $formId;
		
		$id = $DB->insert_record('cmanager_formfields', $object, true); 
		
		echo $id;
	}
	
	else if($fieldType == 'radio'){
		
		
		$object;
		$object->id = '';
		$object->type = 'radio';
		$object->position = $pos;
		$object->formid = $formId;
		
		$id = $DB->insert_record('cmanager_formfields', $object, true); 
		
		echo $id;
	}
	 
}


function getDropdownValues(){
	
	 $id = $_POST['id'];
	 global $DB;
	 $field3ItemsHTML = '';	
	 $selectQuery = "fieldid = '$id'";
	 $formid = $_SESSION['formid'];
	 $field3Items = $DB->get_recordset_select('cmanager_formfields_data', $select=$selectQuery);
	
				$field3ItemsHTML .= '<table width="300px">';							  
							  foreach($field3Items as $item){
							  	$field3ItemsHTML .= '<tr>';
							  	$field3ItemsHTML .= '<td>' . $item->value . '</td> <td><a href="page2.php?id=' . $formid.'&t=dropitem&fid='.$id.'&del=' . $item->id . '"><img src="../images/deleteIcon.png" width="20" height="20" alt="delete" /></a></td>';
								$field3ItemsHTML .= '</tr>';
							  } 
				$field3ItemsHTML .= '</table>';
		
		echo $field3ItemsHTML;
	
	
}


function saveChanges(){
	global $CFG;
		global $DB;
	
	$f1t = $_POST['f1t'];
	$f1d = $_POST['f1d'];
	$f2t = $_POST['f2t'];
	$f2d = $_POST['f2d'];
	$f3d = $_POST['f3d'];
	$dStat = $_POST['dstat'];	
	
	$field1title_id = $DB->get_field_select('cmanager_config', 'id', "varname = 'page1_fieldname1'");
    $field1desc_id = $DB->get_field_select('cmanager_config', 'id', "varname = 'page1_fielddesc1'");
    $field2title_id = $DB->get_field_select('cmanager_config', 'id', "varname = 'page1_fieldname2'");
    $field2desc_id = $DB->get_field_select('cmanager_config', 'id', "varname = 'page1_fielddesc2'");
	$field3desc_id = $DB->get_field_select('cmanager_config', 'id', "varname = 'page1_fielddesc3'");
	
	$statusField_id = $DB->get_field_select('cmanager_config', 'id', "varname = 'page1_field3status'");
	
	$dataobject->id = $field1title_id;
	$dataobject->varname['page1_fieldname1'];
	$dataobject->value = $f1t;
	$DB->update_record('cmanager_config', $dataobject);
	
	$dataobject->id = $field1desc_id;
	$dataobject->varname['page1_fielddesc1'];
	$dataobject->value = $f1d;
	$DB->update_record('cmanager_config', $dataobject);
	
	$dataobject->id = $field2title_id;
	$dataobject->varname['page1_fieldname2'];
	$dataobject->value = $f2t;
	$DB->update_record('cmanager_config', $dataobject);
	
	$dataobject->id = $field2desc_id;
	$dataobject->varname['page1_fielddesc2'];
	$dataobject->value = $f2d;
	$DB->update_record('cmanager_config', $dataobject);
	
	$dataobject->id = $field3desc_id;
	$dataobject->varname['page1_fielddesc3'];
	$dataobject->value = $f3d;
	$DB->update_record('cmanager_config', $dataobject);
	
	
	$dataobject->id = $statusField_id;
	$dataobject->varname['page1_field3status'];
	$dataobject->value = $dStat;
	$DB->update_record('cmanager_config', $dataobject);
}

function addNewItem(){
	global $CFG, $DB;

	$newValue = $_POST['valuetoadd'];

	$object;
	$object->varname = 'page1_field3value';
	$object->value = $newValue;
	$DB->insert_record('cmanager_config', $object, false, $primarykey='id'); 


}
?>