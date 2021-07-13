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
// Copyright 2012-2018 - Institute of Technology Blanchardstown.
// ---------------------------------------------------------
/**
 * COURSE REQUEST MANAGER
  *
 * @package    block_cmanager
 * @copyright  2018 Kyle Goslin, Daniel McSweeney
 * @copyright  2021 Michael Milette (TNG Consulting Inc.), Daniel Keaman
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once("../../../config.php");
global $CFG, $DB;
require_once("$CFG->libdir/formslib.php");

require_login();
require_once('../validate_admin.php');
require_once('../lib/boot.php');

/** Navigation Bar **/
$PAGE->navbar->ignore_active();
$PAGE->navbar->add(get_string('cmanagerDisplay', 'block_cmanager'), new moodle_url('/blocks/cmanager/cmanager_admin.php'));
$PAGE->navbar->add(get_string('configurecoursemanagersettings', 'block_cmanager'), new moodle_url('/blocks/cmanager/cmanager_confighome.php'));
$PAGE->navbar->add(get_string('formpage2', 'block_cmanager'));
$mid = optional_param('id', '', PARAM_INT);
$PAGE->set_url('/blocks/cmanager/formeditor/page2.php', ['id' => $mid]);
$PAGE->set_context(context_system::instance());
$PAGE->set_heading(get_string('formBuilder_name', 'block_cmanager'));
$PAGE->set_title(get_string('formBuilder_name', 'block_cmanager'));
echo $OUTPUT->header();

$context = context_system::instance();
if (has_capability('block/cmanager:viewconfig',$context)) {
} else {
  print_error(get_string('cannotviewrecords', 'block_cmanager'));
}

// Deleting dropdown menus
if(isset($_GET['t']) && isset($_GET['del'])){


	if($_GET['t'] == 'dropitem'){ // Delete a dropdown menu item
		//$itemid = $_GET['del'];
        $itemid = required_param('del', PARAM_INT);


        //$fieldid = $_GET['fid'];
        $fieldid = required_param('fid', PARAM_INT);
		$DB->delete_records('block_cmanager_form_data', array('fieldid'=>$fieldid,'id'=>$itemid));
	}

	if($_GET['t'] == 'drop'){ // Delete all dropdown field items

		//$fieldid = $_GET['del'];
        $fieldid =  required_param('del', PARAM_INT);
		$DB->delete_records('block_cmanager_form_data', array('fieldid'=>$fieldid));
	}

}


// Delete Field
if(isset($_GET['del'])){

	$formid =  required_param('id', PARAM_INT);
	$delid =  required_param('del', PARAM_INT);

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


    $currentid =  required_param('up', PARAM_INT);
    $formid =  required_param('id', PARAM_INT);
    // current record being moved
	$currentrecord = $DB->get_record('block_cmanager_formfields', array('id'=>$currentid, 'formid'=>$formid), $fields='*', IGNORE_MULTIPLE);

	$currentposition = $currentrecord->position;


    // record above the one being moved
	$higherpos = ($currentposition-1);
    $higherrecord = $DB->get_record('block_cmanager_formfields', array('position'=>$higherpos, 'formid'=>$formid), $fields='*', IGNORE_MULTIPLE);

	// Update the current record
	$dataobject = new stdClass();
	$dataobject->id = $currentrecord->id;
	$dataobject->position = $currentposition - 1;
	$DB->update_record('block_cmanager_formfields', $dataobject);

    // update the record above
	$dataobject2 = new stdClass();
	$dataobject2->id = $higherrecord->id;
	$dataobject2->position = $currentrecord->position;
	$DB->update_record('block_cmanager_formfields', $dataobject2);


}



// Move field down
if(isset($_GET['down'])){


    $currentid =  required_param('down', PARAM_INT);
    $formid =  required_param('id', PARAM_INT);

	$currentrecord = $DB->get_record('block_cmanager_formfields', array('id'=>$currentid, 'formid'=>$formid), $fields='*', IGNORE_MULTIPLE);
	$currentposition = $currentrecord->position;

	$higherpos = $currentposition+1;
    $higherrecord = $DB->get_record('block_cmanager_formfields', array('position'=>$higherpos, 'formid'=>$formid), $fields='*', IGNORE_MULTIPLE);

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


$htmloutput = '
    <script src="../js/jquery/jquery-3.3.1.min.js"></script>
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
    </script>

    <a class="btn btn-default" href="form_builder.php"><img src="../icons/back.png"/> '.get_string('back','block_cmanager').'</a>

    <h2>' . get_string('formBuilder_editingForm','block_cmanager') . ': ' .$formname.'</h2>
    <p>' . get_string('formBuilder_p2_instructions','block_cmanager') . '</p>

    <div id="formdiv"></div>
    <label for="newfieldselect">'.get_string('formBuilder_p2_addNewField','block_cmanager').':</label>
    <select name="newfieldselect" id="newfieldselect" onchange="addNewField(this);">
        <option>'.get_string('formBuilder_p2_dropdown1','block_cmanager').'</option>
        <option value="tf">'.get_string('formBuilder_p2_dropdown2','block_cmanager').'</option>
        <option value="ta">'.get_string('formBuilder_p2_dropdown3','block_cmanager').'</option>
        <option value="radio">'.get_string('formBuilder_p2_dropdown4','block_cmanager').'</option>
        <option value="dropdown">'.get_string('formBuilder_p2_dropdown5','block_cmanager').'</option>
    </select>
    <p class="mt-3">
        <a class="btn btn-default" href="preview.php?id=' . $formid . '">' . get_string('formBuilder_previewForm','block_cmanager') . '</a>
        <a class="btn btn-default" href="../cmanager_admin.php">' . get_string('formBuilder_returntoCM','block_cmanager') . '</a>
    </p>
    ';
	$htmloutput .= generateGenericPop('saved', get_string('ChangesSaved','block_cmanager'), get_string('ChangesSaved','block_cmanager'), get_string('ok','block_cmanager') );
	echo $htmloutput;
?>
<script>
// Save the form field status as either a required field
// or an optional field. Display a modal after saving.
function saveOptionalStatus(id){

		var value1 = document.getElementById('optional_' + id).value;

		$.post("ajax_functions.php", { type: 'saveoptionalvalue', value: value1, id: id },

  		function(data) {
  			//alert('<?php echo get_string('ChangesSaved', 'block_cmanager');?>');
            $("#saved").modal();
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

	//alert(data);

	   });


		num++;
	window.location = 'page2.php?id=' + formid;



}


// If the text field already existed, rebuilt it using data from the db.
function recreateTextField(uniqueId, leftText, requiredFieldValue){

    // leftText = decodeURI(leftText);
    var ni = document.getElementById('formdiv');
    var newdiv = document.createElement('div');
    //newdiv.style.backgroundColor = "gray";
    newdiv.style.borderBottomWidth = '1px';
    newdiv.style.borderBottomStyle = 'dotted';

    newdiv.style.width = 400;
    newdiv.style.height = 110;
    newdiv.style.margin = '5px';

    var divIdName = 'my'+num+'Div';
    newdiv.setAttribute('id',num);
    ni.appendChild(newdiv);

    var selectedText = '';
    if(requiredFieldValue == '1'){
        selectedText = 'selected';
    }

    var icons;
    if(numberoffields == 1) {
        icons = '<a href="page2.php?id=' + formid + '&del=' + uniqueId + '"><i class="icon fa fa-trash fa-fw " title="<?php echo get_string('delete'); ?>" aria-label="<?php echo get_string('delete'); ?>"></i></a>';
    }
    else {
        if(num == 1){
            icons = '<a href="page2.php?id=' + formid + '&down=' + uniqueId + '"><i class="icon fa fa-arrow-down fa-fw " title="<?php echo get_string('movedown'); ?>" aria-label="<?php echo get_string('movedown'); ?>"></i></a>'+
                '<a href="page2.php?id=' + formid + '&del=' + uniqueId + '"><i class="icon fa fa-trash fa-fw " title="<?php echo get_string('delete'); ?>" aria-label="<?php echo get_string('delete'); ?>"></i></a>';
        }
        else if(movedownEnabled == 0){
            icons = '<a href="page2.php?id=' + formid + '&up=' + uniqueId + '"><i class="icon fa fa-arrow-up fa-fw " title="<?php echo get_string('moveup'); ?>" aria-label="<?php echo get_string('moveup'); ?>"></i></a>'+
                '<a href="page2.php?id=' + formid + '&del=' + uniqueId + '"><i class="icon fa fa-trash fa-fw " title="<?php echo get_string('delete'); ?>" aria-label="<?php echo get_string('delete'); ?>"></i></a> ';
        }
        else {
            icons = '<a href="page2.php?id=' + formid + '&up=' + uniqueId + '"><i class="icon fa fa-arrow-up fa-fw " title="<?php echo get_string('moveup'); ?>" aria-label="<?php echo get_string('moveup'); ?>"></i></a>'+
                '<a href="page2.php?id=' + formid + '&down=' + uniqueId + '"><i class="icon fa fa-arrow-down fa-fw " title="<?php echo get_string('movedown'); ?>" aria-label="<?php echo get_string('movedown'); ?>"></i></a>'+
                '<a href="page2.php?id=' + formid + '&del=' + uniqueId + '"><i class="icon fa fa-trash fa-fw " title="<?php echo get_string('delete'); ?>" aria-label="<?php echo get_string('delete'); ?>"></i></a>';
        }
    }
    newdiv.innerHTML = '<div class="row">'+
        '<div class="col-sm-6">'+
        '<label for="x'+uniqueId +'"><strong>'+textFieldTxt+':</strong> '+leftTxt+'</label>'+
        ' <input type="text" id = "x'+uniqueId +'" size="30" value="' + leftText+ '" onfocus="enableSave(\''+uniqueId+'_savebtn\');"></input>'+
        ' <select id = "optional_'+uniqueId+'" onchange="saveOptionalStatus('+uniqueId+')">  ' +
        '   <option value="0"> <?php echo get_string('optional_field', 'block_cmanager'); ?> </option>   ' +
        '   <option '+selectedText+' value="1"> <?php echo get_string('required_field', 'block_cmanager'); ?></option>  ' +
        ' </select>'+
        ' <input type="button" value="'+saveTxt+'" disabled="disabled" id="'+uniqueId+'_savebtn" onclick="saveFieldValue(' + uniqueId+')"/>'+
        '</div>'+
        '<div class="col-sm-2">'+icons+
        '</div>'+
        '</div>';
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
    newdiv.style.borderBottomWidth = '1px';
    newdiv.style.borderBottomStyle = 'dotted';

    newdiv.style.width = 400;
    newdiv.style.height = 110;
    newdiv.style.margin = '5px';

    var divIdName = 'my'+num+'Div';
    newdiv.setAttribute('id',num);
    ni.appendChild(newdiv);

    var selectedText = '';
    if(requiredFieldValue == '1'){
        selectedText = 'selected';
    }

    var icons;
    if(numberoffields == 1){
        icons = '<a href="page2.php?id=' + formid + '&del=' + uniqueId + '"><i class="icon fa fa-trash fa-fw " title="<?php echo get_string('delete'); ?>" aria-label="<?php echo get_string('delete'); ?>"></i></a>';
    }
    else {
        if(num == 1) {
            icons = '<a href="page2.php?id=' + formid + '&down=' + uniqueId + '"><i class="icon fa fa-arrow-down fa-fw " title="<?php echo get_string('movedown'); ?>" aria-label="<?php echo get_string('down'); ?>"></i></a>'+
                '<a href="page2.php?id=' + formid + '&del=' + uniqueId + '"><i class="icon fa fa-trash fa-fw " title="<?php echo get_string('delete'); ?>" aria-label="<?php echo get_string('delete'); ?>"></i></a>';
        }
        else if(movedownEnabled == 0){
            icons = '<a href="page2.php?id=' + formid + '&up=' + uniqueId + '"><i class="icon fa fa-arrow-up fa-fw " title="<?php echo get_string('moveup'); ?>" aria-label="<?php echo get_string('moveup'); ?>"></i></a>'+
                '<a href="page2.php?id=' + formid + '&del=' + uniqueId + '"><i class="icon fa fa-trash fa-fw " title="<?php echo get_string('delete'); ?>" aria-label="<?php echo get_string('delete'); ?>"></i></a>';
        }
        else {
            icons = '<a href="page2.php?id=' + formid + '&up=' + uniqueId + '"><i class="icon fa fa-arrow-up fa-fw " title="<?php echo get_string('moveup'); ?>" aria-label="<?php echo get_string('moveup'); ?>"></i></a>'+
                '<a href="page2.php?id=' + formid + '&down=' + uniqueId + '"><i class="icon fa fa-arrow-down fa-fw " title="<?php echo get_string('movedown'); ?>" aria-label="<?php echo get_string('movedown'); ?>"></i></a>'+
                '<a href="page2.php?id=' + formid + '&del=' + uniqueId + '"><i class="icon fa fa-trash fa-fw " title="<?php echo get_string('delete'); ?>" aria-label="<?php echo get_string('delete'); ?>"></i></a>';
        }
    }
    newdiv.innerHTML = '<div class="row">'+
        '<div class="col-sm-6">'+
        '<label for="x'+uniqueId +'"><strong>'+textAreaTxt+':</strong> '+leftTxt+'</label>'+
        ' <input type="text" id = "x'+uniqueId +'" size="30" value="' + leftText+ '" onfocus="enableSave(\''+uniqueId+'_savebtn\');"></input>'+
        ' <select id = "optional_'+uniqueId+'" onchange="saveOptionalStatus('+uniqueId+')">  ' +
        '   <option value="0"> <?php echo get_string('optional_field', 'block_cmanager'); ?> </option>   ' +
        '   <option '+selectedText+' value="1"> <?php echo get_string('required_field', 'block_cmanager'); ?></option>  ' +
        ' </select>'+
        ' <input type="button" value="'+saveTxt+'" disabled="disabled" id="'+uniqueId+'_savebtn" onclick="saveFieldValue(' + uniqueId+')"/>'+
        '</div>'+
        '<div class="col-sm-2">'+icons+
        '</div>'+
        '</div>';
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

    var fieldsInHTML = '<?php echo get_string('formBuilder_nooptionsadded', 'block_cmanager'); ?>';

    // Get the values for the dropdown menu
    $.ajaxSetup({async:false});
    $.post("ajax_functions.php", { id: uniqueId, type: 'getdropdownvalues'},
        function(data) {
            fieldsInHTML = data;

            var ni = document.getElementById('formdiv');
            var newdiv = document.createElement('div');
            //newdiv.style.backgroundColor = "gray";
            newdiv.style.borderBottomWidth = '1px';
            newdiv.style.borderBottomStyle = 'dotted';

            newdiv.style.width = 400;
            newdiv.style.height = 400;
            newdiv.style.margin = '5px';

            var divIdName = 'my'+num+'Div';
            newdiv.setAttribute('id',num);
            ni.appendChild(newdiv);

            var selectedText = '';
            if(requiredFieldValue == '1'){
                selectedText = 'selected';
            }

            if (fieldsInHTML.length == 0){
                fieldsInHTML = '<?php echo get_string('formBuilder_nooptionsadded', 'block_cmanager'); ?>';
            }

            var icons;
            if(numberoffields == 1){
                icons ='<a href="page2.php?id=' + formid + '&t=drop&del=' + uniqueId + '"><i class="icon fa fa-trash fa-fw " title="<?php echo get_string('delete'); ?>" aria-label="<?php echo get_string('delete'); ?>"></i></a>';
            } else {
                if(num == 1){
                    icons ='<a href="page2.php?id=' + formid + '&down=' + uniqueId + '"><i class="icon fa fa-arrow-down fa-fw " title="<?php echo get_string('movedown'); ?>" aria-label="<?php echo get_string('down'); ?>"></i></a>'+
                        '<a href="page2.php?id=' + formid + '&t=drop&del=' + uniqueId + '"><i class="icon fa fa-trash fa-fw " title="<?php echo get_string('delete'); ?>" aria-label="<?php echo get_string('delete'); ?>"></i></a>';
                }
                else if(movedownEnabled == 0){
                    icons ='<a href="page2.php?id=' + formid + '&up=' + uniqueId + '"><i class="icon fa fa-arrow-up fa-fw " title="<?php echo get_string('moveup'); ?>" aria-label="<?php echo get_string('moveup'); ?>"></i></a>'+
                        '<a href="page2.php?id=' + formid + '&t=drop&del=' + uniqueId + '"><i class="icon fa fa-trash fa-fw " title="<?php echo get_string('delete'); ?>" aria-label="<?php echo get_string('delete'); ?>"></i></a>';
                } else {
                    icons ='<a href="page2.php?id=' + formid + '&up=' + uniqueId + '"><i class="icon fa fa-arrow-up fa-fw " title="<?php echo get_string('moveup'); ?>" aria-label="<?php echo get_string('moveup'); ?>"></i></a>'+
                        '<a href="page2.php?id=' + formid + '&down=' + uniqueId + '"><i class="icon fa fa-arrow-down fa-fw " title="<?php echo get_string('movedown'); ?>" aria-label="<?php echo get_string('down'); ?>"></i></a>'+
                        '<a href="page2.php?id=' + formid + '&t=drop&del=' + uniqueId + '"><i class="icon fa fa-trash fa-fw " title="<?php echo get_string('delete'); ?>" aria-label="<?php echo get_string('delete'); ?>"></i></a>';
                }
            }
            newdiv.innerHTML = '<div class="row">'+
                '<div class="col-sm-6">'+
                '<label for="x'+uniqueId +'"><strong>'+dropdownTxt+':</strong> '+leftTxt+'</label>'+
                ' <input type="text" id = "x'+uniqueId +'" size="30" value="' + leftText+ '" onfocus="enableSave(\''+uniqueId+'_savebtn\');"></input>'+
                ' <select id = "optional_'+uniqueId+'" onchange="saveOptionalStatus('+uniqueId+')">  ' +
                '   <option value="0"> <?php echo get_string('optional_field', 'block_cmanager'); ?> </option>   ' +
                '   <option '+selectedText+' value="1"> <?php echo get_string('required_field', 'block_cmanager'); ?></option>  ' +
                ' </select>'+
                ' <input type="button" value="'+saveTxt+'" disabled="disabled" id="'+uniqueId+'_savebtn" onclick="saveFieldValue(' + uniqueId+')"/>'+
                '</div>'+
                '<div class="col-sm-2">'+icons+
                '</div>'+
                '</div>'+
                ' <input type="text" id="newitem'+uniqueId +'"> <input type="button" name="submitbutton" value="'+addItemBtnTxt+'" onclick="addNewItem('+ uniqueId +');">'+
                '<p>'+addedItemsTxt+':</p>'+
                fieldsInHTML;
        }
    );
    num++;
}


function createRadio(){

	  var fieldsInHTML = '';
	  var leftText = '';
		var ni = document.getElementById('formdiv');
		var newdiv = document.createElement('div');
		//newdiv.style.backgroundColor = "gray";
		newdiv.style.borderBottomWidth = '1px';
		newdiv.style.borderBottomStyle = 'dotted';

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
    leftText = decodeURI(leftText);
    var fieldsInHTML;

    // Get the values for the dropdown menu
    $.ajaxSetup({async:false});
    $.post("ajax_functions.php", { id: uniqueId, type: 'getdropdownvalues'},

        function(data) {
            fieldsInHTML = data;
            var ni = document.getElementById('formdiv');
            var newdiv = document.createElement('div');
            newdiv.style.borderBottomWidth = '1px';
            newdiv.style.borderBottomStyle = 'dotted';

            newdiv.style.width = 400;
            newdiv.style.height = 400;
            newdiv.style.margin = '5px';
            var divIdName = 'my'+num+'Div';
            newdiv.setAttribute('id',num);
            ni.appendChild(newdiv);

            var selectedText = '';
            if(requiredFieldValue == '1'){
                selectedText = 'selected';
            }

            if (fieldsInHTML.length == 0){
                fieldsInHTML = '<?php echo get_string('formBuilder_nooptionsadded', 'block_cmanager'); ?>';
            }

            var icons;
            if(numberoffields == 1){
                icons = '<a href="page2.php?id=' + formid + '&del=' + uniqueId + '"><i class="icon fa fa-trash fa-fw " title="<?php echo get_string('delete'); ?>" aria-label="<?php echo get_string('delete'); ?>"></i></a>';
            }
            else {
                if(num == 1){
                    icons = '<a href="page2.php?id=' + formid + '&down=' + uniqueId + '"><i class="icon fa fa-arrow-down fa-fw " title="<?php echo get_string('movedown'); ?>" aria-label="<?php echo get_string('down'); ?>"></i></a>'+
                        '<a href="page2.php?id=' + formid + '&del=' + uniqueId + '"><i class="icon fa fa-trash fa-fw " title="<?php echo get_string('delete'); ?>" aria-label="<?php echo get_string('delete'); ?>"></i></a>';
                }
                else if(movedownEnabled == 0){
                    icons = '<a href="page2.php?id=' + formid + '&up=' + uniqueId + '"><i class="icon fa fa-arrow-up fa-fw " title="<?php echo get_string('moveup'); ?>" aria-label="<?php echo get_string('moveup'); ?>"></i></a>'+
                        '<a href="page2.php?id=' + formid + '&t=drop&del=' + uniqueId + '"><i class="icon fa fa-trash fa-fw " title="<?php echo get_string('delete'); ?>" aria-label="<?php echo get_string('delete'); ?>"></i></a>';
                }
                else {
                    icons = '<a href="page2.php?id=' + formid + '&up=' + uniqueId + '"><i class="icon fa fa-arrow-up fa-fw " title="<?php echo get_string('moveup'); ?>" aria-label="<?php echo get_string('moveup'); ?>"></i></a>'+
                        '<a href="page2.php?id=' + formid + '&down=' + uniqueId + '"><i class="icon fa fa-arrow-down fa-fw " title="<?php echo get_string('movedown'); ?>" aria-label="<?php echo get_string('down'); ?>"></i></a>'+
                        '<a href="page2.php?id=' + formid + '&t=drop&del=' + uniqueId + '"><i class="icon fa fa-trash fa-fw " title="<?php echo get_string('delete'); ?>" aria-label="<?php echo get_string('delete'); ?>"></i></a> ';
                }
            }
            newdiv.innerHTML = '<div class="row">'+
                '<div class="col-sm-6">'+
                '<label for="x'+uniqueId +'"><strong>'+radioTxt+':</strong> '+leftTxt+'</label>'+
                ' <input type="text" id = "x'+uniqueId +'" size="30" value="' + leftText+ '" onfocus="enableSave(\''+uniqueId+'_savebtn\');"></input>'+
                ' <select id = "optional_'+uniqueId+'" onchange="saveOptionalStatus('+uniqueId+')">  ' +
                '   <option value="0"> <?php echo get_string('optional_field', 'block_cmanager'); ?> </option>   ' +
                '   <option '+selectedText+' value="1"> <?php echo get_string('required_field', 'block_cmanager'); ?></option>  ' +
                ' </select>'+
                ' <input type="button" value="'+saveTxt+'" disabled="disabled" id="'+uniqueId+'_savebtn" onclick="saveFieldValue(' + uniqueId+')"/>'+
                '</div>'+
                '<div class="col-sm-2">'+icons+
                '</div>'+
                '</div>'+
                ' <input type="text" id="newitem'+uniqueId +'"> <input type="button" name="submitbutton" value="'+addItemBtnTxt+'" onclick="addNewItem('+ uniqueId +');">'+
                '<p>'+addedItemsTxt+':</p>'+
                fieldsInHTML;
        }
    );
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
					//alert('<?php echo get_string('changeshavebeensaved', 'block_cmanager'); ?>');
                    $("#saved").modal();
		   });



 //window.location = 'page2.php?id=' + formid;

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
    $lt = $field->lefttext;
    if ($field->type == 'textfield') {
		echo "<script>
		       recreateTextField('". $field->id ."', '". $lt ."', '". $field->reqfield ."');
	          </script>";
	}
	else if ($field->type == 'textarea') {
	    echo "<script>
		       recreateTextArea('". $field->id ."', '". $lt ."', '". $field->reqfield ."');
	      </script>
	      ";
	}
	else if ($field->type == 'dropdown') {
	     echo "<script>
		       recreateDropdown('". $field->id ."', '". $lt ."', '". $field->reqfield ."');
	      </script>
	      ";
	}
    else if ($field->type == 'radio') {
	   	echo "<script>
		       recreateRadio('". $field->id ."', '". $lt ."', '". $field->reqfield ."');
	      </script>
	      ";
	 }

	 $reccounter++;
}

echo $OUTPUT->footer();
