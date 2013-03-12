<?php
/* --------------------------------------------------------- 

     COURSE REQUEST BLOCK FOR MOODLE  

     2012 Kyle Goslin & Daniel McSweeney
     Institute of Technology Blanchardstown
     Dublin 15, Ireland
 --------------------------------------------------------- */

require_once("../../config.php");
global $CFG, $DB;
$formPath = "$CFG->libdir/formslib.php";
require_once($formPath);

/** Navigation Bar **/
$PAGE->navbar->ignore_active();
$PAGE->navbar->add(get_string('cmanagerDisplay', 'block_cmanager'), new moodle_url('/blocks/cmanager/module_manager.php'));
$PAGE->navbar->add(get_string('modrequestfacility', 'block_cmanager'));


$PAGE->set_url('/blocks/cmanager/course_new.php');
$PAGE->set_context(get_system_context());
$PAGE->set_heading(get_string('pluginname', 'block_cmanager'));


// Get the session var to take the record from the database
// which we will populate this form with.
$inEditingMode = false;

if(isset($_GET['edit'])){
	$inEditingMode = true;
	$currentSess = required_param('edit', PARAM_TEXT);
} else {
	$currentSess = $_SESSION['cmanager_session'] ;


}

if(isset($_GET['status'])){
  $_SESSION['status'] = required_param('status', PARAM_TEXT);
}

class courserequest_form extends moodleform {
 
    function definition() {
        global $CFG;
        global $currentSess;
		global $inEditingMode, $DB;
		
        $currentRecord =  $DB->get_record('block_cmanager_records', array('id'=>$currentSess));
		
		$mform =& $this->_form; 
		$mform->addElement('header', 'mainheader','<span style="font-size:18px">'.  get_string('modrequestfacility','block_cmanager'). '</span>');

      	// Page description text
		$mform->addElement('html', '<p></p>'.get_string('courserequestline1','block_cmanager'));
		$mform->addElement('html', '<p></p><div style="width:545px; text-align:left"><b>'.get_string('formBuilder_step2','block_cmanager').'</b></div><p></p>');



/* --------------------------------------------------------------------------
 *  Dynamically generate the form from the pre-designed selected form.
 * 
 * 
 * --------------------------------------------------------------------------
 */
       $formId = $DB->get_field_select('block_cmanager_config', 'value', "varname = 'current_active_form_id'");
 
 
  	    $selectQuery = "";
		//$formFields = $DB->get_records('block_cmanager_formfields', 'formid', $formId, $sort='position ASC', $fields='*', $limitfrom='', $limitnum='');

		$DB->sql_order_by_text('position', $numchars=32);
		$formFields = $DB->get_records('block_cmanager_formfields',array('formid'=>$formId));
				
				
		$fieldnameCounter = 1;
		
		foreach($formFields as $field){
			
			
			  $fieldName = 'f' . $fieldnameCounter; // Give each field an incremented fieldname.
			   
			   if($field->type == 'textfield'){
			   	
				  if($inEditingMode == true){
				  	
				  		$fname = 'c' . $fieldnameCounter;
				  		$fieldValue = $currentRecord->$fname;
						
						
				  		createTextField($field->lefttext, $mform, $fieldName, $fieldValue, $field->reqfield);
				  } else {
				   createTextField($field->lefttext, $mform, $fieldName, '', $field->reqfield);
				  }
				  
			   }
			   else if($field->type == 'textarea'){
				   	if($inEditingMode == true){
					  	
					  		$fname = 'c' . $fieldnameCounter;
					  		$fieldValue = $currentRecord->$fname;
							createTextArea($field->lefttext, $mform, $fieldName, $fieldValue, $field->reqfield);
					} else {
				  		createTextArea($field->lefttext, $mform, $fieldName, '', $field->reqfield);
						
				  	}
			   }
			   else if($field->type == 'dropdown'){
			   	
				
					if($inEditingMode == true){
					  	
					  		$fname = 'c' . $fieldnameCounter;
					  		$fieldValue = $currentRecord->$fname;
							createDropdown($field->lefttext, $field->id, $mform, $fieldName, $fieldValue, $field->reqfield);
							
					} else  {
			   			createDropdown($field->lefttext, $field->id, $mform, $fieldName, '', $field->reqfield); 
					}
			   }
			   
			   else if($field->type == 'radio'){
			   
			  	 	if($inEditingMode == true){
					  	 $fname = 'c' . $fieldnameCounter;
					  	 $fieldValue = $currentRecord->$fname;
						 createRadio($field->lefttext, $field->id, $mform, $fieldName, $fieldValue, $field->reqfield);
						
					} else {
						 createRadio($field->lefttext, $field->id, $mform, $fieldName, '', $field->reqfield);
					}
			   
			       
			   }
			   
			   
			   $fieldnameCounter++;
		}
	   
 

   

	    $mform->addElement('html', '<p></p>&nbsp<p></p>');
	    $buttonarray=array();
        $buttonarray[] = &$mform->createElement('submit', 'submitbutton', get_string('Continue','block_cmanager'));
        $buttonarray[] = &$mform->createElement('cancel');
        $mform->addGroup($buttonarray, 'buttonar', '', array(' '), false);
        $mform->addElement('html', '<p></p>&nbsp<p></p>');
	}
}

 


   $mform = new courserequest_form();//name of the form you defined in file above.



  //default 'action' for form is strip_querystring(qualified_me())
  if ($mform->is_cancelled()){
        
	echo '<script>window.location="module_manager.php";</script>';

  } else if ($fromform=$mform->get_data()){

	global $USER, $COURSE, $CFG;

	// Update all the information in the database record	
	$newrec = new stdClass();
	$newrec->id = $currentSess;
	
	
	if(isset($_POST['f1'])){
	    $newrec->c1 = $_POST['f1'];
	}
	if(isset($_POST['f2'])){
    	$newrec->c2 = $_POST['f2'];
	}
	if(isset($_POST['f3'])){
		$newrec->c3 = $_POST['f3'];
	}
	if(isset($_POST['f4'])){
		$newrec->c4 = $_POST['f4'];
	}
	if(isset($_POST['f5'])){
    	$newrec->c5 = $_POST['f5'];
	}
	if(isset($_POST['f6'])){
    	$newrec->c6 = $_POST['f6'];
	}
	if(isset($_POST['f7'])){
    	$newrec->c7 = $_POST['f7'];
	}
	if(isset($_POST['f8'])){
	    $newrec->c8 = $_POST['f8'];
	}
	if(isset($_POST['f9'])){
  	 	 $newrec->c9 = $_POST['f9'];
	}
	if(isset($_POST['f10'])){
	     $newrec->c10 = $_POST['f10'];
	}
	if(isset($_POST['f11'])){
  	 	 $newrec->c11 = $_POST['f11'];
	}
	if(isset($_POST['f12'])){
	   	 $newrec->c12 = $_POST['f12'];
	}
	if(isset($_POST['f13'])){
 	  	 $newrec->c13 = $_POST['f13'];
	}
	if(isset($_POST['f14'])){
  	 	 $newrec->c14 = $_POST['f14'];
	}
	if(isset($_POST['f15'])){
  		  $newrec->c15 = $_POST['f15'];
	}
	
    // Tag the module as new  
	$newrec->req_type = 'New Module Creation';
	$newrec->status = 'PENDING';
	$DB->update_record('block_cmanager_records', $newrec); 

	echo "<script>window.location='review_request.php?id=$currentSess';</script>";
	die;

 
  } else {
          

 
}


 
/* --------------------------------------------------------
 * Dynamic Form creation functions
 * 
 * --------------------------------------------------------
 */ 
function createTextField($leftText, $form, $fieldName, $fieldValue, $reqfield){
	
	$attributes = array();
	$attributes['value'] = $fieldValue;
	$form->addElement('text', $fieldName, $leftText, $attributes);
	if($reqfield == 1){
		$form->addRule($fieldName, '', 'required', null, 'server', false, false);
	}
}


function createTextArea($leftText, $form, $fieldName, $fieldValue, $reqfield){
	
	$attributes = array();
	$attributes['wrap'] = 'virtual';
	$attributes['rows'] = '5';
	$attributes['cols'] = '60';
				
	$form->addElement('textarea', $fieldName, $leftText, $attributes);
	$form->setDefault($fieldName, $fieldValue); 
	if($reqfield == 1){
		$form->addRule($fieldName, '', 'required', null, 'server', false, false);
	}
}


function createRadio($leftText, $id, $form, $fieldName, $selectedValue, $reqfield){
				
	 global $DB;
	 $selectQuery = "fieldid = '$id'";
	 $field3Items = $DB->get_recordset_select('block_cmanager_form_data', $select=$selectQuery);
	
	  $counter = 1;									  
	  foreach($field3Items as $item){
	  	$attributes = '';
		if($counter == 1){ 
			
			$radioarray=array();
			$radioarray[] =& $form->createElement('radio', $fieldName, $leftText, $item->value,  $item->value, $attributes);
			$form->addGroup($radioarray, $fieldName, $leftText, array(' '), false);
			if($reqfield == 1){	
				$form->addRule($fieldName, '', 'required', null, 'server', false, false);
			}
			
		    $counter++;
		} else {
			$radioarray=array();
			$radioarray[] =& $form->createElement('radio', $fieldName, '', $item->value, $item->value, $attributes);
			$form->addGroup($radioarray, $fieldName, '', array(' '), false);
		    $counter++;
		}
		
	  } 
		
	
	$form->setDefault($fieldName, $selectedValue);
		
	
}

function createDropdown($leftText, $id, $form, $fieldName, $selectedValue, $reqfield){
	
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
		$form->setDefault($fieldName, $selectedValue);
		
		if($reqfield == 1){
			$form->addRule($fieldName, '', 'required', null, 'server', false, false);
		}
	
}
 
   echo $OUTPUT->header();
?>
<title>Course Manager</title>

<style>
div.fcontainer.clearfix {
 position:relative;
 top:20px;
 left:300px;
 
}
div.fdescription.required {
	
	position:relative;
	
	right:400px;
}
	
</style>
<?php

   $mform->focus();
   $mform->set_data($mform);
   $mform->display();
   echo $OUTPUT->footer();
