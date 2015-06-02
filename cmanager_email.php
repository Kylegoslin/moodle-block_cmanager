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

// require cfg was here
require_once($CFG->dirroot . "/lib/moodlelib.php");
require_once('lib.php');

global $DB;
$senderemailaddress = $DB->get_field('block_cmanager_config', 'value', array("varname"=>'emailsender'), IGNORE_MULTIPLE);

$emailsender = new stdClass();	
$emailsender->id = 1;
$emailsender->email = $senderemailaddress;
$emailsender->maildisplay = true;


/**
 * Preform a search and replace for any value tags
 * which were entered by the admin.
 * 
 */
function block_cmanager_convert_tags_to_values($email, $replacevalues) {


    //Course code: [course_code]
    $course_code_added = str_replace('[course_code]', $replacevalues['[course_code'], $email);

    // Course name: [course_name]
    $course_name_added = str_replace('[course_name]', $replacevalues['[course_name]'], $course_code_added);

    // Enrolment key: [e_key]
    $enroll_key_added = str_replace('[e_key]',  $replacevalues['[e_key]'], $course_name_added);

    // Full URL to module: [full_link]
    $full_url_added = 	str_replace('[full_link]',  $replacevalues['[full_link]'], $enroll_key_added);

    $req_link_added = str_replace('[req_link]',  $replacevalues['[req_link]'], $full_url_added);

    // Location in catalog: [loc]
    $location_added = str_replace('[loc]',  $replacevalues['[loc]'], $req_link_added);
    
    $new_email = $location_added;

    return $new_email;
	
}


/**
 * When a new course is approved email the user
 * 
 * 
 */
function block_cmanager_new_course_approved_mail_user($uids, $current_mod_info) {

    global $USER;
    global $CFG;
    global $DB;
    global $senderemailaddress;

    $uidarray = explode(' ', $uids);

    foreach ($uidarray as $singleid) {
        $emailinguserobject = $DB->get_record('user', array('id'=>$singleid));
        $subject = get_string('emailSubj_userApproved','block_cmanager');
        $rec = $DB->get_record('block_cmanager_config', array('varname'=>'approveduseremail'));

        if (strlen(trim($rec->value)) > 0){//are there characters in the field.
            $messagetext = block_cmanager_convert_tags_to_values($rec->value, $current_mod_info);
            email_to_user($emailinguserobject, $senderemailaddress, $subject, $messagetext, $messagehtml='', $attachment='', 
            $attachname='', true, $replyto='', $replytoname='', $wordwrapwidth=79);
        }
}
	

} //function




/**
 *   When a new course is approved, email the admin(s)
 * 
 * 
 */
function block_cmanager_new_course_approved_mail_admin($current_mod_info) {

global $USER, $CFG, $emailsender, $senderemailaddress, $DB;


    // Get each admin email
    $wherequery = "varname = 'admin_email'";
    $modrecords = $DB->get_recordset_select('block_cmanager_config', $wherequery);

    $admin_email = $DB->get_field('block_cmanager_config', 'value',array('varname'=>'approvedadminemail') , IGNORE_MULTIPLE);	    

    if (strlen(trim($admin_email)) > 0) {//are there characters in the field.
        $messagetext = block_cmanager_convert_tags_to_values($admin_email, $current_mod_info);
		// Send an email to each admin		                               
        foreach ($modrecords as $rec) {			                               
            $to = $rec->value;
            $from = $emailsender;
            $subject = get_string('emailSubj_adminApproved','block_cmanager');


            block_cmanager_send_email_to_address($to, $subject, $messagetext);
        }//end for loop
    }//end if
}//end function




/**
 *  Requesting a new module, email admin(s)
 * 
 */
function block_cmanager_request_new_mod_email_admins($current_mod_info){


    global $USER, $CFG, $emailsender, $DB, $senderemailaddress;


    // Get each admin email
	$wherequery = "varname = 'admin_email'";
    $modrecords = $DB->get_records_select('block_cmanager_config', $wherequery);									   
    $admin_email = $DB->get_record('block_cmanager_config', array('varname'=>'requestnewmoduleadmin'));	
	
    if (strlen(trim($admin_email->value)) > 0){//are there characters in the field.
        $messagetext = block_cmanager_convert_tags_to_values($admin_email->value, $current_mod_info);
        // Send an email to each admin		                               
        foreach ($modrecords as $rec) {			                               
	        $to = $rec->value;
            //$from = $senderemailaddress;
            $subject = get_string('emailSubj_adminNewRequest','block_cmanager');

            block_cmanager_send_email_to_address($to, $subject, $messagetext);						 
        }//end for
    }//end if
	
}//end function


/**
 * Requesting a new module, email user
 * 
 * 
 */
function block_cmanager_request_new_mod_email_user($uid, $current_mod_info){

    global $emailsender,$senderemailaddress, $DB;

    $emailinguserobject = $DB->get_record('user', array('id'=>$uid));
    $subject = get_string('emailSubj_userNewRequest','block_cmanager');
    $user_email_message = $DB->get_record('block_cmanager_config', array('varname'=>'requestnewmoduleuser'));	

    if (strlen(trim($user_email_message->value)) > 0) {//are there characters in the field.
        $messagetext = block_cmanager_convert_tags_to_values($user_email_message->value, $current_mod_info);
        email_to_user($emailinguserobject, $emailsender, $subject, $messagetext, $messagehtml='', $attachment='', 
        $attachname='', true, $replyto='', $replytoname='', $wordwrapwidth=79);
    }//end if
}//end function


/**
 * 
 * 
 *  Send an email out to an address external to anything
 *  to do with Moodle.
 * */
function block_cmanager_send_email_to_Address($to, $subject, $text){
	
	global $emailsender, $CFG, $DB, $senderemailaddress;
	
	$emailinguserobject = new stdClass();	
	$emailinguserobject->id = 1;
	$emailinguserobject->email = $to;
	$emailinguserobject->maildisplay = true;

	email_to_user($emailinguserobject, $senderemailaddress, $subject, $text, $messagehtml='', $attachment='', 
				  $attachname='', true, $replyto='', $replytoname='', $wordwrapwidth=79);
	
}



/**
* Email a comment out to a user
*/
function block_cmanager_email_comment_to_user($message, $uid, $mid, $current_mod_info){

	global $USER, $CFG, $emailsender, $DB;

    $emailinguserobject = $DB->get_record('user', array('id'=>$uid));
    $commentForUser = $DB->get_field('block_cmanager_config', 'value',array('varname'=>'commentemailuser') , IGNORE_MULTIPLE);	    
		
	if (strlen(trim($commentForUser)) > 0) {//are there characters in the field.
	    $additionalSignature = block_cmanager_convert_tags_to_values($commentForUser, $current_mod_info);
		$from = $emailsender;
		$subject = get_string('emailSubj_userNewComment','block_cmanager');
		$messagetext = get_string('emailSubj_Comment','block_cmanager') . ":
										
$message
					
$additionalSignature
";

	email_to_user($emailinguserobject, $from, $subject, $messagetext, $messagehtml='', $attachment='', 
	              $attachname='', true, $replyto='', $replytoname='', $wordwrapwidth=79);

		}//end if
}


/**
* Email a comment to an admin
*/
function block_cmanager_email_comment_to_admin($message, $mid, $current_mod_info) {

	global $USER, $CFG, $emailsender, $DB;

    // Get each admin email
 	$adminEmailAddresses = $DB->get_recordset_select('block_cmanager_config', "varname = 'admin_email'");
	// Comment for admin
	$commentForAdmin = $DB->get_field('block_cmanager_config', 'value',array('varname'=>'commentemailadmin') , IGNORE_MULTIPLE);
	
    if (strlen(trim($commentForAdmin)) > 0) {//are there characters in the field.
	    $additionalSignature = block_cmanager_convert_tags_to_values($commentForAdmin, $current_mod_info);
		
	    // Send an email to each admin		                               
        foreach ($adminEmailAddresses as $rec) {			                               
		    $to = $rec->value;
	        $from = $emailsender;
	    	$subject = get_string('emailSubj_adminNewComment','block_cmanager');
		    $messagetext = get_string('emailSubj_Comment','block_cmanager')."
										
$message
					
$additionalSignature
";
		
		//$headers = get_string('emailSubj_From','block_cmanager') . $from;
		
		
			 
		block_cmanager_send_email_to_address($to, $subject, $messagetext);
	 }//end for
		
     }//end if

}//end fuction
		


/**
 * When a module has been denied, send an email
 * to the admin.
 * 
 * 
 */
function block_cmanager_send_deny_email_admin($message, $mid, $current_mod_info){


    global $USER, $CFG, $emailsender, $DB;

    // Get each admin email
    $modrecords = $DB->get_records('block_cmanager_config', array('varname'=>'admin_email'));

    $admin_email = $DB->get_record('block_cmanager_config', array('varname'=>'modulerequestdeniedadmin'));
    if (strlen(trim($admin_email->value)) > 0){//are there characters in the field.

    // Send an email to each admin		                               
    foreach ($modrecords as $rec) {			                                  		                               
        $to = $rec->value;
          
        $from = $emailsender;
        $subject = get_string('emailSubj_adminDeny','block_cmanager');
            		
        $messagetext = $message;
        $messagetext .= '';
        
        $messagetext .= block_cmanager_convert_tags_to_values($admin_email->value, $current_mod_info);
        block_cmanager_send_email_to_address($to, $subject, $messagetext);
    	
     }//end loop

    }//end if

}//end function




/**
 * Once a module has been denied, send an email to
 * the user.
 * 
 */
function block_cmanager_send_deny_email_user($message, $userid, $mid, $current_mod_info){

    global $USER, $CFG, $emailsender, $DB;


    $emailinguserobject = $DB->get_record('user', array('id'=>$userid));
    $from = $emailsender;
    $subject = get_string('emailSubj_userDeny','block_cmanager');
    $user_email = $DB->get_record('block_cmanager_config', array('varname'=>'modulerequestdenieduser'));	

    if (strlen(trim($user_email->value)) > 0) {//are there characters in the field.	
        $messagetext = $message;
        $messagetext .= '';
        $messagetext .= block_cmanager_convert_tags_to_values($user_email->value, $current_mod_info);
        email_to_user($emailinguserobject, $from, $subject, $messagetext, $messagehtml= '', $attachment='', 
        $attachname='', true, $replyto='', $replytoname='', $wordwrapwidth=79);
    }

}



/**
 * When a lecturer requests control of a module.
 * 
 * 
 */
function block_cmanager_handover_email_lecturers($course_id, $currentUserId, $custommessage){

    
    global $USER, $CFG, $emailsender, $DB;
    $teacher_ids = '';


    // Send an email to the module owner
    // Get a list of all the lecturers
    if (! $course = $DB->get_record("course", array('id'=>$course_id))) {
        error("That's an invalid course id");
    }

    // Get the teacher ids
    $teacher_ids = block_cmanager_get_lecturer_ids_space_sep($course_id);


    // Collect info on the person who made the request
    $requester = $DB->get_record('user', array('id'=>$currentUserId));
    $requester_email = $requester->email; 

    $teacher_ids;
    $assignedlectureremails = '';

    // for each teacher id, email them
    $idarray = explode(" ", $teacher_ids);


    //****** Email each of the people who are associated with the course ******     
    $admin_email = $DB->get_record('block_cmanager_config', array('varname'=>'handoveruser'));

    if (strlen(trim($admin_email->value)) > 0) {//are there characters in the field.	
        $custom_sig = $admin_email->value;	
        foreach ($idarray as $single_id) {
            $emailinguserobject = $DB->get_record('user', array('id'=>$single_id));
            $assignedlectureremails .= ' ' . $emailinguserobject->email;
            $from = $emailsender;
            $subject = get_string('emailSubj_teacherHandover','block_cmanager');

			    $messagetext = "
			    
			    
			    " .get_string('emailSubj_pleasecontact','block_cmanager').
": $requester_email 

" . $custommessage . "
". $custom_sig;
				
	           email_to_user($emailinguserobject, $from, $subject, $messagetext, $messagehtml='', $attachment='', 
  			                 $attachname='', $usetrueaddress=true, $replyto='', $replytoname='', $wordwrapwidth=79);
	
}
	}//end if
        
    	    
        
   	//***** Email the person who made the request  	
   $current_user_emailinguserobject = $DB->get_record('user', array('id'=>$USER->id));
   $admin_email = $DB->get_record('block_cmanager_config', array('varname'=>'handovercurrent'));	
			
	if (strlen(trim($admin_email->value)) > 0) {//are there characters in the field.		
		$custom_sig = $admin_email->value;	
        $from = $emailsender;
    $subject = get_string('emailSubj_teacherHandover','block_cmanager');
    $messagetext = "
		
".get_string('emailSubj_mailSent1','block_cmanager').":  ". $assignedlectureremails ."
							
$custommessage							
		
$custom_sig
							";
	
	email_to_user($current_user_emailinguserobject, $from, $subject, $messagetext, $messagehtml='', $attachment='', 
				  $attachname='', $usetrueaddress=true, $replyto='', $replytoname='', $wordwrapwidth=79);

}//end if
        
				       
	/******** Send an email to each admin ******************/
	
    $wherequery = "varname = 'admin_email'";
 	$modrecords = $DB->get_recordset_select('block_cmanager_config', $wherequery);

    foreach ($modrecords as $rec) {			                               
        $to = $rec->value;
        $from = $emailsender;
        $subject = get_string('emailSubj_teacherHandover','block_cmanager');

        $admin_email = $DB->get_record('block_cmanager_config', array('varname'=>'handoveradmin'));

        if (strlen(trim($admin_email->value)) > 0) {//are there characters in the field.	
            $custom_sig = $admin_email->value;	
            $messagetext = '';
            $messagetext .= '

';
			 
				$messagetext .= "
$custommessage
								
		".get_string('emailSubj_teacherHandover','block_cmanager').": $requester_email
			
$custom_sig
								";
				
            $headers = get_string('emailSubj_From','block_cmanager') . $from;
            $userobj;
            $userobj->email = $to;
            			
            block_cmanager_send_email_to_address($to, $subject, $messagetext);
		
		
		}//end loop
		
     }//end if
					    					       
}//end function



