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

require_once("../../../config.php");
global $CFG, $DB;

require_login();
require_once('../validate_admin.php');

$formPath = "$CFG->libdir/formslib.php";
require_once($formPath);

$PAGE->navbar->ignore_active();
$PAGE->navbar->add(get_string('cmanagerDisplay', 'block_cmanager'), new moodle_url('/blocks/cmanager/cmanager_admin.php'));
$PAGE->navbar->add(get_string('configurecoursemanagersettings', 'block_cmanager'), new moodle_url('/blocks/cmanager/cmanager_confighome.php'));
$PAGE->navbar->add(get_string('formpage2builder', 'block_cmanager'), new moodle_url('/blocks/cmanager/formeditor/form_builder.php'));
$PAGE->navbar->add(get_string('previewform', 'block_cmanager'));

$PAGE->set_url('/blocks/cmanager/formeditor/preview.php');
$PAGE->set_context(get_system_context());
$PAGE->set_heading(get_string('pluginname', 'block_cmanager'));
$PAGE->set_title(get_string('pluginname', 'block_cmanager'));
echo $OUTPUT->header();

if(isset($_GET['id'])){
	$formId = required_param('id', PARAM_INT);
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
class courserequest_form extends moodleform {
 
    function definition() {
        global $CFG;
        global $USER, $DB;
        $mform =& $this->_form; // Don't forget the underscore! 
 
 
        $fieldnameCounter = 1; // This counter is used to increment the naming conventions of each field.
		
		// Back Button
	
	   	$mform->addElement('header', 'mainheader', '<span style="font-size:18px">'.get_string('formBuilder_previewHeader','block_cmanager'). '</span>');
		
		$mform->addElement('html', '<p></p>	<button type="button" onclick="goBack();"><img src="../icons/back.png"/>'.get_string('back','block_cmanager').'</button><p></p>
	');

	         
		// Page description text
		$mform->addElement('html', '<p></p>&nbsp;&nbsp;&nbsp;'.get_string('formBuilder_previewInstructions1','block_cmanager').' <br>&nbsp;&nbsp;&nbsp;'.get_string('formBuilder_previewInstructions2','block_cmanager').'<p></p>&nbsp;');
		
		$mform->addElement('html', '<p></p><center><div style="width:800px; text-align:left"><b>Step 2: Other Details</b></div></center><p></p>');
	      
		  
		  
	    global $formId;
		
	   	$selectQuery = "";
		//$formFields = $DB->get_records('block_cmanager_formfields', 'formid', $formId, $sort='position ASC', $fields='*', $limitfrom='', $limitnum='');
		$formFields = $DB->get_records('block_cmanager_formfields', array('formid'=>$formId));
		
		foreach($formFields as $field){
			
			
			  $fieldName = 'f' . $fieldnameCounter; // Give each field an incremented fieldname.
			
			   if($field->type == 'textfield'){
			   	
				   createTextField(stripslashes($field->lefttext), $mform, $fieldName, $field->reqfield);
			   }
			   else if($field->type == 'textarea'){
			  		createTextArea(stripslashes($field->lefttext), $mform, $fieldName, $field->reqfield);
			   }
			   else if($field->type == 'dropdown'){
			   		createDropdown(stripslashes($field->lefttext), $field->id, $mform, $fieldName, $field->reqfield);
			   }
			   
			   else if($field->type == 'radio'){
			        createRadio(stripslashes($field->lefttext), $field->id, $mform, $fieldName, $field->reqfield);
			   }
			   
			   
			   $fieldnameCounter++;
		}
	   
	   
	   
	}
}
 
 
 
 
 

 
function createTextField($leftText, $form, $fieldName, $reqfield){
	
	$form->addElement('text', $fieldName, $leftText, '');
	if($reqfield == 1){
		$form->addRule($fieldName, '', 'required', null, 'server', false, false);
	}
}


function createTextArea($leftText, $form, $fieldName, $reqfield){
			
		
	$form->addElement('textarea', $fieldName, $leftText, 'wrap="virtual" rows="5" cols="60"');
	
	if($reqfield == 1){
		$form->addRule($fieldName, '', 'required', null, 'server', false, false);
	}
	
}


function createRadio($leftText, $id, $form, $fieldName, $reqfield){
		
	global $DB;
		
	 $selectQuery = "fieldid = '$id'";
	 $field3Items = $DB->get_recordset_select('block_cmanager_form_data', $select=$selectQuery);
	
	
	
	  $counter = 1;		
	  $attributes = '';							  
	  foreach($field3Items as $item){
	  	
		if($counter == 1){
			
			$radioarray=array();
			$radioarray[] = $form->createElement('radio', $fieldName, $leftText, $item->value,  $item->value, $attributes);
			$form->addGroup($radioarray, $fieldName, $leftText, array(' '), false);
			if($reqfield == 1){
				$form->addRule($fieldName, '', 'required', null, 'server', false, false);
			}
			
		    $counter++;
		} else {
			$radioarray=array();
			$radioarray[] = $form->createElement('radio', $fieldName, '', $item->value, $item->value, $attributes);
			//$form->addGroup($radioarray, $fieldName . $counter, '', array(' '), false);
			$form->addGroup($radioarray, $fieldName, '', array(' '), false);
			
		    $counter++;
		}
		
	  } 
			
	
	
	
	
	
}


/*
 * Create a Moodle form dropdown menu
 * 
 * 
 */
function createDropdown($leftText, $id, $form, $fieldName, $reqfield){
	
	global $DB;
	
	$options = array();
	    
	$selectQuery = "fieldid = '$id'";
	
	$field3Items = $DB->get_recordset_select('block_cmanager_form_data', $select=$selectQuery);
	
						  
					
		  foreach($field3Items as $item){
		  	         $value = $item->value;
					 if($value != ''){
						$options[$value] = $value;
						$options[$value] = $value;
					}
		  }
		  
		$form->addElement('select', $fieldName, $leftText , $options);
		if($reqfield == 1){
			$form->addRule($fieldName, 'Please select module mode.', 'required', null, 'server', false, false);
		}
	
}
  
?>


<?php
$mform = new courserequest_form();//name of the form you defined in file above.

if ($mform->is_cancelled()){


} else if ($fromform=$mform->get_data()){


	   
 
} else {

 
}


	
	$mform->focus();
	$mform->display();
	echo $OUTPUT->footer();



?>

  
	
		