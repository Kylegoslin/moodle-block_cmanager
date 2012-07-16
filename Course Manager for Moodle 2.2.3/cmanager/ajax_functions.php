<?php



require_once("../../config.php");
global $CFG, $DB;


	$type = $_POST['type'];
	

	
	


if($type == 'del'){

    $values = $_POST['values'];
	foreach($values as $id) {


		if($id != 'null'){
			/*
		// Delete the record
		$deleteQuery = "id = $id";
		$DB->delete_records_select('cmanager_records', $deleteQuery);

		// Delete associated comments
		$deleteCommentsQuery = "instanceid = $deleteId";
		$DB->delete_records_select('cmanager_comments', $deleteCommentsQuery);
		*/
		$DB->delete_records('cmanager_records', array('id'=>$id));
	
		// Delete associated comments
		$DB->delete_records('cmanager_comments', array('instanceid'=>$id));


		}


	}



}

/*
 * Update the values for emails.
 * 
 * 
 */
if($type == 'updatefield'){
     
   
  	$post_value = addslashes($_POST['value']);
  	$post_id = addslashes($_POST['id']);

  
  	 

  	
  	 $selectQuery = "varname = '$post_id'";
  	 $recordExists = $DB->record_exists_select('cmanager_config', $selectQuery);
  	 
  	 
  	 if($recordExists){
  	 
  	      // If the record exists
  	     $current_record =  $DB->get_record('cmanager_config', array('varname'=>$post_id));
  	 
  	     $newrec = new stdClass();
	     $newrec->id = $current_record->id;
	     $newrec->varname = $post_id;
	     $newrec->value = $post_value;
  	     $DB->update_record('cmanager_config', $newrec); 
  	     
  	     echo "updated";
  	     
  	 } else {
  	 
  	   	 $newrec = new stdClass();
	     $newrec->varname = $post_id;
	     $newrec->value = $post_value;
  	     $DB->insert_record('cmanager_config', $newrec); 
  	 
  	     
  	     echo "inserted";
  	 }
  	 
   
 
  	
}


?>
