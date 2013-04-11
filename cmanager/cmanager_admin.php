<?php
/* -----------------------------------------------------------------
 * 
 * 
 *  Course Request Manager
 *  2012-2013 Kyle Goslin, Daniel McSweeney
 * 
 * 
 * 
 * 
 * ------------------------------------------------------------------
 */

require_once("../../config.php");
global $CFG, $DB;
$formPath = "$CFG->libdir/formslib.php";
require_once($formPath);
require_once('generate_summary.php');
require_login();
require_once('validate_admin.php');
require_once('../../course/lib.php');
require_once('lib/displayLists.php');

/** Navigation Bar **/
$PAGE->navbar->ignore_active();
$PAGE->navbar->add(get_string('cmanagerDisplay', 'block_cmanager'), new moodle_url('/blocks/cmanager/cmanager_admin.php'));

$PAGE->set_url('/blocks/cmanager/cmanager_admin.php');
$PAGE->set_context(get_system_context());
$PAGE->set_heading(get_string('pluginname', 'block_cmanager'));
$PAGE->set_title(get_string('pluginname', 'block_cmanager'));
echo $OUTPUT->header();

$_SESSION['CRMisAdmin'] = true;
?>


<link rel="stylesheet" type="text/css" href="css/main.css" />
<link href="js/jquery/jquery-ui18.css" rel="stylesheet" type="text/css"/>
<script src="js/jquery/jquery-1.7.2.min.js"></script>
<script src="js/jquery/jquery-ui.1.8.min.js"></script>
  
<script type="text/javascript">

function cancelConfirm(id,langString) {
	var answer = confirm(langString)
	if (answer){
		
		window.location = "deleteRequest.php?t=a&&id=" + id;
	}
	
}


function quickApproveConfirm(id,langString) {
	var answer = confirm(langString)
	if (answer){
		
		window.location = "admin/bulk_approve.php?mul=" + id;
	}
	
}



var checkedIds  = ['null'];

//
// List of currently selected Ids
//				
function addIdToList(id){
	var i = checkedIds.length;
	var found = false;
	
	while (i--) {
	
	    if (checkedIds[i] === id) {
	      	checkedIds[i] = 'null';
			found = true;
	    }
	}
	
	if(found === false){
		checkedIds.push(id);
	}
}
			




/*
 * This function is used to save the text from the 
 * categories when they are changed.
 */
function saveChangedCategory(recordId){

   var fieldvalue = document.getElementById('menucat' + recordId).value;
 
 	
   
    $.post("ajax_functions.php", { type: 'updatecategory', value: fieldvalue, recId: recordId },
    		   function(data) {
    		     alert("Changes have been saved!");
    		   });
	
	
}


</script>


<?php


class courserequest_form extends moodleform {
 
	function definition() {
    global $CFG;
    global $USER, $DB;
    $mform =& $this->_form; // Don't forget the underscore! 
 
 
 	$selectQuery = "status = 'PENDING' ORDER BY id ASC";
 
 	// If search is enabled then use the
 	// search parameters
 	if($_POST && isset($_POST['search'])){
 		
 		$searchText = required_param('searchtext', PARAM_TEXT);
		$searchType = required_param('searchtype', PARAM_TEXT);

		if(!empty($searchText) && !empty($searchType)){
			
			
			if($searchType == 'code'){
				$selectQuery = "`modcode` LIKE '%{$searchText}%'";
			}
			else if($searchType == 'title'){
				$selectQuery = "`modname` LIKE '%{$searchText}%'";
			}
			else if($searchType == 'requester'){
				$selectQuery = "`createdbyid` = (Select id from ".$CFG->prefix."user where `firstname` LIKE '%{$searchText}%' OR `lastname` LIKE '%{$searchText}%' OR `username` LIKE '%{$searchText}%')";
			}
		}
	}
	



	
	// Get the list of records
	$pendingList = $DB->get_recordset_select('block_cmanager_records', $select=$selectQuery);
   	$outputHTML = displayAdminList($pendingList, true, true, true, 'admin_queue');
	
 
  
   $mform->addElement('header', 'mainheader', '<span style="font-size:18px">'.get_string('currentrequests','block_cmanager').'</span>');
  

	$bulkActions = "
			<div style=\"width: 200px; text-align:left; font-size:11pt; float:left\">
			
			<b>".get_string('bulkactions','block_cmanager')."</b>
			<br>
			<input type=\"checkbox\" onClick=\"toggle(this)\" /> Select All<br/>
			
			<select id=\"bulk\" onchange='bulkaction();'>
			  <option></option>
			  <option value =\"Approve\"'>".get_string('bulkapprove','block_cmanager')."</option>
			  <option value=\"Deny\">".get_string('deny','block_cmanager')."</option>
			  <option value =\"Delete\"'>".get_string('delete','block_cmanager')."</option>
			</select>
			
			</div>";	 



	$page1_fieldname1 = $DB->get_field_select('block_cmanager_config', 'value', "varname='page1_fieldname1'");
	$page1_fieldname2 = $DB->get_field_select('block_cmanager_config', 'value', "varname='page1_fieldname2'");
	
	$searchHTML = ' 
	   <div style="float:right">
	 	<form action="cmanager_admin.php?search=1" method="post"> 
	 	
	 	<b><span style="font-size:11pt">'.get_string('search_side_text', 'block_cmanager').'</span></b>
	 	<br> <input type="text" name="searchtext" id="searchtext"></input><br>
	 	<span style="font-size:11pt">
	 	<select name="searchtype" id="searchtype">
  		<option value="code">'.$page1_fieldname1.'</option>
		<option value="title">'.$page1_fieldname2.'</option>
  		<option value="requester">' . get_string('searchAuthor', 'block_cmanager').'</option>
		</select>
		</span>
		<br>
		<span style="font-size:11pt">
		<input type="submit" value="'.get_string('searchbuttontext', 'block_cmanager').'" name="search"></input>
		</span>
		</form>
		
		';
		
		if($_POST && isset($_POST['search'])){
			$searchHTML .= '<br><p></p><a href="cmanager_admin.php">[Clear Search]</a>';
		}
	 
	 $searchHTML .= '</div>';
 		

 	
	$mainBody = '
			    
    	<p></p>
    	<span style="font-size:11px">
    	'. $searchHTML. $bulkActions  . '
		<p></p>
		&nbsp;
		<p></p>
		&nbsp;<br>
	<p></p>
		&nbsp;<br>
	  	<center>
		<div id="twobordertitle" style="background: transparent">
		<div style="text-align: left; float: left; font-size:11pt">&nbsp;<b>' .  get_string('existingrequests','block_cmanager') .'</b></div> 
		<div style="text-align: right; font-size:11pt"><b>' .  get_string('actions','block_cmanager') .'</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
		</div>
	
	    ' . $outputHTML. '
	    </center>
	    </span>';
				
			    
	$mform->addElement('html', $mainBody);

 


    } // Close the function
}  // Close the class



echo "<script>

function toggle(source) {
  checkboxes = document.getElementsByName('groupedcheck');
  for(var i in checkboxes){
  
  		
    if(!checkboxes[i].disabled){
    
    checkboxes[i].checked = source.checked;
    
    addIdToList(checkboxes[i].id)
    }
   }
}

function bulkaction(){

	var cur = document.getElementById('bulk');
    
   
	if(cur.value == 'Delete'){
			
	$.post(\"ajax_functions.php\", { type: \"del\", values: checkedIds},
		   function(data) {
		    		window.location='cmanager_admin.php';
		    	
		   });						

	}
	
	if(cur.value == 'Deny'){
		window.location='admin/bulk_deny.php?mul=' + checkedIds;			
	}
	
	if(cur.value == 'Approve'){
		window.location='admin/bulk_approve.php?mul=' + checkedIds;			
	}
	
}
</script>";


$mform = new courserequest_form();

if ($mform->is_cancelled()){
    
	
} else if ($fromform=$mform->get_data()){

		 
} else {

        
		
		   
}



		if($_POST && isset($_POST['search'])){
	
			$searchText = required_param('searchtext', PARAM_TEXT);
			$searchType = required_param('searchtype', PARAM_TEXT);
			
			echo "<script>document.getElementById('searchtext').value = '$searchText'; ";	
			echo "
				var desiredValue = '$searchType';
				var el = document.getElementById('searchtype');
				for(var i=0; i<el.options.length; i++) {
				  if ( el.options[i].value == desiredValue ) {
				    el.selectedIndex = i;
				    break;
				  }
				} 
				</script>
				";

			
 		}


$mform->focus();
$mform->display();		
echo $OUTPUT->footer();
?>

  
	
		