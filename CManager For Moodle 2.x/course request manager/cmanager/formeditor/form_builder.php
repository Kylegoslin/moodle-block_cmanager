<title>Course Reqest Manager</title>
<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
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

		if(confirmDelete)
		{
			window.location = "form_builder.php?del="+form;
		}
		
		
	}
	
</script>
<?php


require_once("../../../config.php");
global $CFG, $DB;

/** Navigation Bar **/
$PAGE->navbar->ignore_active();
$PAGE->navbar->add(get_string('cmanagerDisplay', 'block_cmanager'), new moodle_url('/blocks/cmanager/cmanager_admin.php'));
$PAGE->navbar->add(get_string('configurecoursemanagersettings', 'block_cmanager'), new moodle_url('/blocks/cmanager/cmanager_confighome.php'));
$PAGE->navbar->add(get_string('formpage2builder', 'block_cmanager'));
 

$PAGE->set_url('/blocks/cmanager/formeditor/form_builder.php');
$PAGE->set_context(get_system_context());
 

$formPath = "$CFG->libdir/formslib.php";
require_once($formPath);


if(isset($_GET['del'])){
	$delId = required_param('del', PARAM_INT);
    $DB->delete_records_select('cmanager_config', "id = $delId"); 
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


class courserequest_form extends moodleform {
 
    function definition() {
        global $CFG, $USER, $DB;
        $mform =& $this->_form; // Don't forget the underscore! 
 
 
 
   	$mform->addElement('header', 'mainheader', get_string('formBuilder_about','block_cmanager'));
   
	//<a href="../cmanager_confighome.php">< '.get_string('back','block_cmanager').'</a>   
	$mform->addElement('html', "<br><a href=\"../cmanager_confighome.php\">< ".get_string('back','block_cmanager')."</a><br>");
     
	// Page description text
	$mform->addElement('html', '<br>'.get_string('formBuilder_instructions','block_cmanager').'<ul><li>'.get_string('formBuilder_instructions1','block_cmanager').'</li><li>'.get_string('formBuilder_instructions2','block_cmanager').'</li><li>'.get_string('formBuilder_instructions3','block_cmanager').'</li><li>'.get_string('formBuilder_instructions4','block_cmanager').'</li><li>'.get_string('formBuilder_instructions5','block_cmanager').'</li><li>'.get_string('formBuilder_instructions6','block_cmanager').'</li><p></p><p></p>');

		
	$mform->addElement('header', 'mainheader', get_string('formBuilder_currentActiveForm','block_cmanager'));
	$mform->addElement('html','</b><br>'.get_string('formBuilder_currentActiveFormInstructions','block_cmanager').'<br><br></center>');

	$currentSelectedForm = $DB->get_field_select('cmanager_config', 'value', "varname = 'current_active_form_id'");	
	
    $whereQuery = "varname = 'page2form'";
 	$formrows = $DB->get_recordset_select('cmanager_config', $whereQuery);
										   
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
 	$formRecords = $DB->get_recordset_select('cmanager_config', $whereQuery);
										   
	
	$formsItemsHTML = '<table width="200px">';
	foreach($formRecords as $rec){
		$formsItemsHTML .= '<tr>';
		$formsItemsHTML .= '<td><a title="'.get_string('formBuilder_editForm','block_cmanager').'" href="page2.php?id=' . $rec->id . '&name='.$rec->value.'">' . $rec->value. '</></td>';
		$formsItemsHTML .= '<td><a title="'.get_string('formBuilder_deleteForm','block_cmanager').'" href="form_builder.php?del='. $rec->id.'"><img src="../images/deleteIcon.png" width="20" height="20" alt="delete" / onclick="javascript:deleteSelectedForm(\''.get_string('formBuilder_confirmDelete','block_cmanager').'\',' . $rec->id . ');"></a></td>';
		$formsItemsHTML .= '<td><a title="'.get_string('formBuilder_previewForm','block_cmanager').'" href="preview.php?id=' . $rec->id . '"><img src="../images/preview.png" width="20" height="20" alt="delete"></a></td>';
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


$mform = new courserequest_form();//name of the form you defined in file above.

if ($mform->is_cancelled()){
    
} else if ($fromform=$mform->get_data()){

			print_header_simple($mform->focus(), "", false);
		    //$mform->set_data($toform);
		    $mform->display();
		   
 
} else {

          print_header_simple($mform->focus(), "", false);
		    
		   //$mform->set_data($toform);
		    $mform->display();
		   
 
}


	echo $OUTPUT->footer();




?>

  
	
		