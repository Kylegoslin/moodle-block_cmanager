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
$formPath = "$CFG->libdir/formslib.php";
require_once($formPath);
require_login();
require_once('../../course/lib.php');
require_once('lib/displayLists.php');

/** Navigation Bar **/
$PAGE->navbar->ignore_active();
$PAGE->navbar->add(get_string('cmanagerDisplay', 'block_cmanager'), new moodle_url('/blocks/cmanager/cmanager_admin.php'));
$PAGE->navbar->add(get_string('allarchivedrequests', 'block_cmanager'));


$PAGE->set_url('/blocks/cmanager/cmanager_admin.php');
$PAGE->set_context(context_system::instance());
$PAGE->set_heading(get_string('pluginname', 'block_cmanager'));
$PAGE->set_title(get_string('pluginname', 'block_cmanager'));
echo $OUTPUT->header();

$context = context_system::instance();

if (has_capability('block/cmanager:approverecord',$context)) {
} else {
       print_error(get_string('cannotviewrecords', 'block_cmanager'));
} 

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

/**
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
<style>
	tr:nth-child(odd)		{ background-color:#eee; }
	tr:nth-child(even)		{ background-color:#fff; }
 </style>
<?php



/**
* Admin Arch
*
* Display admin arch page
* @package    block_socialbookmark
* @copyright  2014 Kyle Goslin, Daniel McSweeney
* @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
*/
class block_cmanager_adminarch_form extends moodleform {
 
    function definition() {
        global $CFG;
        global $USER, $DB;
        $mform =& $this->_form; // Don't forget the underscore! 
     
      	$selectQuery = "status = 'PENDING' ORDER BY id ASC";
     
        // If search is enabled then use the
        // search parameters
        if ($_POST && isset($_POST['search'])) {
     	    $searchText = required_param('searchtext', PARAM_TEXT);
    		$searchType = required_param('searchtype', PARAM_TEXT);

            if (!empty($searchText) && !empty($searchType)) {
    			
    			if ($searchType == 'code') {
    				$selectQuery = "`modcode` LIKE '%{$searchText}%'";
    			}
    			else if ($searchType == 'title') {
    				$selectQuery = "`modname` LIKE '%{$searchText}%'";
    			}
    			else if ($searchType == 'requester') {
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
		
if ($_POST && isset($_POST['archsearch'])) {
	$archSearchText = required_param('archsearchtext', PARAM_TEXT);
    $archSearchType = required_param('archsearchtype', PARAM_TEXT);
	
	if (!empty($archSearchText) && !empty($archSearchType)) {
        if($archSearchType == 'code') {
		    $additionalSearchQuery = " AND `modcode` LIKE '%{$archSearchText}%'";
		}
		else if ($archSearchType == 'title') {
					$additionalSearchQuery = " AND `modname` LIKE '%{$archSearchText}%'";
				}
        else if($archSearchType == 'requester') {
            $additionalSearchQuery = " AND `createdbyid` = (Select id from ".$CFG->prefix."user where `firstname` LIKE '%{$archSearchText}%' OR `lastname` LIKE '%{$archSearchText}%' OR `username` LIKE '%{$archSearchText}%')";
		}
	}
}
		

$numberOfRecords = $DB->count_records_sql("SELECT count(id) FROM " . $CFG->prefix ."block_cmanager_records WHERE status = 'COMPLETE' OR status = 'REQUEST DENIED'" . $additionalSearchQuery);
$numberOfPages = ceil($numberOfRecords / 10) -1;
		
$selectedOption = '';
$archRequestsDropdown = ' <br>View Page: 
        <select onchange="goToPage();" name="pageNumber" id="pageNumber">';
           
    $i = 1;		   
		   
    while ($i < $numberOfPages+1) {
	    if (isset($_GET['p'])) {
	        if (required_param('p', PARAM_INT) == $i) {
		        $selectedOption = 'selected = "yes"';
	        }
        }
        $archRequestsDropdown .= '<option ' .$selectedOption .' value="' . $i. '">' . $i. '</option>';
        $i++;
        $selectedOption = '';	
    }  

    if ($numberOfRecords % 2) {
	
    } else {
	    if (isset($_GET['p'])) {
	    if (required_param('p', PARAM_INT) == $i) {
		    $selectedOption = 'selected = "yes"';
	    }
    }
	$archRequestsDropdown .= '<option '. $selectedOption.'="' . $i. '"> ' . $i.'</option>';
    }

    $archRequestsDropdown .= '</select>';

  
// -----------------------------------------------------------------------------------------
 
// if a page number is selected
if (isset($_GET['p'])) {
    $selected_page_number = required_param('p', PARAM_INT);
    $fromLimit = ($selected_page_number -1) * 10;
    $toLimit = $fromLimit + 10;
} else {
    $fromLimit = 0;
    $toLimit = 10;
}
	 
	
$pendingList = $DB->get_records_sql("SELECT * FROM ". $CFG->prefix ."block_cmanager_records 
                                     WHERE status = 'COMPLETE' OR status = 'REQUEST DENIED'" . $additionalSearchQuery . " 
                                     order by id desc LIMIT $fromLimit, $toLimit");
$mform->addElement('header', 'mainheader', '<span style="font-size:18px"> '.get_string('allarchivedrequests','block_cmanager').'</span>');

$outputHTML = '';
$outputHTML .= $archRequestsDropdown;
$outputHTML .= '
				<div id="twobordertitle" style="background:transparent">
				<div style="text-align: left; float: left; font-size:11pt">&nbsp;<b>'. get_string('archivedrequests','block_cmanager').'</b></div> 
				</div>
			';
$outputHTML .= block_cmanager_display_admin_list($pendingList, true, false, false, 'admin_arch');
			
			
	
$mform->addElement('html', $outputHTML);

	


    } // Close the function
}  // Close the class



$mform = new block_cmanager_adminarch_form();

if ($mform->is_cancelled()) {
    
	
} else if ($fromform=$mform->get_data()) {
//this branch is where you process validated data.
 
} else {

}



		
if ($_POST && isset($_POST['archsearch'])) {

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


  
	
		