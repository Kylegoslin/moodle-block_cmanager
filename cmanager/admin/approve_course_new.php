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

require_once("$CFG->libdir/formslib.php");
require_once('../../../course/lib.php');
require_once($CFG->libdir.'/completionlib.php');
require_once('../lib/course_lib.php');
require_login();

$PAGE->set_url('/blocks/cmanager/admin/approve_course_new.php');
$PAGE->set_context(context_system::instance());
$PAGE->set_title(get_string('pluginname', 'block_cmanager'));

$context = context_system::instance();
if (has_capability('block/cmanager:approverecord',$context)) {
} else {
  print_error(get_string('cannotapproverecord', 'block_cmanager'));
}


if(isset($_GET['id'])){
	$mid = required_param('id', PARAM_INT);
	$_SESSION['mid'] = $mid;
} else {

	$mid = $_SESSION['mid'];
}


	// Create the course by record ID      
	$nid = block_cmanager_create_new_course_by_record_id($mid, true);
	
	
  if(empty($nid)){
  	
	echo 'New Mod ID Not set';
	die;
	  
	  
  } else {
	
	echo '<script> window.location ="../../../course/edit.php?id=' .$nid . '";</script>';
  }



?>
