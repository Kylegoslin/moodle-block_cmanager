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
$PAGE->navbar->add(get_string('formpage1', 'block_cmanager'));
$PAGE->set_url('/blocks/cmanager/formeditor/page1.php');
$PAGE->set_context(context_system::instance());
$PAGE->set_heading(get_string('pluginname', 'block_cmanager'));
$PAGE->set_title(get_string('pluginname', 'block_cmanager'));
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
<script src="../js/jquery/jquery-1.7.2.min.js"></script>
<script>

function goBack(){
	window.location ="../cmanager_confighome.php";
}

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
	
	
function addNewItem(){
	
    jQuery.ajaxSetup({async:false});
    var value = document.getElementById('newitem').value;
    $.post("ajax_functions.php", { valuetoadd: value, type: 'add'},
   
   	function(data) {
     		
	});
 
	//alert('A new item has been added: ' + value);
}
	
	
	
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
		
		
	alert(langString);
		
}
</script>
<?php
require_once("$CFG->libdir/formslib.php");


/**
 * Page 1 form
 *
 *  Page 1 form
 * @package    block_socialbookmark
 * @copyright  2014 Kyle Goslin, Daniel McSweeney
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
	    $mform->addElement('header', 'mainheader', '<span style="font-size:18px"> '.get_string('formpage1','block_cmanager').'</span>');

		
     // Field 3 items
     $field3itemshtml = '';
     $selectQuery = "varname = 'page1_field3value'";
	 $field3Items = $DB->get_recordset_select('block_cmanager_config', $select=$selectQuery);
	
				$field3itemshtml .= '<table width="200px">';							  
							  foreach($field3Items as $item){
							  	$field3itemshtml .= '<tr>';
							  	$field3itemshtml .= '<td>' . $item->value . '</td> <td> [<a href="page1.php?del=' . $item->id . '">Delete Item]</a></td>';
								$field3itemshtml .= '</tr>';
							  } 
				$field3itemshtml .= '</table>';
     

	  // Field 3 html
     if ($field3status == 'enabled') {
     	$enabledselected = 'selected = "yes"';
		$disabledselected = '';
     } else if ($field3status == 'disabled') {
     	$disabledselected = 'selected = "yes"';
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
			   <option value="tf">Text Field</option>
			   <option value="ta">Text Area</option>
			   <option value="rbg">Radio Button Group</option>
			   <option value="cbg">Check Box Group</option>
			</select>
			
			<p></p>
			<div id="formdiv">
			
			</div>
		';
		
		
	 	//$mform->addElement('html', $htmlOutput);
 
 
     $fieldshtml = '
		<p></p> 
		&nbsp;
		<p></p>	
	 
 	<button type="button" onclick="goBack();"><img src="../icons/back.png"/>'.get_string('back','block_cmanager').'</button><p></p>
	


     <br><br>
	 <b>Instructions</b>
	 <br>
	 '.get_string('entryFields_instruction1','block_cmanager').'
	 <br><br>
	 '.get_string('entryFields_instruction2','block_cmanager').'
	 <br><br><br> 
	 
	 
	 <center>
     <div style="border-width: 0px; border-style: dotted; border-color: gray; width:500px; height:130px">
     <b>'.get_string('entryFields_TextfieldOne','block_cmanager').':</b>
	 <p></p>
	 
	 
	 <table border="0" width="400px">
	 <tr>
	 	<td>
	        '.get_string('entryFields_Name','block_cmanager').'
	 	</td>
	 
	 
	 	<td>
	 		<input type="text" id = "field1title" size="50" value = "' . $field1title.'"/>
	 	</td>
	 
	 
	 </td>
	 <tr>
	 	<td>
	        '.get_string('entryFields_Description','block_cmanager').':
	 	</td>
	 
	 
	 	<td>
	 		<input type="text" id = "field1desc" size="50" value = "'. $field1desc.'"/>
	 	</td>
	 
	 
	 
	 </tr>
	
	 </table>
	 
     </div> 
     
	 
	      <div style="border-width: 0px; border-style: dotted; border-color: gray; width:500px; height:130px">
     <b>'.get_string('entryFields_TextfieldTwo','block_cmanager').'</b>
	 <p></p>
	 
	 
	 <table border="0" width="400px">
	 <tr>
	 	<td>
	         '.get_string('entryFields_Name','block_cmanager').'
	 	</td>
	 
	 
	 	<td>
	 		<input type="text" id = "field2title" size="50" value = "' . $field2title.'"/>
	 	</td>
	 
	 
	 </td>
	 <tr>
	 	<td>
	        '.get_string('entryFields_Description','block_cmanager').':
	 	</td>
	 
	 
	 	<td>
	 		<input type="text" id = "field2desc" size="50" value = "' . $field2desc.'"/>
	 	</td>
	 
	 
	 
	 </tr>
	 <tr>
	 
	 
	
	 </table>
	 
     </div> 
	 </center>
	 
	 '.get_string('entryFields_DropdownDescription','block_cmanager').'
	 
	 <center>
	 <br>
	 <br>
	  
	   <div style="border-width: 0px; border-style: dotted; border-color: gray; width:500px; height:130px">
	    <b>'.get_string('entryFields_Dropdown','block_cmanager').':</b>
	    <p></p>
	    <table border="0" width="400px">
	 	<tr>
	 	<td width="100px">
		  '.get_string('entryFields_Name','block_cmanager').':
		<td>
		 <input type="text" id = "field3desc" size="50" value = "' . $field3desc.'"/>
		</td>
		</td>
	 	
	 	</tr>
		 
	 	<tr>
	 	<td width="100px">
	 	'.get_string('entryFields_status','block_cmanager').':  
		</td>
		
		 <td>  
	      ' . $field3html.'
	
	    </td>
		</tr>
		
		
		<tr>
		   <td>
		      Values:
		   </td>
		       ' . $field3itemshtml. '
		   <td>
		<p></p>
		&nbsp;

		<input type="text" id="newitem"></input><input type="submit" name="submitbutton" value="'.get_string('entryFields_AddNewItem','block_cmanager').'" onclick="addNewItem();">
		<p></p>
		
		<input type="submit" value="'.get_string('SaveChanges','block_cmanager').'" onclick="saveAllChanges(\''.get_string('ChangesSaved','block_cmanager').'\');"/> 
		
		
		    </td>
		
				
		</tr>
		
		
	
	 	</table>
	 
	 </div>
	 
	   
	   <p></p>&nbsp;<p></p>&nbsp;
	   <p></p>&nbsp;<p></p>&nbsp;
	   <p></p>&nbsp;<p></p>&nbsp;
    
	  
	<p></p>&nbsp;<p></p>&nbsp;
	   <p></p>&nbsp;<p></p>&nbsp;
	   <p></p>&nbsp;<p></p>&nbsp;
	
	 ';
	 
	 
	 
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


  
	
		