<?php
/*
 *  Course Request Manager
 *  2012-2013
 * 
 *  Kyle Goslin, Daniel McSweeney
 *  
 * 
 * 
 * 
 * 
 * 
 * */



/*
 * Return HTML displaying the names of lecturers
 * 
 */
function getLecturerInfo($courseId){
							
						
	global $DB, $CFG;
	
					
	if (! $course = $DB->get_record("course", array("id"=>$courseId))) {
		echo 'Error: invalid course id';
		die;
	}
	

	$contextId = $DB->get_field('context', 'id', array ('instanceid'=>$courseId, 'contextlevel'=>50), $strictness=IGNORE_MULTIPLE);
	$userIds = $DB->get_records('role_assignments',array('roleid' => '3' , 'contextid' => $contextId));


	$lecturerHTML = '';

	foreach($userIds as $singleUser){
	
		$user_record = $DB->get_record('user', array('id'=>$singleUser->userid), $fields='*', $strictness=IGNORE_MULTIPLE);	
		$lecturerHTML .=  $user_record->firstname . ' ' . $user_record->lastname . '<br>' ;
	}	


	
	return $lecturerHTML;
	
}


/*
 * Get a collection of teacher ids (role 3)
 *  
 * for a specific course, separated by spaces.
 */
function getLecturerIdsSpaceSep($courseId){
							
						
	global $DB, $CFG;
	
					
	if (! $course = $DB->get_record("course", array("id"=>$courseId))) {
		echo 'Error: invalid course id';
		die;
	}
	

	$contextId = $DB->get_field('context', 'id', array ('instanceid'=>$courseId, 'contextlevel'=>50), $strictness=IGNORE_MULTIPLE);
	$userIds = $DB->get_records('role_assignments',array('roleid' => '3' , 'contextid' => $contextId));


	$lecturerHTML = '';

	foreach($userIds as $singleUser){
	
		$user_record = $DB->get_record('user', array('id'=>$singleUser->userid), $fields='*', $strictness=IGNORE_MULTIPLE);	
		$lecturerHTML .=  $user_record->id . ' ' ;
	}	


	
	return $lecturerHTML;
	
}


/*
 * 
 * 
 * Return a list of admin emails for a course, separated
 * by a comma.
 * */
 function getListOfLecturerEmails($courseId){
 	
	global $DB, $CFG;
	
					
	if (! $course = $DB->get_record("course", array("id"=>$courseId))) {
		echo 'Error: invalid course id';
		die;
	}
	

	$contextId = $DB->get_field('context', 'id', array ('instanceid'=>$courseId, 'contextlevel'=>50), $strictness=IGNORE_MULTIPLE);
	$userIds = $DB->get_records('role_assignments',array('roleid' => '3' , 'contextid' => $contextId));


	$lecturerInfo = '';

	foreach($userIds as $singleUser){
	
		$user_record = $DB->get_record('user', array('id'=>$singleUser->userid), $fields='*', $strictness=IGNORE_MULTIPLE);	
		$lecturerInfo .=  $user_record->email . ', ' ;
	}	


	
	return $lecturerInfo;
 }
