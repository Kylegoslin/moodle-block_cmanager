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

require_once("../../config.php");
global $CFG, $DB;


$type = $_POST['type'];
	

if ($type == 'del') {
    $values = $_POST['values'];
    foreach ($values as $id) {
        if ($id != 'null') {
		    $DB->delete_records('block_cmanager_records', array('id'=>$id));
		    // Delete associated comments
		    $DB->delete_records('block_cmanager_comments', array('instanceid'=>$id));
		    }
    }
}


/**
 * Update the values for emails.
 * 
 * 
 */
if ($type == 'updatefield') {
     
    $post_value = addslashes($_POST['value']);
    $post_id = addslashes($_POST['id']);
  	
    $selectQuery = "varname = '$post_id'";
  	$recordExists = $DB->record_exists_select('block_cmanager_config', $selectQuery);
  	 
  	 
  	 if ($recordExists) {
         // If the record exists
  	     $current_record =  $DB->get_record('block_cmanager_config', array('varname'=>$post_id));
  	     $newrec = new stdClass();
	     $newrec->id = $current_record->id;
	     $newrec->varname = $post_id;
	     $newrec->value = $post_value;
  	     $DB->update_record('block_cmanager_config', $newrec); 
  	     
  	     echo "updated";
  	     
  	 } else {
          $newrec = new stdClass();
	      $newrec->varname = $post_id;
	      $newrec->value = $post_value;
  	      $DB->insert_record('block_cmanager_config', $newrec); 
  	      echo "inserted";
    }
  	 
}

if ($type == 'updatecategory') {
      $value = $_POST['value'];
	  $recId = $_POST['recId'];
	  $newrec = new stdClass();
	  $newrec->id = $recId;
	  $newrec->cate = $value;
	  $DB->update_record('block_cmanager_records', $newrec); 
}


