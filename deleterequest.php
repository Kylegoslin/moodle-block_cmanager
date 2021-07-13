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
// Copyright 2012-2018 - Institute of Technology Blanchardstown.
// --------------------------------------------------------- 
/**
 * COURSE REQUEST MANAGER
  *
 * @package    block_cmanager
 * @copyright  2014-2018 Kyle Goslin, Daniel McSweeney
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */



/*
 * Delete request.php
 * 
 * This page is called through AJAX to delete a specific
 * request and all associated comments.
 */
require_once("../../config.php");
global $CFG, $DB;

// check user has capability
$context = context_system::instance();
if (has_capability('block/cmanager:deleterecord',$context)) {
} else {
  print_error(get_string('cannotdelete', 'block_cmanager'));
}


$deleteId = required_param('id', PARAM_INT);    
$type = optional_param('t', '', PARAM_TEXT);    
	
// Delete the record
$deleteQuery = "id = $deleteId";
$DB->delete_records('block_cmanager_records', array('id'=>$deleteId));

// Delete associated comments
$res = $DB->delete_records('block_cmanager_comments', array('instanceid'=>$deleteId));

if($res){
    $event = \block_cmanager\event\course_deleted::create(array(
    'objectid' => '',
    'other' => get_string('courserecorddeleted','block_cmanager') . 'ID:' . $deleteId,
    'context' => $context,
    ));
    $event->trigger();
}

// redirect the browser back when finished deleting.
if ($type == 'a') {
	echo "<script>window.location='cmanager_admin.php';</script>";

} 
else if ($type =='adminarch') {
	echo "<script>window.location='cmanager_admin_arch.php';</script>";
}
else {
	echo "<script>window.location='module_manager.php';</script>";
}	
	


