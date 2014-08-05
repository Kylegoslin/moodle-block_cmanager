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
