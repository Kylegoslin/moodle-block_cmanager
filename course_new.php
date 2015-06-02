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
require_once("../../config.php");
global $CFG, $DB;
$formPath = "$CFG->libdir/formslib.php";
require_once($formPath);

/** Navigation Bar **/
$PAGE->navbar->ignore_active();
$PAGE->navbar->add(get_string('cmanagerDisplay', 'block_cmanager'), new moodle_url('/blocks/cmanager/module_manager.php'));
$PAGE->navbar->add(get_string('modrequestfacility', 'block_cmanager'));
$PAGE->set_url('/blocks/cmanager/course_new.php');
$PAGE->set_context(context_system::instance());

$PAGE->set_heading(get_string('pluginname', 'block_cmanager'));


$context = context_system::instance();
if (has_capability('block/cmanager:addrecord',$context)) {
} else {
  print_error(get_string('cannotrequestcourse', 'block_cmanager'));
}



// Get the session var to take the record from the database
// which we will populate this form with.
$ineditingmode = false;

$editid = optional_param('edit', '0', PARAM_INT);
if ($editid) {
    $ineditingmode = true;
    $currentsess = $editid; 
} else {
    $currentsess = $_SESSION['cmanager_session'] ;
}

/**
 * cmanager new course form
 *
 * Form fields for additional data during the process of requesting a new course.
 * @package    block_cmanager
 * @copyright  2014 Kyle Goslin, Daniel McSweeney
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_cmanager_new_course_form extends moodleform {
 
    function definition() {
        global $CFG, $currentsess, $ineditingmode, $DB;
		
        $currentrecord =  $DB->get_record('block_cmanager_records', array('id'=>$currentsess));
		
		$mform =& $this->_form; 
		$mform->addElement('header', 'mainheader','<span style="font-size:18px">'.  
                           get_string('modrequestfacility','block_cmanager'). '</span>');

      	// Page description text
		$mform->addElement('html', '<p></p>'.get_string('courserequestline1','block_cmanager'));
		$mform->addElement('html', '<p></p><div style="width:545px; text-align:left"><b>'. 
                           get_string('formBuilder_step2','block_cmanager').'</b></div><p></p>');

        // Dynamically generate the form from the pre-designed selected form.
        $formid = $DB->get_field_select('block_cmanager_config', 'value', "varname = 'current_active_form_id'");
 
  	    $selectquery = "";
		$formfields = $DB->get_records('block_cmanager_formfields', array('formid'=>$formid), $sort='position ASC');		
				
        $fieldnamecounter = 1;
		
        foreach ($formfields as $field) {
            $fieldname = 'f' . $fieldnamecounter; // Give each field an incremented fieldname.
			   
			if ($field->type == 'textfield') {   	
			    if ($ineditingmode == true) {
				    $fname = 'c' . $fieldnamecounter;
				  	$fieldvalue = $currentrecord->$fname;
					block_cmanager_create_text_field(stripslashes($field->lefttext), $mform, $fieldname, 
                                                     $fieldvalue, $field->reqfield);
				} else {
				      block_cmanager_create_text_field(stripslashes($field->lefttext), $mform, $fieldname, '', 
                                                       $field->reqfield);
				  }
				  
			}
            else if ($field->type == 'textarea') {
			        if ($ineditingmode == true) {
					    $fname = 'c' . $fieldnamecounter;
					  	$fieldvalue = $currentrecord->$fname;
						block_cmanager_create_text_area(stripslashes($field->lefttext), $mform, $fieldname, 
                                                        $fieldvalue, $field->reqfield);
					} else {
				  		block_cmanager_create_text_Area(stripslashes($field->lefttext), $mform, $fieldname, 
                                                        '', $field->reqfield);
				  	}
			}
			else if ($field->type == 'dropdown') {
			        if ($ineditingmode == true) {
					    $fname = 'c' . $fieldnamecounter;
					  	$fieldvalue = $currentrecord->$fname;
						block_cmanager_create_dropdown(stripslashes($field->lefttext), $field->id, $mform, 
                                                       $fieldname, $fieldvalue, $field->reqfield);
							
					} else  {
			   			block_cmanager_create_dropdown(stripslashes($field->lefttext), $field->id, $mform, 
                                                       $fieldname, '', $field->reqfield); 
					}
		   }
		   else if ($field->type == 'radio') {
			  	 	if ($ineditingmode == true) {
					  	 $fname = 'c' . $fieldnamecounter;
					  	 $fieldvalue = $currentrecord->$fname;
						 block_cmanager_create_radio(stripslashes($field->lefttext), $field->id, $mform, 
                                                     $fieldname, $fieldvalue, $field->reqfield);
						
					} else {
						 block_cmanager_create_radio(stripslashes($field->lefttext), $field->id, $mform, 
                                                     $fieldname, '', $field->reqfield);
					}
		  }
			   
			   
			   $fieldnamecounter++;
		}
	   
 
	    $mform->addElement('html', '<p></p>&nbsp<p></p>');
	    $buttonarray=array();
        $buttonarray[] = &$mform->createElement('submit', 'submitbutton', get_string('Continue','block_cmanager'));
        $buttonarray[] = &$mform->createElement('cancel', 'cancel', get_string('requestReview_CancelRequest','block_cmanager'));
        $mform->addGroup($buttonarray, 'buttonar', '', array(' '), false);
        $mform->addElement('html', '<p></p>&nbsp<p></p>');
	}
}

 


$mform = new block_cmanager_new_course_form();//name of the form you defined in file above.



//default 'action' for form is strip_querystring(qualified_me())
if ($mform->is_cancelled()) {
	echo '<script>window.location="module_manager.php";</script>';
	die;

} else if ($fromform=$mform->get_data()) {

    global $USER, $COURSE, $CFG;

	// Update all the information in the database record	
	$newrec = new stdClass();
	$newrec->id = $currentsess;
	
    if (!empty($fromform->f1)) {
        $newrec->c1 = $fromform->f1;
    }
    if (!empty($fromform->f2)) {
        $newrec->c2 = $fromform->f2;
    }
    if (!empty($fromform->f3)) {
        $newrec->c3 = $fromform->f3;
    }
    if (!empty($fromform->f4)) {
        $newrec->c4 = $fromform->f4;
    }
    if (!empty($fromform->f5)) {
        $newrec->c5 = $fromform->f5;
    }
    if (!empty($fromform->f6)) {
        $newrec->c6 = $fromform->f6;
    }
    if (!empty($fromform->f7)) {
        $newrec->c7 = $fromform->f7;
    }
    if (!empty($fromform->f8)) {
        $newrec->c8 = $fromform->f8;
    }
    if (!empty($fromform->f9)) {
        $newrec->c9 = $fromform->f9;
    }
    if (!empty($fromform->f10)) {
        $newrec->c10 = $fromform->f10;
    }
    if (!empty($fromform->f11)) {
        $newrec->c11 = $fromform->f11;
    }
    if (!empty($fromform->f12)) {
        $newrec->c12 = $fromform->f12;
    }
    if (!empty($fromform->f13)) {
        $newrec->c13 = $fromform->f13;
    }
    if (!empty($fromform->f14)) {
        $newrec->c14 = $fromform->f14;
    }
    if (!empty($fromform->f15)) {
        $newrec->c15 = $fromform->f15;
    }

    // Tag the module as new  
	$newrec->req_type = 'New Module Creation';
	$newrec->status = 'PENDING';
	$DB->update_record('block_cmanager_records', $newrec); 

	echo "<script>window.location='review_request.php?id=$currentsess';</script>";
	die;
 
  }
/**
* Dynamic text field creation
*/ 
function block_cmanager_create_text_field($lefttext, $form, $fieldname, $fieldvalue, $reqfield) {
	
	$attributes = array();
	$attributes['value'] = $fieldvalue;
	$form->addElement('text', $fieldname, $lefttext, $attributes);
	if ($reqfield == 1) {
		$form->addRule($fieldname, '', 'required', null, 'server', false, false);
        $form->setType($fieldname, PARAM_TEXT);
	}
}

/**
* Dynamic text area creation 
*/
function block_cmanager_create_text_area($lefttext, $form, $fieldname, $fieldvalue, $reqfield) {
	
	$attributes = array();
	$attributes['wrap'] = 'virtual';
	$attributes['rows'] = '5';
	$attributes['cols'] = '60';
				
	$form->addElement('textarea', $fieldname, $lefttext, $attributes);
	$form->setDefault($fieldname, $fieldvalue); 
    $form->setType($fieldname, PARAM_TEXT);
	if ($reqfield == 1) {
		$form->addRule($fieldname, '', 'required', null, 'server', false, false);
	}
}


/**
* Dynamic radio button creation
*/
function block_cmanager_create_radio($lefttext, $id, $form, $fieldname, $selectedValue, $reqfield) {
				
    global $DB;
	$selectquery = "fieldid = '$id'";
	$field3Items = $DB->get_recordset_select('block_cmanager_form_data', $select=$selectquery);
	
	$counter = 1;									  
	foreach ($field3Items as $item) {
	    $attributes = '';
	    if ($counter == 1) { 
			$radioarray=array();
			$radioarray[] =& $form->createElement('radio', $fieldname, $lefttext, $item->value,
                                                  $item->value, $attributes);
			$form->addGroup($radioarray, $fieldname, $lefttext, array(' '), false);
		    if ($reqfield == 1) {	
			    $form->addRule($fieldname, '', 'required', null, 'server', false, false);
              
			}
			
		    $counter++;
		} else {
			$radioarray=array();
			$radioarray[] =& $form->createElement('radio', $fieldname, '', $item->value, 
                                                  $item->value, $attributes);
			$form->addGroup($radioarray, $fieldname, '', array(' '), false);
		    $counter++;
		}
		
	  } 
		
	
	$form->setDefault($fieldname, $selectedValue);
    $form->setType($fieldname, PARAM_TEXT);	
	
}

/**
* Dynamically create a drop down select menu
*/
function block_cmanager_create_dropdown($lefttext, $id, $form, $fieldname, $selectedValue, $reqfield){
	
	global $DB;
	
	$options = array();
	    
	$selectquery = "fieldid = '$id'";
	$field3Items = $DB->get_recordset_select('block_cmanager_form_data', $select=$selectquery);
	foreach ($field3Items as $item) {
	        $value = $item->value;
			if ($value != '') {
			    $options[$value] = $value;
			    $options[$value] = $value;
			}
	 }
		  
	$form->addElement('select', $fieldname, $lefttext , $options);
	$form->setDefault($fieldname, $selectedValue);
		
	if ($reqfield == 1) {
	    $form->addRule($fieldname, '', 'required', null, 'server', false, false);
	}
    $form->setType($fieldname, PARAM_TEXT);
	
}
 
echo $OUTPUT->header();

$mform->focus();
$mform->set_data($mform);
$mform->display();
echo $OUTPUT->footer();
