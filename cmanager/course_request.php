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
global $CFG, $USER, $DB;
require_once("$CFG->libdir/formslib.php");
require_once('../../course/lib.php');
require_login();
/** Navigation Bar **/
$PAGE->navbar->ignore_active();
$PAGE->navbar->add(get_string('cmanagerDisplay', 'block_cmanager'), new moodle_url('/blocks/cmanager/module_manager.php'));
$PAGE->navbar->add(get_string('modrequestfacility', 'block_cmanager'));
$PAGE->set_url('/blocks/cmanager/course_request.php');
$PAGE->set_context(get_system_context());
$PAGE->set_heading(get_string('pluginname', 'block_cmanager'));
$PAGE->set_title(get_string('pluginname', 'block_cmanager'));
echo $OUTPUT->header();

/** Main variable for storing the current session id. **/
$currentSess = '00';
$inEditingMode = false;



// Insert a new blank record into the database for this session
if(isset($_GET['new'])){
	if(required_param('new', PARAM_INT) == 1){
          
       $_SESSION['cmanager_addedmods'] = '';
	   $newrec = new stdClass();
       $newrec->modname = '';
	   $newrec->createdbyid = $USER->id;
	   $newrec->createdate = date("d/m/y H:i:s");
	   $newrec->formid = $DB->get_field('block_cmanager_config', 'value', array('varname'=>'current_active_form_id'));
	  
	   $currentSess = $DB->insert_record('block_cmanager_records', $newrec, true);
       $_SESSION['cmanager_session'] = $currentSess;
	
	}		
} 
else if (isset($_GET['edit'])){ // If we are editing the mod
	$inEditingMode = true;
	$currentSess = required_param('edit', PARAM_INT);
    $_SESSION['cmanager_session'] = $currentSess;
	$_SESSION['cmanagermode'] = 'admin';
} else { // If we have already stated a session

	$currentSess = $_SESSION['cmanager_session'];
}



$currentRecord =  $DB->get_record('block_cmanager_records', array('id'=>$currentSess), '*', IGNORE_MULTIPLE);
	
// Quick hack to stop guests from making requests!		
if($USER->id == 1){
	echo print_error('Sorry no guest access, please login.');
	die;
}

class courserequest_form extends moodleform {
 
    function definition() {
       
	    global $CFG;
        global $currentSess, $DB, $currentRecord;
		
        $mform =& $this->_form; // Don't forget the underscore! 
 		
		 


	$mform->addElement('header', 'mainheader','<span style="font-size:18px">'.  get_string('modrequestfacility','block_cmanager'). '</span>');
  
    $field1desc = $DB->get_field('block_cmanager_config', 'value', array('varname'=>'page1_fielddesc1'), IGNORE_MULTIPLE);
	$field2desc = $DB->get_field('block_cmanager_config', 'value', array('varname'=>'page1_fielddesc2'), IGNORE_MULTIPLE);
	
   
	// Get the field values
	$field1title = $DB->get_field('block_cmanager_config', 'value', array('varname'=>'page1_fieldname1'), IGNORE_MULTIPLE);
	$field2title = $DB->get_field('block_cmanager_config', 'value', array('varname'=>'page1_fieldname2'), IGNORE_MULTIPLE);
	$field3desc = $DB->get_field('block_cmanager_config', 'value', array('varname'=>'page1_fielddesc3'), IGNORE_MULTIPLE);
	$field4title = $DB->get_field('block_cmanager_config', 'value', array('varname'=>'page1_fieldname4'), IGNORE_MULTIPLE);
	$field4desc = $DB->get_field('block_cmanager_config', 'value', array('varname'=>'page1_fielddesc4'), IGNORE_MULTIPLE);
 	//get field 3 status
	$field3status = $DB->get_field('block_cmanager_config', 'value', array('varname'=>'page1_field3status'), IGNORE_MULTIPLE);
  	//get the value for autokey - the config variable that determines enrolment key auto or prompt
	$autoKey = $DB->get_field_select('block_cmanager_config', 'value', "varname = 'autoKey'");
			
	$selfcat = $DB->get_field_select('block_cmanager_config', 'value', "varname = 'selfcat'");
	
	// Page description text
	$mform->addElement('html', '<p></p>'.get_string('courserequestline1','block_cmanager'));
	$mform->addElement('html', '<p></p><div style="width:545px; text-align:left"><b>' . get_string('step1text','block_cmanager'). '</b></div><p></p><br>');

	// Programme Code
	$attributes = array();
	$attributes['value'] = $currentRecord->modcode;
	$mform->addElement('text', 'programmecode', $field1title, $attributes, '');
	$mform->addRule('programmecode', get_string('request_rule1','block_cmanager'), 'required', null, 'server', false, false);
    

	$mform->addElement('static', 'description', '', $field1desc);
	$mform->addElement('html', '<p></p>');


	// Programme Title	
	$attributes = array();
	$attributes['value'] = $currentRecord->modname;
	$mform->addElement('text', 'programmetitle', $field2title, $attributes);
	$mform->addRule('programmetitle', get_string('request_rule1','block_cmanager'), 'required', null, 'server', false, false);
   
   	$mform->addElement('static', 'description', '', $field2desc);
	$mform->addElement('html', '<p>&nbsp;<br>');
	 
	 
	// Programme Mode
	if($field3status == 'enabled'){
			
		$options = array();
	    $selectQuery = "varname = 'page1_field3value'";
	 	$field3Items = $DB->get_recordset_select('block_cmanager_config', $select=$selectQuery);
	
		foreach($field3Items as $item){
		  	         $value = $item->value;
					 if($value != ''){
						$options[$value] = $value;
						$options[$value] = $value;
					}
		} 
		
	    $mform->addElement('select', 'programmemode', $field3desc , $options);
		$mform->addRule('programmemode', get_string('request_rule2','block_cmanager'), 'required', null, 'server', false, false);
		$mform->setDefault('programmemode', $currentRecord->modmode);
	 }
	 
	 
	 // If enabled, give the user the option
	 // to select a category location for the course.
	 
	if($selfcat == 'yes'){
		
	
	    $movetocategories = array();
        $notused = array();
        make_categories_list($movetocategories, $notused);
      	$cateDrop =  html_writer::select($movetocategories, 'category', null, null);
 	    $mform->addElement('html', '<div id="catname" class="catname">'.get_string('category') .' '. $cateDrop . '</div>');
	
	}
	 
	 
	 if(!$autoKey){
	 
	 // enrolment key
	$attributes = array();
	$mform->addElement('html', '<br><br>');
	$attributes['value'] = $currentRecord->modkey;
	$mform->addElement('text', 'enrolkey', $field4title, $attributes);
	$mform->addRule('enrolkey', get_string('request_rule3','block_cmanager'), 'required', null, 'server', false, false);
   
	
	
	}
 
	// Hidden form element to pass the key
	global $inEditingMode;
	if($inEditingMode){
		$mform->addElement('hidden', 'editingmode', $currentSess); 
	}
	
	    $mform->addElement('html', '<p></p>&nbsp<p></p>');
	    $buttonarray=array();
        $buttonarray[] = &$mform->createElement('submit', 'submitbutton', get_string('Continue','block_cmanager'));
        $buttonarray[] = &$mform->createElement('cancel', 'cancel', get_string('cancel','block_cmanager'));
        $mform->addGroup($buttonarray, 'buttonar', '', array(' '), false); 
	
	}
}

  $mform = new courserequest_form();//name of the form you defined in file above.

  if ($mform->is_cancelled()){
        
	echo '<script>window.location="module_manager.php";</script>';
	die;
  } else if ($fromform=$mform->get_data()){

	global $USER;
	global $COURSE;
 	global $CFG;


	   $newrec = new stdClass();
	   $newrec->id = $currentSess;
	   
	   $postTitle = $_POST['programmetitle'];
       $newrec->modname = $postTitle;
	   
	   $postCode = $_POST['programmecode'];	   
	   $newrec->modcode = $postCode;
	   
	   if(isset($_POST['category'])){
	   	$postCategory = $_POST['category'];
	   	$newrec->cate = $postCategory;
	   }
	   
	   $postKey = '';
	   if(isset($_POST['enrolkey'])){
	   	$postKey = $_POST['enrolkey'];
	   	$newrec->modkey = $postKey;
	   }

		$postMode = '';
		if(isset($_POST['programmemode'])){
	   	 $postMode = $_POST['programmemode'];
	  	 $newrec->modmode = $postMode;
	   }
	
	   $DB->update_record('block_cmanager_records', $newrec); 



	// Find which records are similar to the one which we are currently looking for.
	$spaceCheck =  substr($postCode, 0, 4) . ' ' . substr($postCode, 4, strlen($postCode));
	$selectQuery = "shortname LIKE '%".addslashes($postCode)."%' 					
				    OR (shortname LIKE '%".addslashes($spaceCheck)."%' AND shortname LIKE '%".addslashes($postMode)."%')
					OR shortname LIKE '%".addslashes($spaceCheck)."%'
					";
	
	

	if(isset($_POST['editingmode'])){
	 	$editSessId = addslashes($_POST['editingmode']);
	 	echo "<script>window.location='course_new.php?edit=$editSessId';</script>";
     	die;
	 }
	$recordsExist = $DB->record_exists_select('course', $selectQuery);
	if($recordsExist){
		
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
	


	if(!empty($currentRecord->cate)){
		echo '<script> document.getElementById("menucategory").value = '.$currentRecord->cate.'; </script> ';
	}

echo $OUTPUT->footer();