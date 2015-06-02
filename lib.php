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

/**
 * Return HTML displaying the names of lecturers
 * 
 */
function block_cmanager_get_lecturer_info($courseid){
							
global $DB, $CFG;

			
if (! $course = $DB->get_record("course", array("id"=>$courseid))) {
	echo 'Error: invalid course id';
	die;
}


$contextid = $DB->get_field('context', 'id', array ('instanceid'=>$courseid, 'contextlevel'=>50), $strictness=IGNORE_MULTIPLE);
$userids = $DB->get_records('role_assignments',array('roleid' => '3' , 'contextid' => $contextid));


$lecturerhtml = '';

foreach ($userids as $singleuser) {
	$user_record = $DB->get_record('user', array('id'=>$singleuser->userid), $fields='*', $strictness=IGNORE_MULTIPLE);	
	$lecturerhtml .=  $user_record->firstname . ' ' . $user_record->lastname . '<br>' ;
}	


return $lecturerhtml;

}


/**
 * Get a collection of teacher ids (role 3)
 *  
 * for a specific course, separated by spaces.
 */
function block_cmanager_get_lecturer_ids_space_sep($courseid) {
							
    global $DB, $CFG;
	
					
    if (! $course = $DB->get_record("course", array("id"=>$courseid))) {
        echo 'Error: invalid course id';
	    die;
	}
	
	$contextid = $DB->get_field('context', 'id', array ('instanceid'=>$courseid, 'contextlevel'=>50), $strictness=IGNORE_MULTIPLE);
	$userids = $DB->get_records('role_assignments',array('roleid' => '3' , 'contextid' => $contextid));


	$lecturerhtml = '';

	foreach ($userids as $singleuser) {
	    $user_record = $DB->get_record('user', array('id'=>$singleuser->userid), $fields='*', $strictness=IGNORE_MULTIPLE);	
	    $lecturerhtml .=  $user_record->id . ' ' ;
    }	

    return $lecturerhtml;
	
}


/**
 * 
 * 
 * Return a list of admin emails for a course, separated
 * by a comma.
 * */
function block_cmanager_get_list_of_lecturer_emails($courseid) {
 	
	global $DB, $CFG;
					
	if (! $course = $DB->get_record("course", array("id"=>$courseid))) {
        echo 'Error: invalid course id';
        die;
	}


    $contextid = $DB->get_field('context', 'id', array ('instanceid'=>$courseid, 'contextlevel'=>50), $strictness=IGNORE_MULTIPLE);
    $userids = $DB->get_records('role_assignments',array('roleid' => '3' , 'contextid' => $contextid));


    $lecturerinfo = '';
	
    foreach ($userids as $singleuser) {
	    $user_record = $DB->get_record('user', array('id'=>$singleuser->userid), $fields='*', $strictness=IGNORE_MULTIPLE);	
	    $lecturerinfo .=  $user_record->email . ', ' ;
    }	

    return $lecturerinfo;
 }
