<link rel="stylesheet" type="text/css" href="css/main.css" />
<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
<script>
	
	
	function saveSelectedForm(){
		
		 var value = document.getElementById('selectform').value;
		  
		  
		  $.post("ajax_functions.php", { type: 'saveselectedform', value: value},
   				function(data) {
		     		
		          alert(data);
			   });
		
			   
			   window.location = 'form_builder.php';
		
	}
	
	
	function deleteSelectedForm(form){
		
		var confirmDelete = confirm("Do you want to delete this form.");

		if(confirmDelete)
		{
			window.location = "form_builder.php?del="+form;
		}
		
		
	}
	
</script>
<?php


require_once("../../../config.php");
global $CFG;



$formPath = "$CFG->libdir/formslib.php";
require_once($formPath);


if(isset($_GET['del'])){
	$delId = $_GET['del'];
    delete_records_select('cmanager_config', "id = $delId"); 
	
	echo " <script>window.location = 'form_builder.php';</script> ";
}
?>


<script>

	function addNewField(){
		
		var value = document.getElementById('newformname').value;
      
       
       
        $.ajaxSetup({async:false});
        $.post("ajax_functions.php", { type: 'addnewform', value: value},
   				function(data) {
		     		
		          
			   });
			   
			   window.location = 'form_builder.php';
	}
	
</script>
	

<?php


class courserequest_form extends moodleform {
 
    function definition() {
        global $CFG;
        global $USER;
        $mform =& $this->_form; // Don't forget the underscore! 
 
 
     
	   	$mform->addElement('header', 'mainheader', get_string('formBuilder_name','block_cmanager'));
	         
		// Page description text
		$mform->addElement('html', '<br>'.get_string('formBuilder_instructions','block_cmanager').'<ul><li>'.get_string('formBuilder_instructions1','block_cmanager').'</li><li>'.get_string('formBuilder_instructions2','block_cmanager').'</li><li>'.get_string('formBuilder_instructions3','block_cmanager').'</li><li>'.get_string('formBuilder_instructions4','block_cmanager').'</li><li>'.get_string('formBuilder_instructions5','block_cmanager').'</li><li>'.get_string('formBuilder_instructions6','block_cmanager').'</li><p></p><hr><p></p><b><center>'.get_string('formBuilder_currentActiveForm','block_cmanager').'</b><br>'.get_string('formBuilder_currentActiveFormInstructions','block_cmanager').'<br><br></center>');
	
		
	$currentSelectedForm = get_field_select('cmanager_config', 'value', "varname = 'current_active_form_id'");	
		
    $whereQuery = "varname = 'page2form'";
 	$formrows = get_recordset_select('cmanager_config', $whereQuery, $sort='', $fields='*', 
			                               $limitfrom='', $limitnum='');
		$selectHTML = '<center>'.get_string('formBuilder_selectDescription','block_cmanager').': <select onchange="saveSelectedForm()" id="selectform">';
		    echo '<option value = "">'.get_string('formBuilder_selectOption','block_cmanager').'</option>';
			foreach($formrows as $row){
				$selected = '';	
				if($currentSelectedForm == $row['id']){
					$selected = 'selected = "yes" ';
				
				}
				$selectHTML .='	<option '. $selected .' value="' .$row['id'] . '">' . $row['value'].'</option>';
				$selected = '';
			}
		
		
		
		$selectHTML .='</select></center><p></p>&nbsp;<hr>';
		$mform->addElement('html', $selectHTML);
		
		
	
	$whereQuery = "varname = 'page2form'";
 	$formRecords = get_recordset_select('cmanager_config', $whereQuery, $sort='', $fields='*', 
			                               $limitfrom='', $limitnum='');
										   
	
	$formsItemsHTML = '<table width="200px">';
	foreach($formRecords as $rec){
		$formsItemsHTML .= '<tr>';
		$formsItemsHTML .= '<td><a href="page2.php?id=' . $rec['id'] . '">' . $rec['value']. '</></td>';
		$formsItemsHTML .= '<td><img src="../images/deleteIcon.png" width="20" height="20" alt="delete" / onclick="javascript:deleteSelectedForm(' . $rec['id'] . ');"></td>';
		$formsItemsHTML .= '</tr>';
	}
	
		$formsItemsHTML .= '</table>';
	
	    $mform->addElement('html', '<center>
	    
	    <b>'.get_string('formBuilder_manageFormsText','block_cmanager').'</b>
		<p></p>
		'.get_string('formBuilder_selectAny','block_cmanager').'<p></p>
		
	    '. $formsItemsHTML .'
	    	   <p></p> 
			   <input type="text" id = "newformname" size="20"></input> <input type="button" value = "'.get_string('formBuilder_createNewText','block_cmanager').'" onclick="addNewField()"/></center>');
	}
}


$mform = new courserequest_form();//name of the form you defined in file above.
//default 'action' for form is strip_querystring(qualified_me())
if ($mform->is_cancelled()){
    //you need this section if you have a cancel button on your form
    //here you tell php what to do if your user presses cancel
    //probably a redirect is called for!
    // PLEASE NOTE: is_cancelled() should be called before get_data(), as this may return true
} else if ($fromform=$mform->get_data()){
//this branch is where you process validated data.
 print_header_simple($streditinga, '',

		    
		    "<a href=\"../cmanager_admin.php\">".get_string('cmanagerDisplay','block_cmanager')."</a> ->".get_string('formBuilder_name','block_cmanager')."
		     $strnav $streditinga", $mform->focus(), "", false);
		    
		    $mform->set_data($toform);
		    $mform->display();
		    print_footer();
 
} else {

          print_header_simple($streditinga, '',

		    
		    "<a href=\"../cmanager_admin.php\">".get_string('cmanagerDisplay','block_cmanager')."</a> ->".get_string('formBuilder_name','block_cmanager')."
		     $strnav $streditinga", $mform->focus(), "", false);
		    
		    $mform->set_data($toform);
		    $mform->display();
		    print_footer();
 
}







?>

  
	
		