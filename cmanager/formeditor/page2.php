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
require_once("$CFG->libdir/formslib.php");

require_login();
require_once('../validate_admin.php');

/** Navigation Bar **/
$PAGE->navbar->ignore_active();
$PAGE->navbar->add(get_string('cmanagerDisplay', 'block_cmanager'), new moodle_url('/blocks/cmanager/cmanager_admin.php'));
$PAGE->navbar->add(get_string('configurecoursemanagersettings', 'block_cmanager'), new moodle_url('/blocks/cmanager/cmanager_confighome.php'));
$PAGE->navbar->add(get_string('formpage2', 'block_cmanager'));

$PAGE->set_url('/blocks/cmanager/formeditor/page2.php');
$PAGE->set_context(context_system::instance());
$PAGE->set_heading(get_string('pluginname', 'block_cmanager'));
$PAGE->set_title(get_string('pluginname', 'block_cmanager'));
echo $OUTPUT->header();

$context = context_system::instance();
if (has_capability('block/cmanager:viewconfig',$context)) {
} else {
  print_error(get_string('cannotviewrecords', 'block_cmanager'));
}


if(isset($_GET['id'])){
	$formid = $_GET['id'];
	$current_record =  $DB->get_record('block_cmanager_config', array('id'=>$formid));
  	$formname =  $current_record->value;
	$_SESSION['formid'] = $formid;
}

else {
	echo get_string('formBuilder_p2_error','block_cmanager');
	die;
}
	
	
	$htmloutput = '<br>
	<script>		
		var num = 1; // Used to count the number of fields added.
       var formid = '.$formid .';

       var movedownEnabled = 1;
	   var numberoffields = 0;
    

	//onscreen language variables and default values
	var dropdownTxt = "";
	var radioTxt = "";
	var textAreaTxt = "";
	var textFieldTxt = "";
	var leftTxt = "";
	var saveTxt = "";
	var addedItemsTxt = "";
	var addItemBtnTxt = "";
	
		//Accept values for onscreen language variables from PHP
	function setLangStrings(lang_dropdownTxt,lang_radioTxt,lang_textAreaTxt,lang_textFieldTxt,lang_leftTxt,lang_saveTxt,lang_addedItemsTxt,lang_addItemBtnTxt)
	{

		dropdownTxt = lang_dropdownTxt;
		radioTxt = lang_radioTxt;
		textAreaTxt = lang_textAreaTxt;
		textFieldTxt = lang_textFieldTxt;
		leftTxt = lang_leftTxt;
		saveTxt = lang_saveTxt;
		addedItemsTxt = lang_addedItemsTxt;
		addItemBtnTxt = lang_addItemBtnTxt;

	}
				setLangStrings("'.get_string('formBuilder_dropdownTxt','block_cmanager').'","'.get_string('formBuilder_radioTxt','block_cmanager').'","'.get_string('formBuilder_textAreaTxt','block_cmanager').'","'.get_string('formBuilder_textFieldTxt','block_cmanager').'","'.get_string('formBuilder_leftTxt','block_cmanager').'","'.get_string('formBuilder_saveTxt','block_cmanager').'","'.get_string('formBuilder_addedItemsTxt','block_cmanager').'","'.get_string('formBuilder_addItemBtnTxt','block_cmanager').'")
	
	
function goBack(){
	window.location ="form_builder.php";
}

			</script>
			
	<button type="button" onclick="goBack();"><img src="../icons/back.png"/> '.get_string('back','block_cmanager').'</button><p></p>
	
			<br>
			<br><b>'.get_string('formBuilder_editingForm','block_cmanager').':</b> ' .$formname.'<br><br>
			'.get_string('formBuilder_p2_instructions','block_cmanager').'
			<hr><p></p><br>
	 		'.get_string('formBuilder_p2_addNewField','block_cmanager').':
			<select onchange="addNewField(this);">
			   <option>'.get_string('formBuilder_p2_dropdown1','block_cmanager').'</option>
			   <option value="tf">'.get_string('formBuilder_p2_dropdown2','block_cmanager').'</option>
			   <option value="ta">'.get_string('formBuilder_p2_dropdown3','block_cmanager').'</option>
			   <option value="radio">'.get_string('formBuilder_p2_dropdown4','block_cmanager').'</option>
			   <option value="dropdown">'.get_string('formBuilder_p2_dropdown5','block_cmanager').'</option>
			</select>

			<p></p>
			<br>
			<hr>
			<div style="width: 100%; filter:alpha(Opacity=50); overflow:auto;">
			<div style="background: #9c9c9c;">
			<br>
			<center><b>' .$formname.' '.get_string('formBuilder_shownbelow','block_cmanager').'</b></center><br>
			</div><p></p><br>







			<div id="formdiv" style="width:400px">


			</div>

			<a href="preview.php?id=' . $formid . '">'.get_string('formBuilder_previewForm','block_cmanager').'</a>
			<center><a href="../cmanager_admin.php"><input type="button" value="'.get_string('formBuilder_returntoCM','block_cmanager').'"/></a></center>
		';
		
	echo $htmloutput;	
?>





			<div id="formdiv" style="width:400px">


			</div>


<?php


// Deleting dropdown menus
if(isset($_GET['t']) && isset($_GET['del'])){

  
	if($_GET['t'] == 'dropitem'){ // Delete a dropdown menu item
		$itemid = $_GET['del'];
		$fieldid = $_GET['fid'];
		$DB->delete_records('block_cmanager_form_data', array('fieldid'=>$fieldid,'id'=>$itemid));
	}
	
	if($_GET['t'] == 'drop'){ // Delete all dropdown field items
	
		$fieldid = $_GET['del'];
		$DB->delete_records('block_cmanager_form_data', array('fieldid'=>$fieldid));
	}

}


// Delete Field
if(isset($_GET['del'])){

	$formid = $_GET['id'];
	$delid = $_GET['del'];

    $DB->delete_records_select('block_cmanager_formfields', "id = $delid");

	//Update the position numbers
	$selectquery = "SELECT * FROM ".$CFG->prefix."block_cmanager_formfields WHERE formid = $formid order by id ASC";
	$positionitems = $DB->get_records_sql($selectquery, null);

	$newposition = 1;
	$dataobject = new stdClass();
    foreach($positionitems as $item){

		$dataobject->id = $item->id;
		$dataobject->position = $newposition;
		$DB->update_record('block_cmanager_formfields', $dataobject);

		$newposition++;

	  }


}


// Move field up
if(isset($_GET['up'])){

	$currentid = $_GET['up'];

	$currentrecord = $DB->get_record('block_cmanager_formfields', array('id'=>$currentid), $fields='*', IGNORE_MULTIPLE);
	$currentposition = $currentrecord->position;

	$higherpos = $currentposition-1;
    $higherrecord = $DB->get_record('block_cmanager_formfields', array('position'=>$higherpos), $fields='*', IGNORE_MULTIPLE);

	// Update the records
	$dataobject = new stdClass();
	$dataobject->id = $currentrecord->id;
	$dataobject->position = $higherrecord->position;
	$DB->update_record('block_cmanager_formfields', $dataobject);

	$dataobject2 = new stdClass();
	$dataobject2->id = $higherrecord->id;
	$dataobject2->position = $currentrecord->position;
	$DB->update_record('block_cmanager_formfields', $dataobject2);


}



// Move field down
if(isset($_GET['down'])){

	$currentid = $_GET['down'];

	$currentrecord = $DB->get_record('block_cmanager_formfields', array('id'=>$currentid), $fields='*', IGNORE_MULTIPLE);
	$currentposition = $currentrecord->position;

	$higherpos = $currentposition+1;
    $higherrecord = $DB->get_record('block_cmanager_formfields', array('position'=>$higherpos), $fields='*', IGNORE_MULTIPLE);

	// Update the records
	$dataobject = new stdClass();
	$dataobject->id = $currentrecord->id;
	$dataobject->position = $higherrecord->position;
	$DB->update_record('block_cmanager_formfields', $dataobject);

	$dataobject2 = new stdClass();
	$dataobject2->id = $higherrecord->id;
	$dataobject2->position = $currentrecord->position;
	$DB->update_record('block_cmanager_formfields', $dataobject2);

}


?>


<script src="../js/jquery/jquery-1.7.2.min.js"></script>


<script>

function saveOptionalStatus(id){

		var value1 = document.getElementById('optional_' + id).value;

		$.post("ajax_functions.php", { type: 'saveoptionalvalue', value: value1, id: id },

  		function(data) {
  			alert('<?php echo get_string('ChangesSaved', 'block_cmanager');?>');

	   });

	}

function enableSave(id){

		//alert(id);

		var saveButton = document.getElementById(id);
		saveButton.disabled=!saveButton.disabled;

}

// Select which field type to add based on fval
function addNewField(fval){


	num++;
	var field = fval.value;

	if(field == 'tf' ){
	  createTextField();
	}
	if(field == 'ta'){
		createTextArea();
   	}
   	if(field == 'dropdown'){
   		createDropdown();
   	}
   	if(field == 'radio'){
   		createRadio();
   	}


}

// Create a new blank text field on the page
function createTextField(){


	var ni = document.getElementById('formdiv');
	var newdiv = document.createElement('div');
	//newdiv.style.backgroundColor = "gray";
	newdiv.style.borderWidth = 1;
	newdiv.style.borderStyle = 'dotted';

	newdiv.style.width = 450;
	newdiv.style.height = 110;
    newdiv.style.marginBottom = 5;
    newdiv.style.marginLeft = 5;

	var divIdName = 'my'+num+'Div';
    newdiv.setAttribute('id',num);
    ni.appendChild(newdiv);


    var uniqueId;
    // Add to database
    $.ajaxSetup({async:false});
     $.post("ajax_functions.php", { type: 'page2addfield', fieldtype: 'textfield', formid: formid},
			function(data) {

	

	   });

	
		num++;
	window.location = 'page2.php?id=' + formid; 



}


// If the text field already existed, rebuilt it using data from the db.
function recreateTextField(uniqueId, leftText, requiredFieldValue){


		var ni = document.getElementById('formdiv');
		var newdiv = document.createElement('div');
		//newdiv.style.backgroundColor = "gray";
		newdiv.style.borderWidth = '1px';
		newdiv.style.borderStyle = 'dotted';

		newdiv.style.width = 400;
		newdiv.style.height = 110;
        newdiv.style.marginBottom = 5;
        newdiv.style.marginLeft = 5;

		var divIdName = 'my'+num+'Div';
        newdiv.setAttribute('id',num);
        ni.appendChild(newdiv);

   	    var selectedText = '';
   	    if(requiredFieldValue == '1'){

   	    	       	selectedText = 'selected="selected"';
   	    }


   	    if(numberoffields == 1){
   	    		newdiv.innerHTML = '<b>'+textFieldTxt+':</b> <img src="../icons/move_up_dis.png"   alt="move up" /> '+
   	    		'<img src="../icons/move_down_dis.png"   alt="move down" />  <a href="page2.php?id=' + formid + '&del=' + uniqueId + '"> ' +
   	    		'<img src="../icons/deleteIcon.png"   alt="delete" /></a> '+
   	    		'<select id = "optional_'+uniqueId+'" onchange="saveOptionalStatus('+uniqueId+')">  ' +
   	    		'<option value="0"> <?php echo get_string('optional_field', 'block_cmanager'); ?> </option>   ' +
   	    		'<option '+selectedText+' value="1"> <?php echo get_string('required_field', 'block_cmanager'); ?></option>  ' +
   	    		' </select><p></p> ' +
   	    		' <table><tr><td>'+leftTxt+':</td> ' +
   	    		' <td><input type="text" id = "x'+uniqueId +'" size="30" value="' + leftText+ '" onfocus="enableSave(\''+uniqueId+'_savebtn\');"></input></td></tr> ' +
   	    		' </table> ' +
				'<input type="button" value="'+saveTxt+'" disabled="disabled" id="'+uniqueId+'_savebtn" onclick="saveFieldValue(' + uniqueId+')"/>';

   	    } else {
   	   		 	if(num == 1){
			   	   		newdiv.innerHTML = '<b>'+textFieldTxt+':</b> '+
			   	   		'<img src="../icons/move_up_dis.png"   alt="move up" />  <a href="page2.php?id=' + formid + '&down=' + uniqueId + '">'+
			   	   		'<img src="../icons/move_down.png" width="30" height="30" alt="move down" /></a>  <a href="page2.php?id=' + formid + '&del=' + uniqueId + '">'+
			   	   		'<img src="../icons/deleteIcon.png"   alt="delete" /></a>'+
			   	   		'<select id = "optional_'+uniqueId+'" onchange="saveOptionalStatus('+uniqueId+')"> '+
			   	   		'<option value="0"> <?php echo get_string('optional_field', 'block_cmanager'); ?> </option> '+
			   	   		'<option '+selectedText+' value="1"> <?php echo get_string('required_field', 'block_cmanager'); ?></option> '+
			   	   		 '</select> <p></p>'+
			   	   		 '<table>'+
			   	   		 '<tr>'+
			   	   		 '<td>'+leftTxt+':</td>'+
			   	   		 '<td><input type="text" id = "x'+uniqueId +'" size="30" value="' + leftText+ '" onfocus="enableSave(\''+uniqueId+'_savebtn\');"></input></td></tr></table>'+
			   	   		 '<input type="button" value="'+saveTxt+'" disabled="disabled" id="'+uniqueId+'_savebtn" onclick="saveFieldValue(' + uniqueId+')"/> ';
			       	}
					else if(movedownEnabled == 0){
						 newdiv.innerHTML = '<b>'+textFieldTxt+':</b> <a href="page2.php?id=' + formid + '&up=' + uniqueId + '">'+
						 '<img src="../icons/move_up.png"   alt="move up" /></a>   '+
						 '<img src="../icons/move_down_dis.png"   alt="move down" />   <a href="page2.php?id=' + formid + '&del=' + uniqueId + '"> '+
						 '<img src="../icons/deleteIcon.png"   alt="delete" /></a> '+
						 '<select id = "optional_'+uniqueId+'" onchange="saveOptionalStatus('+uniqueId+')"> '+
						 '<option value="0"> <?php echo get_string('optional_field', 'block_cmanager'); ?> </option> '+
						 '<option '+selectedText+' value="1"> <?php echo get_string('required_field', 'block_cmanager'); ?></option>'+
						 '</select><p></p><table><tr><td>'+leftTxt+':</td>'+
						 '<td><input type="text" id = "x'+uniqueId +'" size="30" value="' + leftText+ '" onfocus="enableSave(\''+uniqueId+'_savebtn\');"></input></td></tr></table>'+
						 '<input type="button" value="'+saveTxt+'" disabled="disabled" id="'+uniqueId+'_savebtn" onclick="saveFieldValue(' + uniqueId+')"/>';
			       	}
				    else {
				    	 newdiv.innerHTML = '<b>'+textFieldTxt+':</b> <a href="page2.php?id=' + formid + '&up=' + uniqueId + '">'+
				    	 '<img src="../icons/move_up.png"   alt="move up" /></a>'+ 
				    	 ' <a href="page2.php?id=' + formid + '&down=' + uniqueId + '">'+
				    	 ' <img src="../icons/move_down.png"   alt="move down" /></a>'+
				    	 ' <a href="page2.php?id=' + formid + '&del=' + uniqueId + '">'+
				    	 ' <img src="../icons/deleteIcon.png"   alt="delete" /></a>'+
				    	 '<select id = "optional_'+uniqueId+'" onchange="saveOptionalStatus('+uniqueId+')"> '+
				    	 '	<option value="0"> <?php echo get_string('optional_field', 'block_cmanager'); ?> </option> '+
				    	 '	 <option '+selectedText+' value="1"> <?php echo get_string('required_field', 'block_cmanager'); ?></option> '+
				    	 '	 </select>'+
				    	 ' <p></p><table><tr><td>'+leftTxt+':</td><td>'+
				    	 '<input type="text" id = "x'+uniqueId +'" size="30" value="' + leftText+ '" onfocus="enableSave(\''+uniqueId+'_savebtn\');"></input></td></tr></table>'+
				    	 '<input type="button" value="'+saveTxt+'" disabled="disabled" id="'+uniqueId+'_savebtn" onclick="saveFieldValue(' + uniqueId+')"/>';
			       	}

       		}
       			num++;
}

	// Create a new blank text field on the page
function createTextArea(){


		var ni = document.getElementById('formdiv');
		var newdiv = document.createElement('div');
		//newdiv.style.backgroundColor = "gray";
		newdiv.style.borderWidth = '1px';
		newdiv.style.borderStyle = 'dotted';

		newdiv.style.width = 450;
		newdiv.style.height = 110;
        newdiv.style.marginBottom = 5;
        newdiv.style.marginLeft = 5;

		var divIdName = 'my'+num+'Div';
        newdiv.setAttribute('id',num);
        ni.appendChild(newdiv);


        var uniqueId;
        // Add to database
        $.ajaxSetup({async:false});
         $.post("ajax_functions.php", { type: 'page2addfield', fieldtype: 'textarea', formid: formid},
				function(data) {

					
		   });


		   num++;

		  window.location = 'page2.php?id=' + formid;
}




// This function is used to take the value from the textfield beside the "Add New item"
// button on dropdown menus
function addNewItem(id){

//alert(id);

var value = document.getElementById('newitem'+id).value;


 $.post("ajax_functions.php", { value: value, id: id, type: 'addvaluetodropdown'},

		function(data) {
 		//alert("Data Loaded: " + data);
   });

 //alert('A new item has been added: ' + value);
   window.location = 'page2.php?id=' + formid;
   
   
   
}



// If the text field already existed, rebuilt it using data from the db.
function recreateTextArea(uniqueId, leftText, requiredFieldValue){


		var ni = document.getElementById('formdiv');
		var newdiv = document.createElement('div');
		//newdiv.style.backgroundColor = "gray";
		newdiv.style.borderWidth = '1px';
		newdiv.style.borderStyle = 'dotted';

		newdiv.style.width = 400;
		newdiv.style.height = 110;
        newdiv.style.marginBottom = 5;
        newdiv.style.marginLeft = 5;

		var divIdName = 'my'+num+'Div';
        newdiv.setAttribute('id',num);
        ni.appendChild(newdiv);

   	   var selectedText = '';
   	    if(requiredFieldValue == '1'){

   	    	       	selectedText = 'selected="selected"';
   	    }


   	   if(numberoffields == 1){
   	    		newdiv.innerHTML = '<b>'+textAreaTxt+':</b> <img src="../icons/move_up_dis.png"   alt="move up" /> <img src="../icons/move_down_dis.png"   alt="move down" />  <a href="page2.php?id=' + formid + '&del=' + uniqueId + '"><img src="../icons/deleteIcon.png"   alt="delete" /></a> '+
   	   	 						 '<select id = "optional_'+uniqueId+'" onchange="saveOptionalStatus('+uniqueId+')"> <option value="0"> <?php echo get_string('optional_field', 'block_cmanager'); ?> </option>  <option '+selectedText+' value="1"> <?php echo get_string('required_field', 'block_cmanager'); ?></option>  </select>'+
   	    						'<p></p><table><tr><td>'+leftTxt+':</td><td><input type="text" id = "x'+uniqueId +'" size="30" value="' + leftText+ '" onfocus="enableSave(\''+uniqueId+'_savebtn\');"></input></td></tr></table><input type="button" value="'+saveTxt+'" disabled="disabled" id="'+uniqueId+'_savebtn" onclick="saveFieldValue(' + uniqueId+')"/>';


   	    } else {
				   	   	if(num == 1){
					     		 newdiv.innerHTML = '<b>'+textAreaTxt+':</b> <img src="../icons/move_up_dis.png"   alt="move up"/>  <a href="page2.php?id=' + formid + '&down=' + uniqueId + '">'+
					     		 '<img src="../icons/move_down.png"   alt="move down" /></a>  <a href="page2.php?id=' + formid + '&del=' + uniqueId + '"> '+
					     		 '<img src="../icons/deleteIcon.png"   alt="delete" /></a> '+
					     		 '<select id = "optional_'+uniqueId+'" onchange="saveOptionalStatus('+uniqueId+')"> <option value="0"> <?php echo get_string('optional_field', 'block_cmanager'); ?> </option>  <option '+selectedText+' value="1"> <?php echo get_string('required_field', 'block_cmanager'); ?></option>  </select><p></p><table><tr><td>'+leftTxt+':</td><td><input type="text" id = "x'+ uniqueId+ '" value = "' + leftText+ '" size="30" onfocus="enableSave(\''+uniqueId+'_savebtn\');"></input></td></tr></table><input type="button" value="'+saveTxt+'" disabled="disabled" id="'+uniqueId+'_savebtn" onclick="saveFieldValue(' + uniqueId+')"/>';
				       			}
				       	else if(movedownEnabled == 0){
				       	 		newdiv.innerHTML = '<b>'+textAreaTxt+':</b> <a href="page2.php?id=' + formid + '&up=' + uniqueId + '"> ' +
				       	 		'<img src="../icons/move_up.png"   alt="move up" /></a> <img src="../icons/move_down_dis.png"   alt="move down" /> <a href="page2.php?id=' + formid + '&del=' + uniqueId + '"> '+
				       	 		'<img src="../icons/deleteIcon.png"   alt="delete" /></a> '+
				       	 		'<select id = "optional_'+uniqueId+'" onchange="saveOptionalStatus('+uniqueId+')"> <option value="0"> <?php echo get_string('optional_field', 'block_cmanager'); ?> </option>  <option '+selectedText+' value="1"> <?php echo get_string('required_field', 'block_cmanager'); ?></option>  </select><p></p><table><tr><td>'+leftTxt+':</td><td><input type="text" id = "x'+uniqueId +'" size="30" value="' + leftText+ '" onfocus="enableSave(\''+uniqueId+'_savebtn\');"></input></td></tr></table><input type="button" value="'+saveTxt+'" disabled="disabled" id="'+uniqueId+'_savebtn" onclick="saveFieldValue(' + uniqueId+')"/>';

				       	}
				       	else {
				       	 		newdiv.innerHTML = '<b>'+textAreaTxt+':</b> <a href="page2.php?id=' + formid + '&up=' + uniqueId + '"> '+
				       	 		'<img src="../icons/move_up.png"   alt="move up" /></a> <a href="page2.php?id=' + formid + '&down=' + uniqueId + '"> '+
				       	 		'<img src="../icons/move_down.png"   alt="move down" /></a>  <a href="page2.php?id=' + formid + '&del=' + uniqueId + '"> '+
				       	 		'<img src="../icons/deleteIcon.png"   alt="delete" /></a> '+
				       	 		'<select id = "optional_'+uniqueId+'" onchange="saveOptionalStatus('+uniqueId+')"> <option value="0"> <?php echo get_string('optional_field', 'block_cmanager'); ?> </option>  <option '+selectedText+' value="1"> <?php echo get_string('required_field', 'block_cmanager'); ?></option>  </select><p></p><table><tr><td>'+leftTxt+':</td><td><input type="text" id = "x'+uniqueId +'" size="30" value="' + leftText+ '" onfocus="enableSave(\''+uniqueId+'_savebtn\');"></input></td></tr></table><input type="button" value="'+saveTxt+'" disabled="disabled" id="'+uniqueId+'_savebtn" onclick="saveFieldValue(' + uniqueId+')"/>';

				       			}
       		}
       			num++;
}



		// Create a new blank text field on the page
function createDropdown(){

	  var fieldsInHTML = '';
	  var leftText = '';
		var ni = document.getElementById('formdiv');
		var newdiv = document.createElement('div');
		//newdiv.style.backgroundColor = "gray";
		newdiv.style.borderWidth = '1px';
		newdiv.style.borderStyle = 'dotted';

		newdiv.style.width = 450;
		newdiv.style.height = 400;
        newdiv.style.marginBottom = 5;
        newdiv.style.marginLeft = 5;

		var divIdName = 'my'+num+'Div';
        newdiv.setAttribute('id',num);
        ni.appendChild(newdiv);


        var uniqueId;
        // Add to database
        $.ajaxSetup({async:false});
         $.post("ajax_functions.php", { type: 'page2addfield', fieldtype: 'dropdown', formid: formid},
				function(data) {
	     		uniqueId = data;
	     		

		   });


		   num++;
		    window.location = 'page2.php?id=' + formid;
}


// If the text field already existed, rebuilt it using data from the db.
function recreateDropdown(uniqueId, leftText, requiredFieldValue){

	  var fieldsInHTML = 'No fields added..';


       // Get the values for the dropdown menu
        $.ajaxSetup({async:false});
        $.post("ajax_functions.php", { id: uniqueId, type: 'getdropdownvalues'},

   		function(data) {
     		fieldsInHTML = data;

		var ni = document.getElementById('formdiv');
		var newdiv = document.createElement('div');
		//newdiv.style.backgroundColor = "gray";
		newdiv.style.borderWidth = '1px';
		newdiv.style.borderStyle = 'dotted';

		newdiv.style.width = 400;
		newdiv.style.height = 400;
        newdiv.style.marginBottom = 5;
        newdiv.style.marginLeft = 5;

		var divIdName = 'my'+num+'Div';
        newdiv.setAttribute('id',num);
        ni.appendChild(newdiv);

   	   var selectedText = '';
   	    if(requiredFieldValue == '1'){

   	    	       	selectedText = 'selected="selected"';
   	    }


   	   if(numberoffields == 1){
   	    		//newdiv.innerHTML = '<b>'+dropdownTxt+':</b> <img src="../icons/move_up_dis.png"   alt="move up" /> <img src="../icons/move_down_dis.png"   alt="move down" />  <a href="page2.php?id=' + formid + '&del=' + uniqueId + '"><img src="../icons/deleteIcon.png"   alt="delete" /></a> <p></p><table><tr><td>'+leftTxt+':</td><td><input type="text" id = "'+uniqueId +'" size="30" value="' + leftText+ '" onfocus="enableSave(\''+uniqueId+'_savebtn\');"></input></td></tr></table><input type="button" value="'+saveTxt+'" disabled="disabled" id="'+uniqueId+'_savebtn" onclick="saveFieldValue(' + uniqueId+')"/>';
			     newdiv.innerHTML = '<b>'+dropdownTxt+':</b><img src="../icons/move_up_dis.png"   alt="move up" /><a href="page2.php?id=' + formid + '&down=' + uniqueId + '"> '+
			     '<img src="../icons/move_down.png"   alt="move down" /></a> <a href="page2.php?id=' + formid + '&t=drop&del=' + uniqueId + '"> '+
			     '<img src="../icons/deleteIcon.png"   alt="delete" /></a> '+
			     '<select id = "optional_'+uniqueId+'" onchange="saveOptionalStatus('+uniqueId+')"> <option value="0"> <?php echo get_string('optional_field', 'block_cmanager'); ?> </option>  <option '+selectedText+' value="1"> <?php echo get_string('required_field', 'block_cmanager'); ?></option>  </select><p></p><table><tr><td>'+leftTxt+':</td><td><input type="text" id = "x'+uniqueId +'" size="30" value="' + leftText+ '" onfocus="enableSave(\''+uniqueId+'_savebtn\');"></input></td></tr></table><input type="button" value="'+saveTxt+'" disabled="disabled" id="'+uniqueId+'_savebtn" onclick="saveFieldValue(' + uniqueId+')"/><p></p> <input type="text" id="newitem'+uniqueId +'"></input><input type="button" name="submitbutton" value="'+addItemBtnTxt+'" onclick="addNewItem('+ uniqueId +');"><p></p>'+addedItemsTxt+':<p></p>' + fieldsInHTML;

   	    } else {

				   	   	if(num == 1){
			       					newdiv.innerHTML = '<b>'+dropdownTxt+':</b><img src="../icons/move_up_dis.png"   alt="move up" /><a href="page2.php?id=' + formid + '&down=' + uniqueId + '"> '+
			       					'<img src="../icons/move_down.png"   alt="move down" /></a> <a href="page2.php?id=' + formid + '&t=drop&del=' + uniqueId + '"> ' +
			       					'<img src="../icons/deleteIcon.png"   alt="delete" /></a> '+
			       					'<select id = "optional_'+uniqueId+'" onchange="saveOptionalStatus('+uniqueId+')"> <option value="0"> <?php echo get_string('optional_field', 'block_cmanager'); ?> </option>  <option '+selectedText+' value="1"> <?php echo get_string('required_field', 'block_cmanager'); ?></option>  </select><p></p><table><tr><td>'+leftTxt+':</td><td><input type="text" id = "x'+uniqueId +'" size="30" value="' + leftText+ '" onfocus="enableSave(\''+uniqueId+'_savebtn\');"></input></td></tr></table><input type="button" value="'+saveTxt+'" disabled="disabled" id="'+uniqueId+'_savebtn" onclick="saveFieldValue(' + uniqueId+')"/><p></p> <input type="text" id="newitem'+uniqueId +'"></input><input type="button" name="submitbutton" value="'+addItemBtnTxt+'" onclick="addNewItem('+ uniqueId +');"><p></p>'+addedItemsTxt+':<p></p>' + fieldsInHTML;
				       	}
				       	else if(movedownEnabled == 0){
				       		newdiv.innerHTML = '<b>'+dropdownTxt+':</b> <a href="page2.php?id=' + formid + '&up=' + uniqueId + '"> '+
				       		'<img src="../icons/move_up.png"   alt="move up" /></a> '+
				       		'<img src="../icons/move_down_dis.png"   alt="move down" /> <a href="page2.php?id=' + formid + '&t=drop&del=' + uniqueId + '"> '+
				       		'<img src="../icons/deleteIcon.png"   alt="delete" /></a>'+
				       		'<select id = "optional_'+uniqueId+'" onchange="saveOptionalStatus('+uniqueId+')"> <option value="0"> <?php echo get_string('optional_field', 'block_cmanager'); ?> </option>  <option '+selectedText+' value="1"> <?php echo get_string('required_field', 'block_cmanager'); ?></option>  </select> <p></p><table><tr><td>'+leftTxt+':</td><td><input type="text" id = "x'+uniqueId +'" size="30" value="' + leftText+ '" onfocus="enableSave(\''+uniqueId+'_savebtn\');"></input></td></tr></table><input type="button" value="'+saveTxt+'" disabled="disabled" id="'+uniqueId+'_savebtn" onclick="saveFieldValue(' + uniqueId+')"/><p></p> <input type="text" id="newitem'+uniqueId +'"></input><input type="button" name="submitbutton" value="'+addItemBtnTxt+'" onclick="addNewItem('+ uniqueId +');"><p></p>'+addedItemsTxt+':<p></p>' + fieldsInHTML;

				       	} else {

				       	 		newdiv.innerHTML = '<b>'+dropdownTxt+':</b> <a href="page2.php?id=' + formid + '&up=' + uniqueId + '"> '+
				       	 		'<img src="../icons/move_up.png"   alt="move up" /></a>  <a href="page2.php?id=' + formid + '&down=' + uniqueId + '"> '+
				       	 		'<img src="../icons/move_down.png"   alt="move down" /></a>  <a href="page2.php?id=' + formid + '&t=drop&del=' + uniqueId + '"> '+
				       	 		'<img src="../icons/deleteIcon.png"   alt="delete" /></a> '+
				       	 		'<select id = "optional_'+uniqueId+'" onchange="saveOptionalStatus('+uniqueId+')"> <option value="0"> <?php echo get_string('optional_field', 'block_cmanager'); ?> </option>  <option '+selectedText+' value="1"> <?php echo get_string('required_field', 'block_cmanager'); ?></option>  </select><p></p><table><tr><td>'+leftTxt+':</td><td><input type="text" id = "x'+uniqueId +'" size="30" value="' + leftText+ '" onfocus="enableSave(\''+uniqueId+'_savebtn\');"></input></td></tr></table><input type="button" value="'+saveTxt+'" disabled="disabled" id="'+uniqueId+'_savebtn" onclick="saveFieldValue(' + uniqueId+')"/><p></p> <input type="text" id="newitem'+uniqueId +'"></input><input type="button" name="submitbutton" value="'+addItemBtnTxt+'" onclick="addNewItem('+ uniqueId +');"><p></p>'+addedItemsTxt+':<p></p>' + fieldsInHTML;

				       			}
       			}
	   });

       			num++;
}


function createRadio(){

	  var fieldsInHTML = '';
	  var leftText = '';
		var ni = document.getElementById('formdiv');
		var newdiv = document.createElement('div');
		//newdiv.style.backgroundColor = "gray";
		newdiv.style.borderWidth = '1px';
		newdiv.style.borderStyle = 'dotted';

		newdiv.style.width = 450;
		newdiv.style.height = 400;
        newdiv.style.marginBottom = 5;
        newdiv.style.marginLeft = 5;

		var divIdName = 'my'+num+'Div';
        newdiv.setAttribute('id',num);
        ni.appendChild(newdiv);


        var uniqueId;
        // Add to database
        $.ajaxSetup({async:false});
         $.post("ajax_functions.php", { type: 'page2addfield', fieldtype: 'radio', formid: formid},
				function(data) {
	     		uniqueId = data;
	     		

		   });


		   num++;
		    window.location = 'page2.php?id=' + formid;
}


// If the text field already existed, rebuilt it using data from the db.
function recreateRadio(uniqueId, leftText, requiredFieldValue){

	  var fieldsInHTML = 'No fields added..';



       // Get the values for the dropdown menu
        $.ajaxSetup({async:false});
        $.post("ajax_functions.php", { id: uniqueId, type: 'getdropdownvalues'},

   		function(data) {

		fieldsInHTML = data;
		var ni = document.getElementById('formdiv');
		var newdiv = document.createElement('div');
		//newdiv.style.backgroundColor = "gray";
		newdiv.style.borderWidth = '1px';
		newdiv.style.borderStyle = 'dotted';

		newdiv.style.width = 400;
		newdiv.style.height = 400;
        newdiv.style.marginBottom = 5;
        newdiv.style.marginLeft = 5;
		newdiv.style.overflow = 'auto';
		var divIdName = 'my'+num+'Div';
        newdiv.setAttribute('id',num);
        ni.appendChild(newdiv);

   	var selectedText = '';
   	    if(requiredFieldValue == '1'){

   	    	       	selectedText = 'selected="selected"';
   	    }


	   if(numberoffields == 1){
	      // newdiv.innerHTML = '<b>'+radioTxt+':</b> <img src="../icons/move_up_dis.png"   alt="move up" /><img src="../icons/move_down_dis.png"   alt="move down" /> <a href="page2.php?id=' + formid + '&del=' + uniqueId + '"><img src="../icons/deleteIcon.png"   alt="delete" /></a> <p></p><table><tr><td>'+leftTxt+':</td><td><input type="text" id = "'+uniqueId +'" size="30" value="' + leftText+ '" onfocus="enableSave(\''+uniqueId+'_savebtn\');"></input></td></tr></table><input type="button" value="'+saveTxt+'" disabled="disabled" id="'+uniqueId+'_savebtn" onclick="saveFieldValue(' + uniqueId+')"/>';
		newdiv.innerHTML = '<b>'+radioTxt+':</b>  <img src="../icons/move_up_dis.png"   alt="move up" /> <a href="page2.php?id=' + formid + '&down=' + uniqueId + '"> '+
		'<img src="../icons/move_down.png"   alt="move down" /></a>  <a href="page2.php?id=' + formid + '&del=' + uniqueId + '"> '+
		'<img src="../icons/deleteIcon.png"   alt="delete" /></a> '+
		'<select id = "optional_'+uniqueId+'" onchange="saveOptionalStatus('+uniqueId+')"> <option value="0"> <?php echo get_string('optional_field', 'block_cmanager'); ?> </option>  <option '+selectedText+' value="1"> <?php echo get_string('required_field', 'block_cmanager'); ?></option>  </select><p></p><table><tr><td>'+leftTxt+':</td><td><input type="text" id = "x'+uniqueId +'" size="30" value="' + leftText+ '" onfocus="enableSave(\''+uniqueId+'_savebtn\');"></input></td></tr></table><input type="button" value="'+saveTxt+'" disabled="disabled" id="'+uniqueId+'_savebtn" onclick="saveFieldValue(' + uniqueId+')"/><p></p> <input type="text" id="newitem'+uniqueId +'"></input><input type="button" name="submitbutton" value="'+addItemBtnTxt+'" onclick="addNewItem('+ uniqueId +');"><p></p>'+addedItemsTxt+':<p></p>' + fieldsInHTML;

	} else {


   	   	if(num == 1){
				newdiv.innerHTML = '<b>'+radioTxt+':</b>  <img src="../icons/move_up_dis.png"   alt="move up" /> <a href="page2.php?id=' + formid + '&down=' + uniqueId + '"> '+
				'<img src="../icons/move_down.png"   alt="move down" /></a>  <a href="page2.php?id=' + formid + '&del=' + uniqueId + '"> '+
				'<img src="../icons/deleteIcon.png"   alt="delete" /></a> '+
				'<select id = "optional_'+uniqueId+'" onchange="saveOptionalStatus('+uniqueId+')"> <option value="0"> <?php echo get_string('optional_field', 'block_cmanager'); ?> </option>  <option '+selectedText+' value="1"> <?php echo get_string('required_field', 'block_cmanager'); ?></option>  </select><p></p><table><tr><td>'+leftTxt+':</td><td><input type="text" id = "x'+uniqueId +'" size="30" value="' + leftText+ '" onfocus="enableSave(\''+uniqueId+'_savebtn\');"></input></td></tr></table><input type="button" value="'+saveTxt+'" disabled="disabled" id="'+uniqueId+'_savebtn" onclick="saveFieldValue(' + uniqueId+')"/><p></p> <input type="text" id="newitem'+uniqueId +'"></input><input type="button" name="submitbutton" value="'+addItemBtnTxt+'" onclick="addNewItem('+ uniqueId +');"><p></p>'+addedItemsTxt+':<p></p>' + fieldsInHTML;


				}
       	else if(movedownEnabled == 0){
       				newdiv.innerHTML = '<b>'+radioTxt+':</b> <a href="page2.php?id=' + formid + '&up=' + uniqueId + '"> '+
       				'<img src="../icons/move_up.png"   alt="move up" /></a> <img src="../icons/move_down_dis.png"   alt="move down" /> <a href="page2.php?id=' + formid + '&t=drop&del=' + uniqueId + '"> '+
       				'<img src="../icons/deleteIcon.png"   alt="delete" /></a> '+
       				'<select id = "optional_'+uniqueId+'" onchange="saveOptionalStatus('+uniqueId+')"> <option value="0"> <?php echo get_string('optional_field', 'block_cmanager'); ?> </option>  <option '+selectedText+' value="1"> <?php echo get_string('required_field', 'block_cmanager'); ?></option>  </select><p></p><table><tr><td>'+leftTxt+':</td><td><input type="text" id = "x'+uniqueId +'" size="30" value="' + leftText+ '" onfocus="enableSave(\''+uniqueId+'_savebtn\');"></input></td></tr></table><input type="button" value="'+saveTxt+'" disabled="disabled" id="'+uniqueId+'_savebtn" onclick="saveFieldValue(' + uniqueId+')"/><p></p> <input type="text" id="newitem'+uniqueId +'"></input><input type="button" name="submitbutton" value="'+addItemBtnTxt+'" onclick="addNewItem('+ uniqueId +');"><p></p>'+addedItemsTxt+':<p></p>' + fieldsInHTML;

       	} else {
       	 			newdiv.innerHTML = '<b>'+radioTxt+':</b> <a href="page2.php?id=' + formid + '&up=' + uniqueId + '"> '+
       	 			'<img src="../icons/move_up.png"   alt="move up" /></a>  <a href="page2.php?id=' + formid + '&down=' + uniqueId + '"> '+
       	 			'<img src="../icons/move_down.png"   alt="move down" /></a>  <a href="page2.php?id=' + formid + '&t=drop&del=' + uniqueId + '"> '+
       	 			'<img src="../icons/deleteIcon.png"   alt="delete" /></a> '+
       	 			'<select id = "optional_'+uniqueId+'" onchange="saveOptionalStatus('+uniqueId+')"> <option value="0"> <?php echo get_string('optional_field', 'block_cmanager'); ?> </option>  <option '+selectedText+' value="1"> <?php echo get_string('required_field', 'block_cmanager'); ?></option>  </select><p></p><table><tr><td>'+leftTxt+':</td><td><input type="text" id = "x'+uniqueId +'" size="30" value="' + leftText+ '" onfocus="enableSave(\''+uniqueId+'_savebtn\');"></input></td></tr></table><input type="button" value="'+saveTxt+'" disabled="disabled" id="'+uniqueId+'_savebtn" onclick="saveFieldValue(' + uniqueId+')"/><p></p> <input type="text" id="newitem'+uniqueId +'"></input><input type="button" name="submitbutton" value="'+addItemBtnTxt+'" onclick="addNewItem('+ uniqueId +');"><p></p>'+addedItemsTxt+':<p></p>' + fieldsInHTML;

       			}
       			}
	   });

       			num++;
}


// Saves the text field data to the database
// by passing the field id.
function saveFieldValue(id){


	var value = document.getElementById('x' + id).value;

	//alert("value: " +value);
    
    var currentid = id;
    
   $.ajaxSetup({async:false});
    $.post("ajax_functions.php", { type: 'updatefield', id: currentid, value: value},
				function(data) {
					alert('<?php echo get_string('changeshavebeensaved', 'block_cmanager'); ?>');

		   });



 window.location = 'page2.php?id=' + formid;

}
</script>

<?php
// If any fields currently exist, add them to the page for editing
$selectquery = "";

// Count the total number of records
$numberoffields = $DB->count_records('block_cmanager_formfields', array('formid'=>$formid));
echo '<script>numberoffields = '.$numberoffields.';</script>';

//$formfields = $DB->get_records('block_cmanager_formfields', 'formid', $formid, $sort='position ASC', $fields='*', $limitfrom='', $limitnum='');
$formfields = $DB->get_records('block_cmanager_formfields', array('formid'=>$formid), 'position ASC');


$reccounter = 1;
foreach ($formfields as $field) {

    // If we are on the last record, disable the move down option.
	if ($numberoffields == $reccounter || $numberoffields == 1) {
        echo '<script>movedownEnabled = 0;</script>';
    }
    if ($field->type == 'textfield') {
		echo "<script>
		       recreateTextField('". $field->id ."', '". $field->lefttext ."', '". $field->reqfield ."');
	          </script>";
	}
	else if ($field->type == 'textarea') {
	    echo "<script>
		       recreateTextArea('". $field->id ."', '". $field->lefttext ."', '". $field->reqfield ."');
	      </script>
	      ";
	}
	else if ($field->type == 'dropdown') {
	     echo "<script>
		       recreateDropdown('". $field->id ."', '". $field->lefttext ."', '". $field->reqfield ."');
	      </script>
	      ";
	}
    else if ($field->type == 'radio') {
	   	echo "<script>
		       recreateRadio('". $field->id ."', '". $field->lefttext ."', '". $field->reqfield ."');
	      </script>
	      ";
	 }

	 $reccounter++;
}

echo $OUTPUT->footer();

