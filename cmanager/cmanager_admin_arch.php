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
$PAGE->navbar->add(get_string('allarchivedrequests', 'block_cmanager'));


$PAGE->set_url('/blocks/cmanager/cmanager_admin.php');
$PAGE->set_context(get_system_context());
$PAGE->set_heading(get_string('pluginname', 'block_cmanager'));
$PAGE->set_title(get_string('pluginname', 'block_cmanager'));
echo $OUTPUT->header();
?>


<link rel="stylesheet" type="text/css" href="css/main.css" />
<link href="js/jquery/jquery-ui18.css" rel="stylesheet" type="text/css"/>
<script src="js/jquery/jquery-1.7.2.min.js"></script>
<script src="js/jquery/jquery-ui.1.8.min.js"></script>
  
<script type="text/javascript">

function cancelConfirm(id,langString) {
	var answer = confirm(langString)
	if (answer){
		
		window.location = "deleteRequest.php?t=adminarch&&id=" + id;
	}
	
}

/*
 * This function is used to save the text from the 
 * categories when they are changed.
 */
function saveChangedCategory(fieldvalue, recordId){

   
   
    $.post("ajax_functions.php", { type: 'updatecategory', value: fieldvalue, recId: recordId },
    		   function(data) {
    		     alert("Changes have been saved!");
    		   });
	
	
}


  $(document).ready(function() {
    $("#tabs").tabs();
    
    <?php 
    // Switch to the history tab
    if(isset($_GET['view'])){
    	if (required_param('view', PARAM_TEXT) == 'history'){
			echo "    $('#tabs').tabs('select', '2');";    		
    	}
    }
    ?>
  });
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
	


  
  
  echo "
  <script>
    // Open the selected archived request page

  function goToPage(){
  	var page = document.getElementById('pageNumber');
  	window.location = 'cmanager_admin_arch.php?view=history&p=' + page.value;
  }
  </script>";
  
  
        // Arch Requests Dropdow
        $page1_fieldname1 = $DB->get_field_select('block_cmanager_config', 'value', "varname='page1_fieldname1'");
		$page1_fieldname2 = $DB->get_field_select('block_cmanager_config', 'value', "varname='page1_fieldname2'");
        
		
		$additionalSearchQuery = '';
		
		 if($_POST && isset($_POST['archsearch'])){
 				$archSearchText = required_param('archsearchtext', PARAM_TEXT);
				$archSearchType = required_param('archsearchtype', PARAM_TEXT);
		
				if(!empty($archSearchText) && !empty($archSearchType)){
					
 
					if($archSearchType == 'code'){
						$additionalSearchQuery = " AND `modcode` LIKE '%{$archSearchText}%'";
					}
					else if($archSearchType == 'title'){
						$additionalSearchQuery = " AND `modname` LIKE '%{$archSearchText}%'";
					}
					else if($archSearchType == 'requester'){
						$additionalSearchQuery = " AND `createdbyid` = (Select id from ".$CFG->prefix."user where `firstname` LIKE '%{$archSearchText}%' OR `lastname` LIKE '%{$archSearchText}%' OR `username` LIKE '%{$archSearchText}%')";
					}
				}
		}
		
		
		
	
		
        $numberOfRecords = $DB->count_records_sql("SELECT count(id) FROM " . $CFG->prefix ."block_cmanager_records WHERE status = 'COMPLETE' OR status = 'REQUEST DENIED'" . $additionalSearchQuery);

	
        $numberOfPages = ceil($numberOfRecords / 10);
		
        $selectedOption = '';
        $archRequestsDropdown = '
        
        <br>View Page: 
        <select onchange="goToPage();" name="pageNumber" id="pageNumber">';
           
           $i = 1;		   
		   
		   while($i < $numberOfPages+1){
		   	
			if(isset($_GET['p'])){
				if(required_param('p', PARAM_INT) == $i){
					$selectedOption = 'selected = "yes"';
				}
			}
		    $archRequestsDropdown .= '<option ' .$selectedOption .' value="' . $i. '">' . $i. '</option>';
			$i++;
			$selectedOption = '';	
		  }  
		  
		  if($numberOfRecords % 2){
		  	
		  } else {
		  	if(isset($_GET['p'])){
				if(required_param('p', PARAM_INT) == $i){
					$selectedOption = 'selected = "yes"';
				}
			}
		  		  $archRequestsDropdown .= '<option '. $selectedOption.'="' . $i. '"> ' . $i.'</option>';
		  }
		   
		$archRequestsDropdown .= '</select>';
        
  
  // -----------------------------------------------------------------------------------------
 

	
	
	// if a page number is selected
	if(isset($_GET['p'])){
		
		$selected_page_number = required_param('p', PARAM_INT);
		$fromLimit = ($selected_page_number -1) * 10;
		$toLimit = $fromLimit + 10;
	} else {

		$fromLimit = 0;
		$toLimit = 10;
	}
	 
	
	$pendingList = $DB->get_records_sql("SELECT * FROM ". $CFG->prefix ."block_cmanager_records WHERE status = 'COMPLETE' OR status = 'REQUEST DENIED'" . $additionalSearchQuery . " order by id desc LIMIT $fromLimit, $toLimit");
	$mform->addElement('header', 'mainheader', '<span style="font-size:18px"> '.get_string('allarchivedrequests','block_cmanager').'</span>');
	
	$outputHTML = '';
	$outputHTML .= $archRequestsDropdown;
	$outputHTML .= '<center>
						<div id="twobordertitle" style="background:transparent">
						<div style="text-align: left; float: left; font-size:11pt">&nbsp;<b>'. get_string('archivedrequests','block_cmanager').'</b></div> 
						<div style="text-align: right; font-size:11pt"><b>'. get_string('actions','block_cmanager').'</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
						</div>
			';
	$outputHTML .= displayAdminList($pendingList, true, false, false, 'admin_arch');
			
			
	$outputHTML.= '</center>';	
			$mform->addElement('html', $outputHTML);

	


    } // Close the function
}  // Close the class



$mform = new courserequest_form();

if ($mform->is_cancelled()){
    
	
} else if ($fromform=$mform->get_data()){
//this branch is where you process validated data.
 
} else {

}



		
if($_POST && isset($_POST['archsearch'])){

	$archSearchText = required_param('archsearchtext', PARAM_TEXT);
	$archSearchType = required_param('archsearchtype', PARAM_TEXT);
	
	echo "<script>document.getElementById('archsearchtext').value = '$archSearchText'; ";	
	echo "
		var desiredValue = '$archSearchType';
		var el = document.getElementById('archsearchtype');
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

  
	
		