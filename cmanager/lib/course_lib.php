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

/** 
* Building up the new course object
*/
class block_cmanager_new_course {

     	public $returnto = 'topcat';
	 	public $category = 1;
	    public $fullname = '';
	    public $shortname = ''; 
	    public $idnumber = ''; 
	    public $summary_editor = array("text" => "", "format" => "1", "itemid" => '');
		public $format = '';
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

/** 
 *  Create a new Module on the Moodle installation based
 * upon the ID of the record in the course request system.
 * 
 */
function block_cmanager_create_new_course_by_record_id($mid, $sendMail) {
	
	global $CFG, $DB;
	require_once("$CFG->libdir/formslib.php");
	require_once('../../../course/lib.php');
	require_once($CFG->libdir.'/completionlib.php');
	
	

    //** Create an object to hold our new course information
    $new_course = new block_cmanager_new_course();

    $new_course->format = get_config('moodlecourse', 'format');
    //Get the default timestamp for new courses
    $timestamp_startdate = $DB->get_field('block_cmanager_config', 'value', array('varname'=>'startdate'), IGNORE_MULTIPLE);
    $new_course->startdate = $timestamp_startdate;

    $new_course->newsitems = get_config('moodlecourse','newsitems');
    $new_course->showgrades = get_config('moodlecourse','showgrades');
    $new_course->showreports = get_config('moodlecourse','showreports');
    $new_course->maxbytes = get_config('moodlecourse','maxbytes');

    //Formatting
    $new_course->numsections = get_config('moodlecourse','numsections');
    $new_course->hiddensections = get_config('moodlecourse','hiddensections');

    // Groups
    $new_course->groupmode = get_config('moodlecourse','groupmode');
    $new_course->groupmodeforce = get_config('moodlecourse','groupmodeforce');


    // Visible
    $new_course->visible = get_config('moodlecourse','visible');
    $new_course->lang = get_config('moodlecourse','lang');

    //is course mode enabled (page 1 optional dropdown)
    $mode = $DB->get_field('block_cmanager_config', 'value', array('varname'=>'page1_field3status'));
    // what naming mode is operating
    $naming = $DB->get_field('block_cmanager_config', 'value', array('varname'=>'naming'), IGNORE_MULTIPLE);
    	//what short naming format is operating
    $snaming = $DB->get_field('block_cmanager_config', 'value', array('varname'=>'snaming'), IGNORE_MULTIPLE);
    //get the record for the request
    $rec =  $DB->get_record('block_cmanager_records', array('id'=>$mid));

    // Build up a course record based on the request.
    
    if(empty($rec->cate)){
		$new_course->category = $CFG->defaultrequestcategory; 
	} else {
			$new_course->category = $rec->cate;
	}
	
	
		
	
	// Fields we are carrying across
	if ($mode == "enabled" && $snaming==2){
		$newShortName = $rec->modcode . ' - ' . $rec->modmode;
	}else{
		$newShortName = $rec->modcode;
	}
	

	
	$new_course->shortname = $newShortName;
	
	
	
	$p_key = $rec->modkey;
	
	//course naming
	if ($naming == 1) {
		$new_course->fullname = $rec->modname;
	}
	else if ($naming ==2) {
		$new_course->fullname = $rec->modcode . ' - '. $rec->modname;	
	}
	else if ($naming == 3) {
		$new_course->fullname = $rec->modname . ' ('. $rec->modcode . ')'; // Fullname, shortname
	}
	else if ($naming == 4) {
		$new_course->fullname = $rec->modcode . ' - '. $rec->modname . ' ('.date("Y").')';	// Shortname, fullname (year)
	}
	else if ($naming == 5) {
		$new_course->fullname = $rec->modname . ' ('.date("Y").')';
	}
	
	// Enrollment key
	// if the key thats been set, otherwise auto gen a key
	if (isset($rec->modkey)) {
		$modkey = $rec->modkey;
	} else{
		$modkey = rand(999,5000);	
	}
	
	
    $categoryid = $new_course->category;
    $category = $DB->get_record('course_categories', array('id'=>$categoryid));
    $catcontext = context_coursecat::instance($category->id);
    $contextobject = $catcontext;
    $editoroptions = array('maxfiles' => EDITOR_UNLIMITED_FILES, 
    					   'maxbytes'=>$CFG->maxbytes, 'trusttext'=>false, 
    					   'noclean'=>true, 'content'=>$contextobject);
	
	// Create the course
	$course = create_course($new_course, $editoroptions);
	   
	// Forward to the course editing page to allow the admin to make
	// any changes to the course
	$nid = $course->id;
	
	if ($nid == null) {
		return $nid;
	}
	 
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
	

	// Check to see if auto create enrollment keys
	// is enabled. If this option is set, add an 
	// enrollment key.
	$autoKey = $DB->get_field_select('block_cmanager_config', 'value', "varname = 'autoKey'");
	
	if ($autoKey == 0 || $autoKey == 1){

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
			
			

        if ($CFG->version >= 2013051400) {
        	$enrollmentRecord->customint5 = NULL;
        	$enrollmentRecord->customint6 = 1;
        }
        $enrollmentRecord->customchar1 = NULL;
        $enrollmentRecord->customchar2 = NULL;
        $enrollmentRecord->customdec1 = NULL;
        $enrollmentRecord->customdec2 = NULL;
        $enrollmentRecord->customtext1 = '';
        $enrollmentRecord->customtext2 = NULL;
        $enrollmentRecord->timecreated = time();
        $enrollmentRecord->timemodified = time();

        $DB->insert_record('enrol', $enrollmentRecord);
	} 
	
	
	if ($sendMail == true) {
        block_cmanager_send_emails($nid, $new_course->shortname, $new_course->fullname, $modkey, $mid);
	}
	
	return $nid;
	
	
	
	
}


/**
 * Send emails to everyone that is related to this module.
 * 
 */
function block_cmanager_send_emails($courseid, $modcode, $modname, $modkey, $mid){

	global $USER, $CFG, $DB;


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
		block_cmanager_new_course_approved_mail_user($user_ids, $replaceValues);
		//mail the admin
		block_cmanager_new_course_approved_mail_admin($replaceValues);
  
}
