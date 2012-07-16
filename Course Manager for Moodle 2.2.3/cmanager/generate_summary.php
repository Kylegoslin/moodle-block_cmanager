<?php

/* ------------------------------------------------
 * 
 *   Generate Summary
 * 
 *  This function is used for generating the HTML table
 *  rows which holds all the course specific meta data
 *  which has been generated.
 * 
 * ------------------------------------------------
 */

 function generateSummary($recordId, $formId){
	
	global $CFG, $DB;
	
	$generatedHTML = '';
	
	 
	// Get the form fields from the database.
	$whereQuery = "formid = '$formId'";
 	//$modRecords = $DB->get_field_select('cmanager_formfields', $whereQuery, $sort='', $fields='*', 
			                    //           $limitfrom='', $limitnum='');
	
	$modRecords = $DB->get_records('cmanager_formfields',array('formid'=>$formId));
	
	
		
	$counter = 1;
	   
    foreach($modRecords as $record){
    	
		$fieldIdName = 'c' . $counter;
		$generatedHTML .= '<tr>';
		$generatedHTML .= '  <td width="150px">';
		$generatedHTML .= '  <b>' . $record->lefttext . ':</b>';
		$generatedHTML .= ' </td>';
		$generatedHTML .= '	<td>';
		$generatedHTML .= $DB->get_field('cmanager_records', $fieldIdName, array('id'=>$recordId));
		$generatedHTML .= '	</td>';
		$generatedHTML .= '</tr>';
		
		$counter++;
	}	
	
	
	
	
	
	return $generatedHTML;
}



?>