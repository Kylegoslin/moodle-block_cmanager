<?php
/* --------------------------------------------------------- 

     COURSE REQUEST BLOCK FOR MOODLE  

     2012 Kyle Goslin & Daniel McSweeney
     Institute of Technology Blanchardstown
     Dublin 15, Ireland
 --------------------------------------------------------- */

require_once("../../../config.php");
global $CFG, $DB;

require_once("$CFG->libdir/formslib.php");
require_once('../../../course/lib.php');
require_once($CFG->libdir.'/completionlib.php');
require_login();
$PAGE->set_url('/blocks/cmanager/admin/approve_course_new.php');
$PAGE->set_context(get_system_context());

?>

<link rel="stylesheet" type="text/css" href="css/main.css" />
<SCRIPT LANGUAGE="JavaScript" SRC="http://code.jquery.com/jquery-1.6.min.js">
</SCRIPT>
<?php


if(isset($_GET['id'])){
	$mid = required_param('id', PARAM_INT);
	$_SESSION['mid'] = $mid;
} else {

	$mid = $_SESSION['mid'];
}


	      
	class NewCourse {

     	public $returnto = 'topcat';
	 	public $category = 1;
	    public $fullname = '';
	    public $shortname = ''; 
	    public $idnumber = ''; 
	    public $summary_editor = array("text" => "", "format" => "1", "itemid" => '');
		public $format = 'weeks';
		public $numsections = '10';
		public $startdate = '1336003200';
		public $hiddensections = '0';
		public $newsitems = '5';
		public $showgrades = '1';
		public $showreports = '0';
		public $maxbytes = '2097152';
		public $enrol_guest_status_0 = 1;
		public $groupmode = 0;
		public $groupmodeforce = '';
		public $defaultgroupingid = '';
		public $visible = '1';
		public $lang = '';
		public $enablecompletion = '';
		public $completionstartonenrol = '';
		public $restrictmodules = ''; 
		public $role_1 = '';
		public $role_2 = '';
		public $mform_showadvanced_last = ''; 
		public $role_3 = '';
		public $role_4 = '';
	 	public $role_5 = '';
		public $role_6 = '';
		public $role_7 = '';
		public $role_8 = '';
		public $role_9 = '';
	}

	/** Create an objec to hold our new course information**/
	$new_course = new NewCourse();
	
	
	
	//Get the default timestamp for new courses
	$timestamp_startdate = $DB->get_field('block_cmanager_config', 'value', array('varname'=>'startdate'), IGNORE_MULTIPLE);
	$new_course->startdate = $timestamp_startdate;


	
	//is course mode enabled (page 1 optional dropdown)
	$mode = $DB->get_field('block_cmanager_config', 'value', array('varname'=>'page1_field3status'));

	// what naming mode is operating
	$naming = $DB->get_field('block_cmanager_config', 'value', array('varname'=>'naming'), IGNORE_MULTIPLE);
 	
	//what short naming format is operating
	$snaming = $DB->get_field('block_cmanager_config', 'value', array('varname'=>'snaming'), IGNORE_MULTIPLE);

	//get the record for the request
	$rec =  $DB->get_record('block_cmanager_records', array('id'=>$mid));

    
	/// Build up a course record based on the request.
    $selected_course_category = $CFG->defaultrequestcategory;
	$new_course->category = $selected_course_category;
	
	
	/*
    $course->sortorder = $DB->get_field_sql("SELECT min(sortorder)-1 FROM {$CFG->prefix}course WHERE category=$course->category");
  
    if (empty($course->sortorder)) {
            $course->sortorder = 1000;
    }*/
   
	// Fields we are carrying across
	if ($mode == "enabled" && $snaming==2){
		$new_course->shortname = $rec->modcode . ' - ' . $rec->modmode;
	}else{
		$new_course->shortname = $rec->modcode;	
	}
	
	$p_key = $rec->modkey;
	
	//course naming
	if ($naming == 1){
		$new_course->fullname = $rec->modname;
	}
	else if($naming ==2){
		$new_course->fullname = $rec->modcode . ' - '. $rec->modname;	
	}
	else{
		$new_course->fullname = $rec->modcode . ' ('. $rec->modname . ')';
	}
	
	
	
	
	
	// Enrollment key?
	//if its set lets use the key thats been set, otherwise auto gen a key
	if(isset($rec->modkey)){
		$modkey = $rec->modkey;
	} else{
		$modkey = rand(999,5000);	
	}
	
	

	 $categoryid = $new_course->category;
	 $category = $DB->get_record('course_categories', array('id'=>$categoryid));
	 $catcontext = get_context_instance(CONTEXT_COURSECAT, $category->id);
	 $contextobject = $catcontext;
	 $editoroptions = array('maxfiles' => EDITOR_UNLIMITED_FILES, 'maxbytes'=>$CFG->maxbytes, 'trusttext'=>false, 'noclean'=>true, 'content'=>$contextobject);
	
	 // Create the course
	 $course = create_course($new_course, $editoroptions);
	   
	 // Forward to the course editing page to allow the admin to make
	 // any changes to the course
	 $nid = $course->id;
	 
	 
	 
	 
	 
	 // Update the record to say that it is now complete
	 $updatedRecord = new stdClass();
	 $updatedRecord->id = $rec->id;
	 $updatedRecord->status = 'COMPLETE';
	 $DB->update_record('block_cmanager_records', $updatedRecord);
	 
	 
	 // Try enroll the creator
	 
        if (!empty($CFG->creatornewroleid)) {
            // deal with course creators - enrol them internally with default role
            enrol_try_internal_enrol($nid, $rec->createdbyid, $CFG->creatornewroleid);

        }
	 
		 // Add enrollnent key
		$enrollmentRecord = new stdClass();
		$enrollmentRecord->enrol =  'self';
		$enrollmentRecord->status = 0;
		$enrollmentRecord->courseid = $nid;
		$enrollmentRecord->sortorder = 3;
		$enrollmentRecord->name = '';
		$enrollmentRecord->enrolperiod =  0;
		$enrollmentRecord->enrolenddate = 0;
		$enrollmentRecord->expirynotify = 0;
		$enrollmentRecord->expirythreshold =0; 
		$enrollmentRecord->notifyall = 0;
		$enrollmentRecord->password = $modkey;
		$enrollmentRecord->cost = NULL;
		$enrollmentRecord->currency = NULL;  
		$enrollmentRecord->roleid =  5 ;
		$enrollmentRecord->customint1 = 0 ;
		$enrollmentRecord->customint2 = 0;
		$enrollmentRecord->customint3 = 0;
		$enrollmentRecord->customint4 = 1;
		$enrollmentRecord->customchar1 = NULL;
		$enrollmentRecord->customchar2 = NULL;
		$enrollmentRecord->customdec1 = NULL;
		$enrollmentRecord->customdec2 = NULL;
		$enrollmentRecord->customtext1 = '';
		$enrollmentRecord->customtext2 = NULL;
		$enrollmentRecord->timecreated = time();
		$enrollmentRecord->timemodified = time();
		
		$DB->insert_record('enrol', $enrollmentRecord);
	 
	 
	 
	 
	 
	 sendEmails($nid, $new_course->shortname, $new_course->fullname, $modkey);
	 echo '<script> window.location ="../../../course/edit.php?id=' .$nid . '";</script>';



/*
 * Send emails to everyone that is related to this module.
 * 
 */
function sendEmails($courseid, $modcode, $modname, $modkey){

	global $USER, $CFG, $mid, $DB;


		// Send an email to everyone concerned.
		require_once('../cmanager_email.php');
		
		// Get all user id's from the record
		$currentRecord =  $DB->get_record('block_cmanager_records', array('id'=>$mid));
		$user_ids = '';
		$user_ids = $currentRecord->createdbyid; // Add the current user

		$replaceValues = array();
	    $replaceValues['[course_code'] = $modcode;
	    $replaceValues['[course_name]'] = $modname;
	    $replaceValues['[e_key]'] = $modkey;
	    $replaceValues['[full_link]'] = $CFG->wwwroot .'/course/view.php?id=' . $courseid;
	    $replaceValues['[loc]'] = 'Location: ' . '';
	    $replaceValues['[req_link]'] = $CFG->wwwroot .'/blocks/cmanager/view_summary.php?id=' . $courseid;
	    

		//mail the user
		new_course_approved_mail_user($user_ids, $replaceValues);
		
		//mail the admin
		new_course_approved_mail_admin($replaceValues);
  
}
?>
