<?php
/* --------------------------------------------------------- 



     COURSE REQUEST BLOCK FOR MOODLE  

     2012 Kyle Goslin & Daniel McSweeney



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





class courserequest_form extends moodleform {
 
    function definition() {
       
	    global $CFG;
        global $currentSess, $DB;
		
        $currentRecord =  $DB->get_record('block_cmanager_records', array('id'=>$currentSess), '*', IGNORE_MULTIPLE);
		$mform =& $this->_form; // Don't forget the underscore! 
 		$mform->addElement('html', '<style>
		#content {
		
		left:200px;
		}
		
		</style>
			');


	$mform->addElement('header', 'mainheader','<span style="font-size:18px">'.  get_string('modrequestfacility','block_cmanager'). '</span>');
  
    
   
	// Get the field values
	$field1title = $DB->get_field('block_cmanager_config', 'value', array('varname'=>'page1_fieldname1'), IGNORE_MULTIPLE);
	//$field1desc = $DB->get_field('block_cmanager_config', 'value', array('varname'=>'page1_fielddesc1'), IGNORE_MULTIPLE);
	$field2title = $DB->get_field('block_cmanager_config', 'value', array('varname'=>'page1_fieldname2'), IGNORE_MULTIPLE);
	//$field2desc = $DB->get_field('block_cmanager_config', 'value', array('varname'=>'page1_fielddesc2'), IGNORE_MULTIPLE);
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
	$mform->addElement('text', 'programmecode', $field1title, $attributes, 'sdfdsf');
	$mform->addRule('programmecode', get_string('request_rule1','block_cmanager'), 'required', null, 'server', false, false);
    
     $mform->addElement('html', '<p>&nbsp;');
	// Programme Title	
	$attributes = array();
	$attributes['value'] = $currentRecord->modname;
	$mform->addElement('text', 'programmetitle', $field2title, $attributes);
	$mform->addRule('programmetitle', get_string('request_rule1','block_cmanager'), 'required', null, 'server', false, false);
   
   
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

		$options = array();			
		$catItems = $DB->get_records('course_categories');
	
		foreach($catItems as $item){
	  	        	$options[$item->id] = $item->name;
						
					
	} 
		$mform->addElement('select', 'category', get_string('category'), $options);
		$mform->setDefault('category', $currentRecord->cate); 
		
		
		
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
        $buttonarray[] = &$mform->createElement('cancel');
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
	$selectQuery = "shortname LIKE '%$postCode%' 					
				    OR (shortname LIKE '%$spaceCheck%' AND shortname LIKE '%$postMode%')
					OR shortname LIKE '%$spaceCheck%'
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
	
	
	  
 


$field1desc = $DB->get_field('block_cmanager_config', 'value', array('varname'=>'page1_fielddesc1'), IGNORE_MULTIPLE);
$field2desc = $DB->get_field('block_cmanager_config', 'value', array('varname'=>'page1_fielddesc2'), IGNORE_MULTIPLE);
		
?>


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

<script>
		
	// Field 1 desc
	var theDiv = document.getElementById("fitem_id_programmecode");
	var div = document.createElement("div");
	div.style.width = "300px";
	div.style.height = "20px";
	div.style.position = "relative";
	div.style.left = "200px";
	div.style.top = "2px";
	div.style.background = "transparent";
	div.style.color = "#888";
	div.innerHTML = "<?php echo $field1desc; ?>";
	theDiv.appendChild(div);
	
	
	// Field 1 desc
	var theDiv = document.getElementById("fitem_id_programmetitle");
	var div = document.createElement("div");
	div.style.width = "300px";
	div.style.height = "40px";
	div.style.position = "relative";
	div.style.left = "200px";
	div.style.top = "2px";
	div.style.background = "transparent";
	div.style.color = "#888";
	div.innerHTML = "<?php echo $field2desc; ?>";
	theDiv.appendChild(div);

</script>
<?php
echo $OUTPUT->footer();