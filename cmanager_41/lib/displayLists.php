
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


/* ------------------------------------------------------------------------
 *  displayLists.php is used to display the various differerent
 *  representations of queues etc.
 * ------------------------------------------------------------------------
 */
 require_once($CFG->libdir. '/coursecatlib.php');
 ?>

<style>
	select.my_dropdown{width:200px}
</style>

 <?php
 /*
  * Display a list of pending modules
  * for the Admin
  *
  */
 function displayAdminList($pendingList, $includeRightPanel, $includeLeftCheckBox, $editCatAvailable, $rightPanelType){

	global $CFG, $DB;

	$outputHTML = '';

   	$page1_fieldname1 = $DB->get_field_select('block_cmanager_config', 'value', "varname='page1_fieldname1'");
	$page1_fieldname2 = $DB->get_field_select('block_cmanager_config', 'value', "varname='page1_fieldname2'");

	$counter = 1;

   foreach($pendingList as $rec){


			// Get a list of all the lecturers
			$lecturerHTML = '';
			$req_values = $rec->req_values;

			if(!empty($req_values)){
				if (! $course = $DB->get_record("course", array("id"=> $req_values))) {
					   // If the course doesn't exist anymore, just let the process continue..
					} else { // Otherwise, start the process
						    $context = get_context_instance(CONTEXT_COURSE, $course->id);
						    if ($managerroles = get_config('', 'coursemanager')) {
									$coursemanagerroles = explode(',', $managerroles);
									foreach ($coursemanagerroles as $roleid) {
									    $role = $DB->get_record('role', array('id'=>$roleid));
									    $canseehidden = has_capability('moodle/role:viewhiddenassigns', $context);
									    $roleid = (int) $roleid;
									    $namesarray = null;
									    if ($users = get_role_users($roleid, $context, true, '', 'u.lastname ASC', $canseehidden)) {

										    foreach ($users as $teacher) {
										    $fullname = fullname($teacher, has_capability('moodle/site:viewfullnames', $context));
										    $namesarray[] = '<a href="'.$CFG->wwwroot.'/user/view.php?id='.
										                    $teacher->id.'&amp;course='.SITEID.'">'.$fullname.'</a>';
										}
									    }
									}
									if (!empty($namesarray)) {
									    $lecturerHTML =  implode(', ', $namesarray);

									}

						    }
					}
			}

			else {
				// Get the id from who created the record, and get their username

				$fullname = $DB->get_field('user', 'username', array('id'=>$rec->createdbyid));

				$lecturerHTML = '<a href="'.$CFG->wwwroot.'/user/view.php?id='.
						                    $rec->createdbyid.'&amp;course='.SITEID.'">'.$fullname.'</a>';


			}


			//Get the latest comment
			$latestComment = '';
			$currentModId = $rec->id;


			$whereQuery = "instanceid = '$currentModId'";
		 	$modRecords = $DB->get_recordset_select('block_cmanager_comments', $whereQuery);


		    foreach($modRecords as $record){

				$latestComment = $record->message;
				if(strlen($latestComment) > 55){
					$latestComment = substr($latestComment, 0, 55);
					$pagename = basename($_SERVER['PHP_SELF']);

					if($pagename == 'module_manager.php'){
					$latestComment .= '... <a href="comment.php?type=userq&id=' . $currentModId . '">[View More]</a>';

					} else {
						$latestComment .= '... <a href="comment.php?type=adminq&id=' . $currentModId . '">[View More]</a>';
					}
				}
		    }

			// Check if shortname exists
			$shortNameExists = $DB->record_exists('course', array('shortname'=>$rec->modcode));


			$shortNameExistsMode = $DB->record_exists('course', array('shortname'=>$rec->modcode . ' - ' . $rec->modmode));

			$disabledHTML = '';
			if($shortNameExists == 1 || $shortNameExistsMode == 1){
				$disabledHTML = 'disabled="disabled"';
			}
			$outputHTML .= '

			<div id="existingrequest" style="background: transparent;">';



			$outputHTML .= '
			<div style="float:left; padding-bottom:20px; width:100%">
			<p style="font-size:16px;"><br><b>[' . get_string('Request','block_cmanager'). ' #'.$counter.']</b></p>


			';
			if($includeLeftCheckBox == true){
					$outputHTML .= '<input type="checkbox" id="' . $rec->id . '" name="groupedcheck" onClick="addIdToList(' . $rec->id . ')" value="' . $rec->id . '" '.$disabledHTML.'/>
									<br>';
		    }

		    // ---------- Additional Controls -------------------------------
		if($includeRightPanel == true){

				if($rightPanelType == 'admin_queue'){

						$outputHTML .= '
						<div style="font-size:14px; padding-bottom:5px">
								<a href="#" onclick="quickApproveConfirm('. $rec->id .',\''.get_string('quickapprove_desc','block_cmanager').'\')"><img src="icons/list/tick.png"/> ' . get_string('quickapprove','block_cmanager'). '</a>

								<a href="admin/approve_course.php?id=' . $rec->id .'"><img src="icons/list/tick.png"/> ' . get_string('approve','block_cmanager'). '</a>


								<a href="admin/deny_course.php?id=' . $rec->id .'"><img src="icons/list/deny.png"/> ' . get_string('deny','block_cmanager'). '</a>

								<a href="course_request.php?edit=' . $rec->id .'"><img src="icons/list/edit.png"/> ' . get_string('edit','block_cmanager'). '</a>

								<a href="#" onclick="cancelConfirm('. $rec->id .',\''.get_string('configure_delete','block_cmanager').'\')" href="#"><img src="icons/list/delete.png"/> ' . get_string('delete','block_cmanager'). '</a>
								<img src="icons/list/comment.png"/><a href="admin/comment.php?type=adminq&id=' . $rec->id . '">' . get_string('addviewcomments','block_cmanager'). '</a>
						</div>

						';

					}
					else if($rightPanelType == 'admin_arch'){
						$outputHTML .= '
								<div style="float:left; font-size:14px; padding-bottom:5px">

										<a onclick="cancelConfirm('. $rec->id .', \'delete\')" href="#"><img src="icons/list/delete.png"/>' . get_string('delete','block_cmanager'). '</a>

										<A href="admin/comment.php?type=adminarch&id=' . $rec->id . '"><img src="icons/list/comment.png"/>' . get_string('addviewcomments','block_cmanager'). '</a>

								</div>
							';
					}
					else if($rightPanelType == 'user_manager'){

									$outputHTML .= '
							<div style="float: left; font-size:14px; padding-bottom:5px">

									<A href="view_summary.php?id=' . $rec->id .'"><img src="icons/list/open.png"/>'.get_string('view','block_cmanager').'</a>

									<A href="course_request.php?edit=' . $rec->id .'"><img src="icons/list/edit.png"/>'.get_string('edit','block_cmanager').'</a>

									<a onclick="cancelConfirm('. $rec->id .',\''.get_string('cmanagerConfirmCancel','block_cmanager').'\')" href="#"><img src="icons/list/deny.png"/>'.get_string('cancel','block_cmanager').'</a>

									<A href="comment.php?type=userq&id=' . $rec->id . '"><img src="icons/list/comment.png"/>'.get_string('addviewcomments','block_cmanager').'</a>


							</div>		';
					}
					else if($rightPanelType == 'user_history'){
						$outputHTML .= '
							<div style="float: left; font-size:14px; padding-bottom:5px">

									<a href="view_summary.php?id=' . $rec->id .'"> <img src="icons/list/open.png"/> '.get_string('view','block_cmanager').'</a>

									<A href="comment.php?type=userarch&id=' . $rec->id . '"> <img src="icons/list/comment.png"/> '.get_string('addviewcomments','block_cmanager').'</a>

							</div>';
					}
			}
			// ------------------ END admin controls ----------------------

			$outputHTML .='



			 <table width="100%" style="overflow:hidden;" cellpadding="3" >

				<tr>
					<td width="25%">
						<b>' . get_string('status','block_cmanager'). ':</b>
					</td>
					<td style="width:100%">
						'. $rec->status . '
					</td>
				</tr>
				';

			// Check if shortname exists
			if($rightPanelType == 'admin_queue'){
						if($shortNameExists == 1 || $shortNameExistsMode == 1){
			    			$outputHTML .= '
			    			<tr>

								<td width="25%">
									<b><span style="color:red">' . get_string('displayListWarningTitle','block_cmanager'). ':</span></b>
								</td>
								<td>
									<span style="color:red">'. get_string('displayListWarningSideText','block_cmanager') . '</span>
								</td>
							</tr>
			    			';
						}
			}

			$outputHTML .= '
				<tr>
					<td width="25%">
						<b>' . get_string('creationdate','block_cmanager'). ':</b>
					</td>
					<td>
						'. $rec->createdate . '
					</td>
				</tr>

				<tr>
					<td width="25%">
						<b>' . get_string('requesttype','block_cmanager'). ':</b>
					</td>
					<td>
						'. $rec->req_type . '
					</td>
				</tr>

				<tr>
					<td width="25%">
						<b>' . $page1_fieldname1 . ':</b>
					</td>
					<td>
						'. $rec->modcode . '
					</td>
				</tr>

				<tr>
					<td width="25%">
						<b>'. $page1_fieldname2 .':</b>
					</td>
					<td>
						'. $rec->modname . '
					</td>
				</tr>';

				if(isset($rec->modmode)){
				 $selectedModName = $DB->get_field_select('block_cmanager_config', 'value', "varname = 'page1_fielddesc3'");

				$outputHTML .= '
						<tr>
						<td width="25%">
							<b> ' . $selectedModName. ': </b>
						</td>
						<td>
							'. $rec->modmode . '
						</td>
					</tr>';
				}

	$catlistHTML = '';
	if($editCatAvailable == true){


			    $movetocategories = array();
        $notused = array();
        $movetocategories += coursecat::make_categories_list('moodle/category:manage');
        //make_categories_list($movetocategories, $notused);
      	$cateDrop =  html_writer::select($movetocategories, 'cat'.$rec->id, $rec->cate, null);
 	    $catlistHTML .= '<div id="catname" class="catname">'.$cateDrop . '';

		//$catlistHTML .=  '<button onClick="saveChangedCategory(\''.$rec->id.'\')">Save</button>';
		$catlistHTML .=  '<input id="clickMe" type="button" value="'.get_string('update','block_cmanager').'" onclick="saveChangedCategory(\''.$rec->id.'\')" /></div>';
		//echo '<script> document.getElementById("'.'cat'.$rec->id.'").value = '.$rec->cate.'; </script> ';





	} else {

		if(!empty($rec->cate)){
			$catlistHTML .= $DB->get_field_select('course_categories', 'name', "id =" . $rec->cate);
		} else {
			$catlistHTML = '<i>None Selected</i>';
		}

	}
							$outputHTML .= '
									<tr>
									<td width="25%">
										<b> ' . get_string('selectedcategory', 'block_cmanager'). ': </b>
									</td>
									<td>
										'. $catlistHTML . '
									</td>
								</tr>';

				if 	(isset($rec->modkey)){

					$outputHTML .= '
						<tr>
							<td width="25%">
								<b> ' . get_string('configure_EnrolmentKey','block_cmanager'). ':</b>
							</td>
							<td>
								'. $rec->modkey . '
							</td>
						</tr>';

				}


				$outputHTML .= '
				' . generateSummary($rec->id, $rec->formid) . '

				<tr>
					<td width="25%">
						<b>' . get_string('originator','block_cmanager'). ':</b>
					</td>
					<td>
						' . $lecturerHTML . '
					</td>

				</tr>


				<tr>
					<td width="25%">
					&nbsp;
					</td>
					<td>
					&nbsp;
					</td>

				</tr>

				<tr>
					<td width="25%">
						<b>' . get_string('comments','block_cmanager'). ':</b>
					</td>
					<td>
						'. $latestComment . '
					</td>

				</tr>
			 </table>
			 </div>
			 ';



		$outputHTML .= '</div>';
		$counter++;
	}


return $outputHTML;
}













 function generateSummary($recordId, $formId){

	global $CFG, $DB;

	$generatedHTML = '';


	// Get the form fields from the database.
	$whereQuery = "formid = '$formId'";


	$modRecords = $DB->get_records('block_cmanager_formfields', array('formid'=>$formId), $sort='position ASC');



	$counter = 1;

    foreach($modRecords as $record){

		$fieldIdName = 'c' . $counter;
		$generatedHTML .= '<tr>';
		$generatedHTML .= '  <td width="25%">';
		$generatedHTML .= '  <b>' . $record->lefttext . ': </b>';
		$generatedHTML .= ' </td>';
		$generatedHTML .= '	<td>';
		$generatedHTML .= $DB->get_field('block_cmanager_records', $fieldIdName, array('id'=>$recordId));
		$generatedHTML .= '	</td>';
		$generatedHTML .= '</tr>';

		$counter++;
	}





	return $generatedHTML;
}



