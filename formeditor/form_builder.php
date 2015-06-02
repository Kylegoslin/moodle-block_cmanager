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

require_login();
require_once('../validate_admin.php');

/** Navigation Bar **/
$PAGE->navbar->ignore_active();
$PAGE->navbar->add(get_string('cmanagerDisplay', 'block_cmanager'), new moodle_url('/blocks/cmanager/cmanager_admin.php'));
$PAGE->navbar->add(get_string('configurecoursemanagersettings', 'block_cmanager'), new moodle_url('/blocks/cmanager/cmanager_confighome.php'));
$PAGE->navbar->add(get_string('formpage2builder', 'block_cmanager'));
$PAGE->set_url('/blocks/cmanager/formeditor/form_builder.php');
$PAGE->set_context(context_system::instance());
$PAGE->set_heading(get_string('pluginname', 'block_cmanager'));
$formPath = "$CFG->libdir/formslib.php";
require_once($formPath);
$PAGE->set_title(get_string('pluginname', 'block_cmanager'));
echo $OUTPUT->header();


$context = context_system::instance();
if (has_capability('block/cmanager:viewconfig',$context)) {
} else {
  print_error(get_string('cannotviewrecords', 'block_cmanager'));
}


?>



<link rel="stylesheet" type="text/css" href="../css/main.css" />
<script src="../js/jquery/jquery-1.7.2.min.js"></script>


<script>
	function saveSelectedForm(){
		
		 var value = document.getElementById('selectform').value;
		  
		
		  $.ajaxSetup({async:false});
		  $.post("ajax_functions.php", { type: 'saveselectedform', value: value},
   				function(data) {
		     		
		         // alert(data);
			   });
		
			   
			   window.location = 'form_builder.php';
		
	}
	
	
	function deleteSelectedForm(confirmMsg,form){
		
		var confirmDelete = confirm(confirmMsg);

		if(confirmDelete == true){
			window.location = "form_builder.php?del="+form;
		}
		
		
	}
	function goBack(){
	window.location ="../cmanager_confighome.php";
}
</script>
<?php





if(isset($_GET['del'])){
	$delId = required_param('del', PARAM_INT);
    $DB->delete_records_select('block_cmanager_config', "id = $delId"); 
	echo " <script>window.location = 'form_builder.php';</script> ";
}
?>


<script>

	function addNewField(){
		
		var value = document.getElementById('newformname').value;
      
       
       if(value != ''){
	        $.ajaxSetup({async:false});
	        $.post("ajax_functions.php", { type: 'addnewform', value: value},
	   				function(data) {
			     		
			          
				   });
			   
			   window.location = 'form_builder.php';
	    }
	}
	
	
	


</script>
	

<?php


class block_cmanager_builder_form extends moodleform {
 
    function definition() {
        global $CFG, $USER, $DB;
        $mform =& $this->_form; // Don't forget the underscore! 
 
 
 $headingTab =  '
		<p></p> 
		&nbsp;
		<p></p>	';
	 
 
 
 
   	$mform->addElement('header', 'mainheader', '<span style="font-size:18px"> '.get_string('formpage2','block_cmanager').'</span>');
   
 	$mform->addElement('html', '<p></p>	<button type="button" onclick="goBack();"><img src="../icons/back.png"/> '.get_string('back','block_cmanager').'</button><p></p>
	');
	
	// Page description text
	$mform->addElement('html', '<br>'.get_string('formBuilder_instructions','block_cmanager').'<ul><li>'.get_string('formBuilder_instructions1','block_cmanager').'</li><li>'.get_string('formBuilder_instructions2','block_cmanager').'</li><li>'.get_string('formBuilder_instructions3','block_cmanager').'</li><li>'.get_string('formBuilder_instructions4','block_cmanager').'</li><li>'.get_string('formBuilder_instructions5','block_cmanager').'</li><li>'.get_string('formBuilder_instructions6','block_cmanager').'</li><p></p><p></p>');

		
	$mform->addElement('header', 'mainheader', get_string('formBuilder_currentActiveForm','block_cmanager'));
	$mform->addElement('html','</b><br>'.get_string('formBuilder_currentActiveFormInstructions','block_cmanager').'<br><br></center>');

	$currentSelectedForm = $DB->get_field_select('block_cmanager_config', 'value', "varname = 'current_active_form_id'");	
	
    $whereQuery = "varname = 'page2form'";
 	$formrows = $DB->get_recordset_select('block_cmanager_config', $whereQuery);
										   
		$selectHTML = '<center>'.get_string('formBuilder_selectDescription','block_cmanager').': <select onchange="saveSelectedForm()" id="selectform">';
		    	foreach($formrows as $row){
				$selected = '';	
				if($currentSelectedForm == $row->id){
					$selected = 'selected = "yes" ';
				
				}
				$selectHTML .='	<option '. $selected .' value="' .$row->id . '">' . $row->value.'</option>';
				$selected = '';
			}
		
		
		
		$selectHTML .='</select></center><p></p>&nbsp;';
		$mform->addElement('html', $selectHTML);
		
		
	
	$whereQuery = "varname = 'page2form'";
 	$formRecords = $DB->get_recordset_select('block_cmanager_config', $whereQuery);
										   
	
	$formsItemsHTML = '<table width="250px">';
	foreach($formRecords as $rec){
		$formsItemsHTML .= '<tr>';
		
		$formsItemsHTML .= '<td width="100px">' .$rec->value.'</td>';
		$formsItemsHTML .= '<td><a title="'.get_string('formBuilder_editForm','block_cmanager').'" href="page2.php?id=' . $rec->id . '&name='.$rec->value.'">[Edit]</></td>';
		$formsItemsHTML .= '<td><a title="'.get_string('formBuilder_previewForm','block_cmanager').'" href="preview.php?id=' . $rec->id . '">[Preview]</a></td>';
		$formsItemsHTML .= '<td><a title="'.get_string('formBuilder_deleteForm','block_cmanager').'" href="#" onclick="javascript:deleteSelectedForm(\''.get_string('formBuilder_confirmDelete','block_cmanager').'\',' . $rec->id . ');">[Delete]</a></td>';
		
		$formsItemsHTML .= '</tr>';
	}
	
		$formsItemsHTML .= '</table>';
	
		$mform->addElement('header', 'mainheader', get_string('formBuilder_manageFormsText','block_cmanager'));
	
	    $mform->addElement('html', '<center>
		<p></p>
		'.get_string('formBuilder_selectAny','block_cmanager').'<p></p>
		
	    '. $formsItemsHTML .'
	    	   <p></p> 
			   <input type="text" id = "newformname" size="20"></input> <input type="button" value = "'.get_string('formBuilder_createNewText','block_cmanager').'" onclick="addNewField()"/></center>');
	}
}


$mform = new block_cmanager_builder_form();//name of the form you defined in file above.

if ($mform->is_cancelled()) {
    
} else if ($fromform=$mform->get_data()) {

			
		   
 
} else {

       
		   
 
}



	$mform->focus();
	$mform->display();
	echo $OUTPUT->footer();




?>

  
	
		