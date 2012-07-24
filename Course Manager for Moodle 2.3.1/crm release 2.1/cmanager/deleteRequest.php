<?php
/*
 * Delete request.php
 * 
 * This page is called through AJAX to delete a specific
 * request and all associated comments.
 */

require_once("../../config.php");
global $CFG, $DB;


	$deleteId = required_param('id', PARAM_INT);    
	$type = optional_param('t', '', PARAM_TEXT);    
	
	// Delete the record
	$deleteQuery = "id = $deleteId";
	$DB->delete_records('block_cmanager_records', array('id'=>$deleteId));

	// Delete associated comments
	$DB->delete_records('block_cmanager_comments', array('instanceid'=>$deleteId));


	if($type == 'a'){
		echo "<script>window.location='cmanager_admin.php';</script>";

	} else {
		echo "<script>window.location='module_manager.php';</script>";
	}	
	

?>
