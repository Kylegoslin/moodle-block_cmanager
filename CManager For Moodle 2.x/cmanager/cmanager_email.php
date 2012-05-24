<?php
/* --------------------------------------------------------- 

     COURSE REQUEST BLOCK FOR MOODLE  

     2012 Kyle Goslin & Daniel McSweeney
     Institute of Technology Blanchardstown
     Dublin 15, Ireland
 --------------------------------------------------------- */

//get the default email address from config




// require cfg was here
require_once($CFG->dirroot . "/lib/moodlelib.php");
global $DB;
$emailSender = $DB->get_field('cmanager_config', 'value', array("varname"=>'emailSender'), IGNORE_MULTIPLE);



/*
 * Preform a search and replace for any value tags
 * which were entered by the admin.
 * 
 */
function convertTagsToValues($email, $replaceValues){


    //Course code: [course_code]
	$course_code_added = str_replace('[course_code]', $replaceValues['[course_code'], $email);

	// Course name: [course_name]
	$course_name_added = str_replace('[course_name]', $replaceValues['[course_name]'], $course_code_added);
	
    // Enrolment key: [e_key]
	$enroll_key_added = str_replace('[e_key]',  $replaceValues['[e_key]'], $course_name_added);
	
    // Full URL to module: [full_link]
	$full_url_added = 	str_replace('[full_link]',  $replaceValues['[full_link]'], $enroll_key_added);
	
	$req_link_added = str_replace('[req_link]',  $replaceValues['[req_link]'], $full_url_added);
	
    // Location in catalog: [loc]
	$location_added = str_replace('[loc]',  $replaceValues['[loc]'], $req_link_added);
	
	
	$new_email = $location_added;
	
	return $new_email;
	
}


/*
 * When a new course is approved email the user
 * 
 * 
 */
function new_course_approved_mail_user($uids, $current_mod_info){

	global $USER;
    global $CFG;
	global $emailSender, $DB;

	


	$uidArray = explode(' ', $uids);
	foreach($uidArray as $singleid){
		
		

	
		$emailingUserObject = $DB->get_record('user', array('id'=>$singleid));



		$from = $emailSender;
		$subject = get_string('emailSubj_userApproved','block_cmanager');

		$rec = $DB->get_record('cmanager_config', array('varname'=>'approveduseremail'));
		
		if (strlen(trim($rec->value)) > 0){//are there characters in the field.
		
		$messagetext = convertTagsToValues($rec->value, $current_mod_info);
		
		
		email_to_user($emailingUserObject, $from, $subject, $messagetext, $messagehtml='', $attachment='', 
				       $attachname='', $usetrueaddress=true, $replyto='', $replytoname='', $wordwrapwidth=79);
	
		
		}

	}
	
	
	

					


} //function




/*
 *   When a new course is approved, email the admin(s)
 * 
 * 
 */
function new_course_approved_mail_admin($current_mod_info){

	global $USER, $CFG, $emailSender, $DB;


    // Get each admin email
	$whereQuery = "varname = 'admin_email'";
 	$modRecords = $DB->get_recordset_select('cmanager_config', $whereQuery);
	
	
	$admin_email = $DB->get_field('cmanager_config', 'value',array('varname'=>'approvedadminemail') , IGNORE_MULTIPLE);	    
		
	
	
	
	if (strlen(trim($admin_email)) > 0){//are there characters in the field.
	
	
	
		$messagetext = convertTagsToValues($admin_email, $current_mod_info);
												   
		// Send an email to each admin		                               
		 foreach($modRecords as $rec){			                               
											   
			$to = $rec->value;
		
	 
			$from = $emailSender;
			$subject = get_string('emailSubj_adminApproved','block_cmanager');
		
			$userobj;
			$userobj->email = $to;
			
			email_to_user($userobj, $from, $subject, $messagetext, $messagehtml='', $attachment='', 
						   $attachname='', $usetrueaddress=true, $replyto='', $replytoname='', $wordwrapwidth=79);
		
	 	}//end for loop
     }//end if
}//end function




/*
 *  Requesting a new module, email admin(s)
 * 
 */
function request_new_mod_email_admins($current_mod_info){


	global $USER, $CFG, $emailSender, $DB;


    // Get each admin email
	$whereQuery = "varname = 'admin_email'";
 	//$modRecords = $DB->get_recordset_select('cmanager_config', $whereQuery, $sort='', $fields='*', 
	//		                               $limitfrom='', $limitnum='');
										   
	$modRecords = $DB->get_records_select('cmanager_config', $whereQuery);									   


		                             
	$admin_email = $DB->get_record('cmanager_config', array('varname'=>'requestnewmoduleadmin'));	
	
  
	if (strlen(trim($admin_email->value)) > 0){//are there characters in the field.
	
		                               
		$messagetext = convertTagsToValues($admin_email->value, $current_mod_info);
	 
		
		// Send an email to each admin		                               
		 foreach($modRecords as $rec){			                               
											   
			$to = $rec->value;
		
			$from = $emailSender;
			$subject = get_string('emailSubj_adminNewRequest','block_cmanager');
	
			$headers = get_string('emailSubj_From','block_cmanager') . $from;
			//mail($to,$subject,$messagetext,$headers);
			
			$userobj;
			$userobj->email = $to;
			
			//email_to_user($userobj, $from, $subject, $messagetext, $messagehtml='', $attachment='', 
			//			   $attachname='', $usetrueaddress=true, $replyto='', $replytoname='', $wordwrapwidth=79);
								 
		 }//end for
	}//end if
	
	
	
}//end function


/*
 * Requesting a new module, email user
 * 
 * 
 */
function request_new_mod_email_user($uid, $current_mod_info){

		global $emailSender, $DB;

		$emailingUserObject = $DB->get_record('user', array('id'=>$uid));

		$from = $emailSender;
		$subject = get_string('emailSubj_userNewRequest','block_cmanager');
	
		$user_email = $DB->get_record('cmanager_config', array('varname'=>'requestnewmoduleuser'));	
		
		if (strlen(trim($user_email->value)) > 0){//are there characters in the field.
			    
			$messagetext = convertTagsToValues($user_email->value, $current_mod_info);
		
			//email_to_user($emailingUserObject, $from, $subject, $messagetext, $messagehtml='', $attachment='', 
			//	       $attachname='', $usetrueaddress=true, $replyto='', $replytoname='', $wordwrapwidth=79);
		}//end if
		
}//end function



function email_comment_to_user($message, $uid, $mid, $current_mod_info){

	global $USER, $CFG, $emailSender, $DB;


		$emailingUserObject = $DB->get_record('user', array('id'=>$uid), IGNORE_MULTIPLE);
		
		$user_email = $DB->get_field('cmanager_config', 'value',array('varname'=>'commentemailuser') , IGNORE_MULTIPLE);	    
		
		if (strlen(trim($user_email)) > 0){//are there characters in the field.
		
		$additionalSignature = convertTagsToValues($user_email, $current_mod_info);
		

		$from = $emailSender;
		$subject = get_string('emailSubj_userNewComment','block_cmanager');
		$messagetext = get_string('emailSubj_Comment','block_cmanager') . ":
										
$message
					
$additionalSignature
";

		email_to_user($emailingUserObject, $from, $subject, $messagetext, $messagehtml='', $attachment='', 
				       $attachname='', $usetrueaddress=true, $replyto='', $replytoname='', $wordwrapwidth=79);


		}//end if

}



function email_comment_to_admin($message, $mid, $current_mod_info) {

	global $USER, $CFG, $emailSender, $DB;



    // Get each admin email
	$whereQuery = "varname = 'admin_email'";
 	$modRecords = $DB->get_recordset_select('cmanager_config', $whereQuery);

    //$admin_email = $DB->get_record('cmanager_config', array('varname'=>'commentemailadmin'));
	$admin_email = $DB->get_field('cmanager_config', 'value',array('varname'=>'commentemailadmin') , IGNORE_MULTIPLE);
	
	
	if (strlen(trim($admin_email)) > 0){//are there characters in the field.
	
			
	$additionalSignature = convertTagsToValues($admin_email, $current_mod_info);
		
	// Send an email to each admin		                               
     foreach($modRecords as $rec){			                               
			                               
		$to = $rec->value;
	
		$from = $emailSender;
		$subject = get_string('emailSubj_adminNewComment','block_cmanager');
		
				$messagetext = get_string('emailSubj_Comment','block_cmanager').":
										
$message
					
$additionalSignature
";
		
		$headers = get_string('emailSubj_From','block_cmanager') . $from;
		//mail($to,$subject,$messagetext,$headers);
		
		
		
		$userobj;
		$userobj->email = $to;
		
		email_to_user($userobj, $from, $subject, $messagetext, $messagehtml='', $attachment='', 
				       $attachname='', $usetrueaddress=true, $replyto='', $replytoname='', $wordwrapwidth=79);
	 }//end for
		
     }//end if

}//end fuction
		


/*
 * When a module has been denied, send an email
 * to the admin.
 * 
 * 
 */
function send_deny_email_admin($message, $mid, $current_mod_info){


	global $USER, $CFG, $emailSender, $DB;

    // Get each admin email
 	$modRecords = $DB->get_records('cmanager_config', array('varname'=>'admin_email'));

	$admin_email = $DB->get_record('cmanager_config', array('varname'=>'modulerequestdeniedadmin'));
	if (strlen(trim($admin_email->value)) > 0){//are there characters in the field.
	
	// Send an email to each admin		                               
     foreach($modRecords as $rec){			                               
			                               
		$to = $rec->value;
	
		$from = $emailSender;
		$subject = get_string('emailSubj_adminDeny','block_cmanager');
	    		
	    $messagetext = $message;
	    $messagetext .= '
	    ';
	    
	    $messagetext .= convertTagsToValues($admin_email->value, $current_mod_info);

		$headers = get_string('emailSubj_From','block_cmanager') . $from;
		//mail($to,$subject,$messagetext,$headers);
		
		$userobj;
		$userobj->email = $to;
		
		email_to_user($userobj, $from, $subject, $messagetext, $messagehtml='', $attachment='', 
				       $attachname='', $usetrueaddress=true, $replyto='', $replytoname='', $wordwrapwidth=79);
		
     }//end loop
	
	}//end if

}//end function




/*
 * Once a module has been denied, send an email to
 * the user.
 * 
 */
function send_deny_email_user($message, $userid, $mid, $current_mod_info){

global $USER, $CFG, $emailSender, $DB;


	$emailingUserObject = $DB->get_record('user', array('id'=>$userid));



		$from = $emailSender;
		$subject = get_string('emailSubj_userDeny','block_cmanager');
		

		
		$user_email = $DB->get_record('cmanager_config', array('varname'=>'modulerequestdenieduser'));	
		
		if (strlen(trim($user_email->value)) > 0){//are there characters in the field.	
		
		$messagetext = $message;
		$messagetext .= '
	    
	    ';
	    
		$messagetext .= convertTagsToValues($user_email->value, $current_mod_info);

		

		email_to_user($emailingUserObject, $from, $subject, $messagetext, $messagehtml='', $attachment='', 
				       $attachname='', $usetrueaddress=true, $replyto='', $replytoname='', $wordwrapwidth=79);
		}

}



/*
 * When a lecturer requests control of a module.
 * 
 * 
 */
function handover_email_lecturers($course_id, $currentUserId, $custommessage){


global $USER, $CFG, $emailSender, $DB;

  $teacher_ids = '';


	// Send an email to the module owner
	// Get a list of all the lecturers
	if (! $course = $DB->get_record("course", array('id'=>$course_id))) {
		    error("That's an invalid course id");
	}
	    

	    $context = get_context_instance(CONTEXT_COURSE, $course->id); 
	    if ($managerroles = get_config('', 'coursemanager')) {
		$coursemanagerroles = explode(',', $managerroles);
		foreach ($coursemanagerroles as $roleid) {
		    $role = $DB->get_record('role',array('id'=>$roleid));
		    $canseehidden = has_capability('moodle/role:viewhiddenassigns', $context);
		    $roleid = (int) $roleid;
		    $namesarray = null;
		    if ($users = get_role_users($roleid, $context, true, '', 'u.lastname ASC', $canseehidden)) {
		        
			    foreach ($users as $teacher) {
		            $fullname = fullname($teacher, has_capability('moodle/site:viewfullnames', $context)); 
		            $namesarray[] = format_string(role_get_name($role, $context)).': <a href="'.$CFG->wwwroot.'/user/view.php?id='.
		                            $teacher->id.'&amp;course='.SITEID.'">'.$fullname.'</a>';
		                            $teacher_ids .= ' ' . $teacher->id;
		        }
		    }          
		}
		if (!empty($namesarray)) {
		    $lecturerHTML =  implode('<br>', $namesarray);
		   
		} else {
			$lecturerHTML = '&nbsp;';
		}
	    }

	    
	    
        $requester = $DB->get_record('user', array('id'=>$currentUserId));
	    $requester_email = $requester->email; 
	    
        // for each teacher id, email them
        $idarray = explode(" ", $teacher_ids);
	    
	        
        $admin_email = $DB->get_record('cmanager_config', array('varname'=>'handoveruser'));
		if (strlen(trim($admin_email->value)) > 0){//are there characters in the field.	
				
			//$custom_sig = convertTagsToValues($admin_email->value, $current_mod_info);
			$custom_sig = $admin_email->value;	
			
			foreach($idarray as $single_id){
				$emailingUserObject = $DB->get_record('user', array('id'=>$single_id));
				
				$from = $emailSender;
				$subject = get_string('emailSubj_teacherHandover','block_cmanager');
				$messagetext = "
	$custommessage".
							
	get_string('emailSubj_pleasecontact','block_cmanager').": $requester_email
		
	
	$custom_sig
	";
				
		
				email_to_user($emailingUserObject, $from, $subject, $messagetext, $messagehtml='', $attachment='', 
							   $attachname='', $usetrueaddress=true, $replyto='', $replytoname='', $wordwrapwidth=79);
	
			}
		}//end if
        
        $useremail = $emailingUserObject->email;
        
            // Email the person who made the request
        	global $USER;
        	
        	$current_user_emailingUserObject = $DB->get_record('user', array('id'=>$USER->id));
		    
        	
		    $admin_email = $DB->get_record('cmanager_config', array('varname'=>'handovercurrent'));	
			
			if (strlen(trim($admin_email->value)) > 0){//are there characters in the field.		
				
				$custom_sig = $admin_email->value;	
				$from = $emailSender;
				$subject = get_string('emailSubj_teacherHandover','block_cmanager');
				$messagetext = "
		
	$custommessage
							
	".get_string('emailSubj_mailSent1','block_cmanager').": $useremail ".get_string('emailSubj_mailSent2','block_cmanager')."
							
							
		
	$custom_sig
							";
	
				email_to_user($current_user_emailingUserObject, $from, $subject, $messagetext, $messagehtml='', $attachment='', 
							   $attachname='', $usetrueaddress=true, $replyto='', $replytoname='', $wordwrapwidth=79);

			}//end if
        
					       
	// Send an email to the admins

    // Get each admin email
	$whereQuery = "varname = 'admin_email'";
 	$modRecords = $DB->get_recordset_select('cmanager_config', $whereQuery);


	// Send an email to each admin		                               
     foreach($modRecords as $rec){			                               
			                               
		$to = $rec->value;
	
		$from = $emailSender;
		$subject = get_string('emailSubj_teacherHandover','block_cmanager');
		

	    $admin_email = $DB->get_record('cmanager_config', array('varname'=>'handoveradmin'));
		
		if (strlen(trim($admin_email->value)) > 0){//are there characters in the field.	
				
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
				//mail($to,$subject,$messagetext,$headers);
				
				$userobj;
				$userobj->email = $to;
				
				email_to_user($userobj, $from, $subject, $messagetext, $messagehtml='', $attachment='', 
							   $attachname='', $usetrueaddress=true, $replyto='', $replytoname='', $wordwrapwidth=79);
		
		
		}//end loop
		
     }//end if
					       
					       
					       
}//end function





?>
