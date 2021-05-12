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
require_login();
require_once('../validate_admin.php');
require_once('../lib/boot.php');

 /** Navigation Bar **/
$PAGE->navbar->ignore_active();
$PAGE->navbar->add(get_string('cmanagerDisplay', 'block_cmanager'), new moodle_url('/blocks/cmanager/cmanager_admin.php'));
$PAGE->navbar->add(get_string('configurecoursemanagersettings', 'block_cmanager'), new moodle_url('/blocks/cmanager/cmanager_confighome.php'));
$PAGE->navbar->add(get_string('formpage1', 'block_cmanager'));
$PAGE->set_url('/blocks/cmanager/formeditor/page1.php');
$PAGE->set_context(context_system::instance());
$PAGE->set_heading(get_string('formfieldsHeader', 'block_cmanager'));
$PAGE->set_title(get_string('formfieldsHeader', 'block_cmanager'));
echo $OUTPUT->header();
 if(isset($_GET['del'])){
	$deleteid = required_param('del', PARAM_INT);
 	$DB->delete_records('block_cmanager_config', array('id'=>$deleteid));

 }


$context = context_system::instance();
if (has_capability('block/cmanager:viewconfig',$context)) {
} else {
  print_error(get_string('cannotviewrecords', 'block_cmanager'));
}
?>

<link rel="stylesheet" type="text/css" href="../css/main.css" />
<script src="../js/jquery/jquery-3.3.1.min.js"></script>

<script>

var num = 1;

function addNewField(field){
		alert(field.value);

		num++;
		var ni = document.getElementById('formdiv');

		var newdiv = document.createElement('div');
		//newdiv.style.backgroundColor = "gray";
		newdiv.style.borderWidth = 1;
		newdiv.style.borderStyle = 'dotted';

		newdiv.style.width = 400;
		newdiv.style.height = 100;
        newdiv.style.marginBottom = 5;
        newdiv.style.marginLeft = 5;

		var divIdName = 'my'+num+'Div';
        newdiv.setAttribute('id',num);
        newdiv.innerHTML = 'Example Field';
        ni.appendChild(newdiv);



}

//
// Add a new menu item into the optional drop down field on
// form page 1.
function addNewItem(){

    jQuery.ajaxSetup({async:false});
    var value = document.getElementById('newitem').value;
    if(value != "") {
        $.post("ajax_functions.php", { valuetoadd: value, type: 'add'},

        function(data) {

        });
    }

}


//
// Save all the text inside the fields for page 1 of the form
//
function saveAllChanges(langString){

    var field1title = document.getElementById('field1title').value;

	var field1desc = document.getElementById('field1desc').value;
	var field2title = document.getElementById('field2title').value;
	var field2desc = document.getElementById('field2desc').value;
	var field3desc = document.getElementById('field3desc').value;


	var dropdownStatus = document.getElementById('dropdownstatus').value;

	jQuery.ajaxSetup({async:false});
	    $.post("ajax_functions.php", { f1t: field1title, f1d: field1desc, f2t: field2title, f2d: field2desc, f3d: field3desc, type: 'save', dstat: dropdownStatus},

   	function(data) {

	});

	$("#saved").modal();


}
</script>
<?php
require_once("$CFG->libdir/formslib.php");


/**
 * Page 1 form
 *
 *  Page 1 form
 * @package    block_socialbookmark
 * @copyright  2018 Kyle Goslin, Daniel McSweeney
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_cmanager_page1_form extends moodleform {

    function definition() {
        global $CFG, $USER, $DB;
        $mform =& $this->_form; // Don't forget the underscore!

        // Get the field values
        $field1title = $DB->get_field_select('block_cmanager_config', 'value', "varname = 'page1_fieldname1'");
        $field1desc = $DB->get_field_select('block_cmanager_config', 'value', "varname = 'page1_fielddesc1'");
        $field2title = $DB->get_field_select('block_cmanager_config', 'value', "varname = 'page1_fieldname2'");
        $field2desc = $DB->get_field_select('block_cmanager_config', 'value', "varname = 'page1_fielddesc2'");
	    $field3desc = $DB->get_field_select('block_cmanager_config', 'value', "varname = 'page1_fielddesc3'");
	    $field3status = $DB->get_field_select('block_cmanager_config', 'value', "varname = 'page1_field3status'");

		// Field 3 items
		$field3itemshtml = '';
		$selectQuery = "varname = 'page1_field3value'";
		$field3Items = $DB->get_recordset_select('block_cmanager_config', $select=$selectQuery);

		foreach($field3Items as $item){
			$field3itemshtml .= '<div class="row">';
			$field3itemshtml .= '<div class="col-sm-7"><label for="field1title">' .$item->value . '</label></div>';
			$field3itemshtml .= '<div class="col-sm-2"><a href="page1.php?del=' . $item->id . '" aria-label="' . get_string('formBuilder_confirmDelete','block_cmanager') . '" title="' . get_string('formBuilder_confirmDelete','block_cmanager') . '"><i class="icon fa fa-trash fa-fw" aria-hidden="true"></i></a></div>';
			$field3itemshtml .= '</div>';
		}

		// Field 3 html
		if ($field3status == 'enabled') {
			$enabledselected = 'selected';
			$disabledselected = '';
		} else if ($field3status == 'disabled') {
			$disabledselected = 'selected';
			$enabledselected = '';
		}

		$field3html = '
		<select id = "dropdownstatus">
				<option '. $enabledselected .' value="enabled">'.get_string('Enabled','block_cmanager').'</option>
				<option ' . $disabledselected .' value="disabled">'.get_string('Disabled','block_cmanager').'</option>
		</select>
	    ';


	 	$htmlOutput = '

	 		&nbsp;Add new field:

			<select onchange="addNewField(this);">
			   <option>Add new..</option>
			   <option value="tf">'.get_string('formpage1_textfield','block_cmanager').'</option>
			   <option value="ta">'.get_string('formpage1_textarea','block_cmanager').'</option>
			   <option value="rbg">'.get_string('formpage1_rbg','block_cmanager').'</option>
			   <option value="cbg">'.get_string('formpage1_cbg','block_cmanager').'</option>
			</select>

			<p></p>
			<div id="formdiv">

			</div>
		';


	 	//$mform->addElement('html', $htmlOutput);


     $fieldshtml = '
		<p><a href="../cmanager_confighome.php" class="btn btn-default"><img src="../icons/back.png" alt=""> '.get_string('back','block_cmanager').'</a></p>
		<h2>Instructions</h2>
		<p>' . get_string('entryFields_instruction1','block_cmanager') . '</p>
		<p>' . get_string('entryFields_instruction2','block_cmanager') . '</p>
		<h2 class="mt-3">'.get_string('entryFields_TextfieldOne','block_cmanager').':</h2>
		<div class="row">
			<div class="col-sm-1"><label for="field1title">' .get_string('entryFields_Name','block_cmanager') . '</label></div>
			<div class="col-sm-3"><input type="text" id = "field1title" size="50" value = "' . $field1title.'"/></div>
		</div>
		<div class="row">
			<div class="col-sm-1"><label for="field1desc">' .get_string('entryFields_Description','block_cmanager') . '</label></div>
			<div class="col-sm-3"><input type="text" id = "field1desc" size="50" value = "' . $field1desc.'"/></div>
		</div>
		<h2 class="mt-3">' . get_string('entryFields_TextfieldTwo','block_cmanager') . '</h2>
		<div class="row">
			<div class="col-sm-1"><label for="field2title">' .get_string('entryFields_Name','block_cmanager') . '</label></div>
			<div class="col-sm-3"><input type="text" id = "field2title" size="50" value = "' . $field2title.'"/></div>
		</div>
		<div class="row">
			<div class="col-sm-1"><label for="field2desc">' .get_string('entryFields_Description','block_cmanager') . '</label></div>
			<div class="col-sm-3"><input type="text" id = "field2desc" size="50" value = "' . $field2desc.'"/></div>
		</div>

	    <p class="mt-3">' . get_string('entryFields_DropdownDescription','block_cmanager') . '</p>

		<h2 class="mt-3">'.get_string('entryFields_Dropdown','block_cmanager').'</h2>
		<div class="row">
			<div class="col-sm-1"><label for="field3desc">' .get_string('entryFields_Name','block_cmanager') . '</label></div>
			<div class="col-sm-3"><input type="text" id = "field3desc" size="50" value = "' . $field3desc.'"/></div>
		</div>
		<div class="row">
			<div class="col-sm-1"><label for="dropdownstatus">' .get_string('entryFields_status','block_cmanager') . '</label></div>
			<div class="col-sm-3">' . $field3html . '</div>
		</div>
		<div class="row">
			<div class="col-sm-1">' .get_string('entryFields_values','block_cmanager') . '</div>
			<div class="col-sm-3">' . format_text($field3itemshtml, FORMAT_HTML, ['context'=> context_system::instance()]) . '</div>
		</div>
		<div class="row">
			<div class="col-sm-3 offset-md-1"><input type="text" id="newitem"><input class="btn-default" type="submit" name="submitbutton" value="'.get_string('entryFields_AddNewItem','block_cmanager').'" onclick="addNewItem();"></div>
		</div>
		<button class="btn-primary" type="button" onclick="saveAllChanges(\''.get_string('ChangesSaved','block_cmanager').'\');">
        '.get_string('SaveChanges','block_cmanager').'
        </button>
	 ';

	 $fieldshtml .= generateGenericPop('saved', get_string('ChangesSaved','block_cmanager'), get_string('ChangesSaved','block_cmanager'), get_string('ok','block_cmanager') );


	 $mform->addElement('html', $fieldshtml);

	}
}




$mform = new block_cmanager_page1_form();//name of the form you defined in file above.

if ($mform->is_cancelled()) {


} else if ($fromform=$mform->get_data()){

			$mform->focus();
		    //$mform->set_data($toform);
		    $mform->display();
		  	echo $OUTPUT->footer();

} else {

			$mform->focus();
		    //$mform->set_data($toform);
		    $mform->display();
		  	echo $OUTPUT->footer();

}
