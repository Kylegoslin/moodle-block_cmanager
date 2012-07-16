<?php
//ini_set('display_errors', 1); 
//error_reporting(E_ALL);


require_once("../../../config.php");
global $CFG;
$formPath = "$CFG->libdir/formslib.php";
require_once($formPath);

if(isset($_GET['id'])){
	
	$formId = $_GET['id'];
	$formName = $_GET['name'];
	
} else {
	echo get_string('formBuilder_p2_error','block_cmanager');
	die;
}


echo '<script>
       var num = 1; // Used to count the number of fields added.
       var formId = ' . $formId .';
       var movedownEnabled = 1;
      </script>';

// Deleting dropdown menus
if(isset($_GET['t']) && isset($_GET['del'])){
	
    if($_GET['t'] == 'drop'){
          // Delete the main record
          $delId = $_GET['del'];
          delete_records_select('cmanager_formfields', "id = $delId"); 
		  
		  // Delete the data records
    	  delete_records_select('cmanager_formfields_data', "fieldid = $delId"); 
		  
		  
		  // Reorder the rest of the fields
		  //Update the position numbers
		 $selectQuery = "";
		 $positionItems = get_recordset_select('cmanager_formfields', $select=$selectQuery, $sort='position ASC', $fields='*', 
	                              $limitfrom='', $limitnum='');
							  
							  $newposition = 1;
							  foreach($positionItems as $item){
							  	
								$dataobject->id = $item['id'];
								$dataobject->position = $newposition;
								update_record('cmanager_formfields', $dataobject);
								
								$newposition++;
	
							  }

	}
	else if($_GET['t'] == 'dropitem'){ // Delete a dropdown menu item
		$itemid = $_GET['del'];
		$fieldid = $_GET['fid'];
		delete_records_select('cmanager_formfields_data', "fieldid = $fieldid AND id=$itemid"); 
	}
    
}

// Delete Field
if(isset($_GET['del'])){
	$delId = $_GET['del'];
    delete_records_select('cmanager_formfields', "id = $delId"); 	
	
	//Update the position numbers
	 $selectQuery = "";
	 $positionItems = get_recordset_select('cmanager_formfields', $select=$selectQuery, $sort='position ASC', $fields='*', 
                              $limitfrom='', $limitnum='');
							  
							  $newposition = 1;
							  foreach($positionItems as $item){
							  	
								$dataobject->id = $item['id'];
								$dataobject->position = $newposition;
								update_record('cmanager_formfields', $dataobject);
								
								$newposition++;
	
							  }
	
}


// Move field up
if(isset($_GET['up'])){
	
	
$currentid = $_GET['up'];
	// Get current fields position
	$query = "SELECT * FROM mdl_cmanager_formfields WHERE id = $currentid";
	$currentRecord = get_record_sql($query, $expectmultiple=false, $nolimit=false); 
	
    $currentPosition = $currentRecord->position;


  
    $higherpos = $currentPosition-1;
    $query = "SELECT * FROM mdl_cmanager_formfields WHERE position = $higherpos";
	$higherRecord = get_record_sql($query, $expectmultiple=false, $nolimit=false); 
	
    

   	$dataobject->id = $currentRecord->id;
	$dataobject->position = $currentPosition-1;
	update_record('cmanager_formfields', $dataobject);




	$dataobject2->id = $higherRecord->id;
	$newpos2 = $higherRecord->position + 1;  
	$dataobject2->position = $newpos2;
	update_record('cmanager_formfields', $dataobject2);
	
	
}



// Move field down
if(isset($_GET['down'])){
	
	
	$currentid = $_GET['down'];
	// Get current fields position
	$query = "SELECT * FROM mdl_cmanager_formfields WHERE id = $currentid";
	$currentRecord = get_record_sql($query, $expectmultiple=false, $nolimit=false); 
	
    $currentPosition = $currentRecord->position;


  
    $higherpos = $currentPosition+1;
    $query = "SELECT * FROM mdl_cmanager_formfields WHERE position = $higherpos";
	$higherRecord = get_record_sql($query, $expectmultiple=false, $nolimit=false); 
	
    

   	$dataobject->id = $currentRecord->id;
	$dataobject->position = $currentPosition+1;
	update_record('cmanager_formfields', $dataobject);




	$dataobject2->id = $higherRecord->id;
	$newpos2 = $higherRecord->position - 1;  
	$dataobject2->position = $newpos2;
	update_record('cmanager_formfields', $dataobject2);
	
	
	
	
}





							  
							  
?>

<link rel="stylesheet" type="text/css" href="css/main.css" />
<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
 
<script>

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
			newdiv.style.height = 100;
	        newdiv.style.marginBottom = 5;
	        newdiv.style.marginLeft = 5;
			
			var divIdName = 'my'+num+'Div';
	        newdiv.setAttribute('id',num);
	        ni.appendChild(newdiv);
	        
	        
	        var uniqueId;
	        // Add to database
	        $.ajaxSetup({async:false});
	         $.post("ajax_functions.php", { type: 'page2addfield', fieldtype: 'textfield', formid: formId},
   				function(data) {
		     		uniqueId = data;
		     		
		     		if(num == 1){
		     		 newdiv.innerHTML = '<b>'+textFieldTxt+':</b> <img src="../images/move_up_dis.gif" width="20" height="20" alt="move up" />  <a href="page2.php?id=' + formId + '&down=' + uniqueId + '"><img src="../images/move_down.gif" width="20" height="20" alt="move down" /></a>  <a href="page2.php?id=' + formId + '&del=' + uniqueId + '"><img src="../images/deleteIcon.png" width="20" height="20" alt="delete" /></a> <p></p><table><tr><td>'+leftTxt+':</td><td><input type="text" size="30" id="'+ uniqueId+'"></input></td></tr></table><input type="button" value="'+saveTxt+'" onclick="saveFieldValue(' + uniqueId + ');"/>';
	       			}
	       			else if(movedownEnabled == 0){
					   newdiv.innerHTML = '<b>'+textFieldTxt+':</b> <a href="page2.php?id=' + formId + '&up=' + uniqueId + '"><img src="../images/move_up.gif" width="20" height="20" alt="move up" /></a> <img src="../images/move_down_dis.gif" width="20" height="20" alt="move down" /> <a href="page2.php?id=' + formId + '&del=' + uniqueId + '"><img src="../images/deleteIcon.png" width="20" height="20" alt="delete" /></a><p></p><table><tr><td>'+leftTxt+':</td><td><input type="text" size="30" id="'+ uniqueId+'"></input></td></tr></table><input type="button" value="'+saveTxt+'" onclick="saveFieldValue(' + uniqueId+');"/>';
	       					
			        }	
	       			else {
	       	 			newdiv.innerHTML = '<b>'+textFieldTxt+':</b> <a href="page2.php?id=' + formId + '&up=' + uniqueId + '"><img src="../images/move_up.gif" width="20" height="20" alt="move up" /></a> <img src="../images/move_down_dis.gif" width="20" height="20" alt="move down" /> <a href="page2.php?id=' + formId + '&del=' + uniqueId + '"><img src="../images/deleteIcon.png" width="20" height="20" alt="delete" /></a><p></p><table><tr><td>'+leftTxt+':</td><td><input type="text" size="30" id="'+ uniqueId+'"></input></td></tr></table><input type="button" value="'+saveTxt+'" onclick="saveFieldValue(' + uniqueId+');"/>';
	       			
	       			}
			   });
			   
			   
			   num++;
			   window.location = 'page2.php?id=' + formId;
	}
	
	
	// If the text field already existed, rebuilt it using data from the db.
	function recreateTextField(uniqueId, leftText){
		
		
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
	        ni.appendChild(newdiv);
	   	   
	   	   	if(num == 1){
		     		newdiv.innerHTML = '<b>'+textFieldTxt+':</b> <img src="../images/move_up_dis.gif" width="20" height="20" alt="move up" />  <a href="page2.php?id=' + formId + '&down=' + uniqueId + '"><img src="../images/move_down.gif" width="20" height="20" alt="move down" /></a>  <a href="page2.php?id=' + formId + '&del=' + uniqueId + '"><img src="../images/deleteIcon.png" width="20" height="20" alt="delete" /></a> <p></p><table><tr><td>'+leftTxt+':</td><td><input type="text" id = "'+uniqueId +'" size="30" value="' + leftText+ '"></input></td></tr></table><input type="button" value="'+saveTxt+'" onclick="saveFieldValue(' + uniqueId+')"/>';
	       			
		     		} 
			else if(movedownEnabled == 0){
					newdiv.innerHTML = '<b>'+textFieldTxt+':</b> <a href="page2.php?id=' + formId + '&up=' + uniqueId + '"><img src="../images/move_up.gif" width="20" height="20" alt="move up" /></a>   <img src="../images/move_down_dis.gif" width="20" height="20" alt="move down" />   <a href="page2.php?id=' + formId + '&del=' + uniqueId + '"><img src="../images/deleteIcon.png" width="20" height="20" alt="delete" /></a> <p></p><table><tr><td>'+leftTxt+':</td><td><input type="text" id = "'+uniqueId +'" size="30" value="' + leftText+ '"></input></td></tr></table><input type="button" value="'+saveTxt+'" onclick="saveFieldValue(' + uniqueId+')"/>';
	       		
			}	       			    		
		    else {
	       	 			newdiv.innerHTML = '<b>'+textFieldTxt+':</b> <a href="page2.php?id=' + formId + '&up=' + uniqueId + '"><img src="../images/move_up.gif" width="20" height="20" alt="move up" /></a>  <a href="page2.php?id=' + formId + '&down=' + uniqueId + '"><img src="../images/move_down.gif" width="20" height="20" alt="move down" /></a>  <a href="page2.php?id=' + formId + '&del=' + uniqueId + '"><img src="../images/deleteIcon.png" width="20" height="20" alt="delete" /></a> <p></p><table><tr><td>'+leftTxt+':</td><td><input type="text" id = "'+uniqueId +'" size="30" value="' + leftText+ '"></input></td></tr></table><input type="button" value="'+saveTxt+'" onclick="saveFieldValue(' + uniqueId+')"/>';
	       			
	       			}
	       			
	       			num++;
	}
	
		// Create a new blank text field on the page
	function createTextArea(){
		
		
			var ni = document.getElementById('formdiv');
			var newdiv = document.createElement('div');
			//newdiv.style.backgroundColor = "gray";
			newdiv.style.borderWidth = 1;
			newdiv.style.borderStyle = 'dotted';
	
			newdiv.style.width = 450;
			newdiv.style.height = 100;
	        newdiv.style.marginBottom = 5;
	        newdiv.style.marginLeft = 5;
			
			var divIdName = 'my'+num+'Div';
	        newdiv.setAttribute('id',num);
	        ni.appendChild(newdiv);
	        
	        
	        var uniqueId;
	        // Add to database
	        $.ajaxSetup({async:false});
	         $.post("ajax_functions.php", { type: 'page2addfield', fieldtype: 'textarea', formid: formId},
   				function(data) {
		     		uniqueId = data;
		     		
		     		if(num == 1){
		     		 newdiv.innerHTML = '<b>'+textAreaTxt+':</b>  <img src="../images/move_up_dis.gif" width="20" height="20" alt="move up" /> <a href="page2.php?id=' + formId + '&down=' + uniqueId + '"><img src="../images/move_down.gif" width="20" height="20" alt="move down" /></a>  <a href="page2.php?id=' + formId + '&del=' + uniqueId + '"><img src="../images/deleteIcon.png" width="20" height="20" alt="delete" /></a> <p></p><table><tr><td>'+leftTxt+':</td><td><input type="text" size="30" id="'+ uniqueId+'"></input></td></tr></table><input type="button" value="'+saveTxt+'" onclick="saveFieldValue(' + uniqueId + ');"/>';
	       			} 
	       			else if(movedownEnabled == 0){
	       	 			newdiv.innerHTML = '<b>'+textAreaTxt+':</b> <a href="page2.php?id=' + formId + '&up=' + uniqueId + '"><img src="../images/move_up.gif" width="20" height="20" alt="move up" /></a> <img src="../images/move_down_dis.gif" width="20" height="20" alt="move down" /> <a href="page2.php?id=' + formId + '&del=' + uniqueId + '"><img src="../images/deleteIcon.png" width="20" height="20" alt="delete" /></a><p></p><table><tr><td>'+leftTxt+':</td><td><input type="text" size="30" id="'+ uniqueId+'"></input></td></tr></table><input type="button" value="'+saveTxt+'" onclick="saveFieldValue(' + uniqueId+');"/>';
	       			
	       			}
	       			else {
	       	 			newdiv.innerHTML = '<b>'+textAreaTxt+':</b> <a href="page2.php?id=' + formId + '&up=' + uniqueId + '"><img src="../images/move_up.gif" width="20" height="20" alt="move up" /></a>  <a href="page2.php?id=' + formId + '&down=' + uniqueId + '"><img src="../images/move_down.gif" width="20" height="20" alt="move down" /></a>  <a href="page2.php?id=' + formId + '&del=' + uniqueId + '"><img src="../images/deleteIcon.png" width="20" height="20" alt="delete" /></a><p></p><table><tr><td>'+leftTxt+':</td><td><input type="text" size="30" id="'+ uniqueId+'"></input></td></tr></table><input type="button" value="'+saveTxt+'" onclick="saveFieldValue(' + uniqueId+');"/>';
	       			
	       			}
			   });
			   
			   
			   num++;
			   window.location = 'page2.php?id=' + formId;
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
      window.location = 'page2.php?id=' + formId;
	}


	
	// If the text field already existed, rebuilt it using data from the db.
	function recreateTextArea(uniqueId, leftText){
		
		
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
	        ni.appendChild(newdiv);
	   	   
	   	   	if(num == 1){
		     		 newdiv.innerHTML = '<b>'+textAreaTxt+':</b> <img src="../images/move_up_dis.gif" width="20" height="20" alt="move up"/>  <a href="page2.php?id=' + formId + '&down=' + uniqueId + '"><img src="../images/move_down.gif" width="20" height="20" alt="move down" /></a>  <a href="page2.php?id=' + formId + '&del=' + uniqueId + '"><img src="../images/deleteIcon.png" width="20" height="20" alt="delete" /></a><p></p><table><tr><td>'+leftTxt+':</td><td><input type="text" id = "'+ uniqueId+ '" value = "' + leftText+ '" size="30"></input></td></tr></table><input type="button" value="'+saveTxt+'" onclick="saveFieldValue(' + uniqueId + ')"/>';
	       			} 
	       	else if(movedownEnabled == 0){
	       	 		newdiv.innerHTML = '<b>'+textAreaTxt+':</b> <a href="page2.php?id=' + formId + '&up=' + uniqueId + '"><img src="../images/move_up.gif" width="20" height="20" alt="move up" /></a> <img src="../images/move_down_dis.gif" width="20" height="20" alt="move down" /> <a href="page2.php?id=' + formId + '&del=' + uniqueId + '"><img src="../images/deleteIcon.png" width="20" height="20" alt="delete" /></a> <p></p><table><tr><td>'+leftTxt+':</td><td><input type="text" id = "'+uniqueId +'" size="30" value="' + leftText+ '"></input></td></tr></table><input type="button" value="'+saveTxt+'" onclick="saveFieldValue(' + uniqueId+')"/>';
	       	
	       	}		
	       	else {
	       	 			newdiv.innerHTML = '<b>'+textAreaTxt+':</b> <a href="page2.php?id=' + formId + '&up=' + uniqueId + '"><img src="../images/move_up.gif" width="20" height="20" alt="move up" /></a> <a href="page2.php?id=' + formId + '&down=' + uniqueId + '"><img src="../images/move_down.gif" width="20" height="20" alt="move down" /></a>  <a href="page2.php?id=' + formId + '&del=' + uniqueId + '"><img src="../images/deleteIcon.png" width="20" height="20" alt="delete" /></a> <p></p><table><tr><td>'+leftTxt+':</td><td><input type="text" id = "'+uniqueId +'" size="30" value="' + leftText+ '"></input></td></tr></table><input type="button" value="'+saveTxt+'" onclick="saveFieldValue(' + uniqueId+')"/>';
	       			
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
			newdiv.style.borderWidth = 1;
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
	         $.post("ajax_functions.php", { type: 'page2addfield', fieldtype: 'dropdown', formid: formId},
   				function(data) {
		     		uniqueId = data;
		     		
			          	if(num == 1){
       					newdiv.innerHTML = '<b>'+dropdownTxt+':</b><img src="../images/move_up_dis.gif" width="20" height="20" alt="move up" /><a href="page2.php?id=' + formId + '&down=' + uniqueId + '"><img src="../images/move_down.gif" width="20" height="20" alt="move down" /></a> <a href="page2.php?id=' + formId + '&t=drop&del=' + uniqueId + '"><img src="../images/deleteIcon.png" width="20" height="20" alt="delete" /></a> <p></p><table><tr><td>'+leftTxt+':</td><td><input type="text" id = "'+uniqueId +'" size="30" value="' + leftText+ '"></input></td></tr></table><input type="button" value="'+saveTxt+'" onclick="saveFieldValue(' + uniqueId+')"/><p></p> <input type="text" id="newitem'+uniqueId +'"></input><input type="button" name="submitbutton" value="'+addItemBtnTxt+'" onclick="addNewItem('+ uniqueId +');"><p></p>'+addedItemsTxt+':<p></p>' + fieldsInHTML;
	       				} 
	       				else if(movedownEnabled == 0){
	       					newdiv.innerHTML = '<b>'+dropdownTxt+':</b> <a href="page2.php?id=' + formId + '&up=' + uniqueId + '"><img src="../images/move_up.gif" width="20" height="20" alt="move up" /></a> <img src="../images/move_down_dis.gif" width="20" height="20" alt="move down" /> <a href="page2.php?id=' + formId + '&t=drop&del=' + uniqueId + '"><img src="../images/deleteIcon.png" width="20" height="20" alt="delete" /></a> <p></p><table><tr><td>'+leftTxt+':</td><td><input type="text" id = "'+uniqueId +'" size="30" value="' + leftText+ '"></input></td></tr></table><input type="button" value="'+saveTxt+'" onclick="saveFieldValue(' + uniqueId+')"/><p></p> <input type="text" id="newitem'+uniqueId +'"></input><input type="button" name="submitbutton" value="'+addItemBtnTxt+'" onclick="addNewItem('+ uniqueId +');"><p></p>'+addedItemsTxt+':<p></p>' + fieldsInHTML;
	       			
	       				}
	       				else {
	       	 			newdiv.innerHTML = '<b>'+dropdownTxt+':</b> <a href="page2.php?id=' + formId + '&up=' + uniqueId + '"><img src="../images/move_up.gif" width="20" height="20" alt="move up" /></a>  <a href="page2.php?id=' + formId + '&down=' + uniqueId + '"><img src="../images/move_down.gif" width="20" height="20" alt="move down" /></a>  <a href="page2.php?id=' + formId + '&t=drop&del=' + uniqueId + '"><img src="../images/deleteIcon.png" width="20" height="20" alt="delete" /></a> <p></p><table><tr><td>'+leftTxt+':</td><td><input type="text" id = "'+uniqueId +'" size="30" value="' + leftText+ '"></input></td></tr></table><input type="button" value="'+saveTxt+'" onclick="saveFieldValue(' + uniqueId+')"/><p></p> <input type="text" id="newitem'+uniqueId +'"></input><input type="button" name="submitbutton" value="'+addItemBtnTxt+'" onclick="addNewItem('+ uniqueId +');"><p></p>'+addedItemsTxt+':<p></p>' + fieldsInHTML;
	       			
	       			}
	       			 		
	       			
			   });
			   
			   
			   num++;
			   window.location = 'page2.php?id=' + formId;
	}
	
		
	// If the text field already existed, rebuilt it using data from the db.
	function recreateDropdown(uniqueId, leftText){
		
		  var fieldsInHTML = 'No fields added..';
		  
		
	       // Get the values for the dropdown menu
	        $.ajaxSetup({async:false});
	        $.post("ajax_functions.php", { id: uniqueId, type: 'getdropdownvalues'},
   
	   		function(data) {
	     		fieldsInHTML = data;
	     		
	    
		
		
		
			var ni = document.getElementById('formdiv');
			var newdiv = document.createElement('div');
			//newdiv.style.backgroundColor = "gray";
			newdiv.style.borderWidth = 1;
			newdiv.style.borderStyle = 'dotted';
	
			newdiv.style.width = 400;
			newdiv.style.height = 400;
	        newdiv.style.marginBottom = 5;
	        newdiv.style.marginLeft = 5;
			
			var divIdName = 'my'+num+'Div';
	        newdiv.setAttribute('id',num);
	        ni.appendChild(newdiv);
	   	   
	   	   	if(num == 1){
       					newdiv.innerHTML = '<b>'+dropdownTxt+':</b><img src="../images/move_up_dis.gif" width="20" height="20" alt="move up" /><a href="page2.php?id=' + formId + '&down=' + uniqueId + '"><img src="../images/move_down.gif" width="20" height="20" alt="move down" /></a> <a href="page2.php?id=' + formId + '&t=drop&del=' + uniqueId + '"><img src="../images/deleteIcon.png" width="20" height="20" alt="delete" /></a> <p></p><table><tr><td>'+leftTxt+':</td><td><input type="text" id = "'+uniqueId +'" size="30" value="' + leftText+ '"></input></td></tr></table><input type="button" value="'+saveTxt+'" onclick="saveFieldValue(' + uniqueId+')"/><p></p> <input type="text" id="newitem'+uniqueId +'"></input><input type="button" name="submitbutton" value="'+addItemBtnTxt+'" onclick="addNewItem('+ uniqueId +');"><p></p>'+addedItemsTxt+':<p></p>' + fieldsInHTML;
	       	}
	       	else if(movedownEnabled == 0){
	       		newdiv.innerHTML = '<b>'+dropdownTxt+':</b> <a href="page2.php?id=' + formId + '&up=' + uniqueId + '"><img src="../images/move_up.gif" width="20" height="20" alt="move up" /></a> <img src="../images/move_down_dis.gif" width="20" height="20" alt="move down" /> <a href="page2.php?id=' + formId + '&t=drop&del=' + uniqueId + '"><img src="../images/deleteIcon.png" width="20" height="20" alt="delete" /></a> <p></p><table><tr><td>'+leftTxt+':</td><td><input type="text" id = "'+uniqueId +'" size="30" value="' + leftText+ '"></input></td></tr></table><input type="button" value="'+saveTxt+'" onclick="saveFieldValue(' + uniqueId+')"/><p></p> <input type="text" id="newitem'+uniqueId +'"></input><input type="button" name="submitbutton" value="'+addItemBtnTxt+'" onclick="addNewItem('+ uniqueId +');"><p></p>'+addedItemsTxt+':<p></p>' + fieldsInHTML;
	       			
	       	} else {
	       	 			newdiv.innerHTML = '<b>'+dropdownTxt+':</b> <a href="page2.php?id=' + formId + '&up=' + uniqueId + '"><img src="../images/move_up.gif" width="20" height="20" alt="move up" /></a>  <a href="page2.php?id=' + formId + '&down=' + uniqueId + '"><img src="../images/move_down.gif" width="20" height="20" alt="move down" /></a>  <a href="page2.php?t=drop&del=' + uniqueId + '"><img src="../images/deleteIcon.png" width="20" height="20" alt="delete" /></a> <p></p><table><tr><td>'+leftTxt+':</td><td><input type="text" id = "'+uniqueId +'" size="30" value="' + leftText+ '"></input></td></tr></table><input type="button" value="'+saveTxt+'" onclick="saveFieldValue(' + uniqueId+')"/><p></p> <input type="text" id="newitem'+uniqueId +'"></input><input type="button" name="submitbutton" value="'+addItemBtnTxt+'" onclick="addNewItem('+ uniqueId +');"><p></p>'+addedItemsTxt+':<p></p>' + fieldsInHTML;
	       			
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
			newdiv.style.borderWidth = 1;
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
	         $.post("ajax_functions.php", { type: 'page2addfield', fieldtype: 'radio', formid: formId},
   				function(data) {
		     		uniqueId = data;
		     		
			          	if(num == 1){
		     		 newdiv.innerHTML = '<b>'+radioTxt+':</b>  <img src="../images/move_up_dis.gif" width="20" height="20" alt="move up" /> <a href="page2.php?id=' + formId + '&down=' + uniqueId + '"><img src="../images/move_down.gif" width="20" height="20" alt="move down" /></a>  <a href="page2.php?del=' + uniqueId + '"><img src="../images/deleteIcon.png" width="20" height="20" alt="delete" /></a><p></p><table><tr><td>'+leftTxt+':</td><td><input type="text" id = "'+ uniqueId+ '" value = "' + leftText+ '" size="30"></input></td></tr></table><input type="button" value="'+saveTxt+'" onclick="saveFieldValue(' + uniqueId + ')"/> <input type="text" id="newitem"></input><p></p><input type="button" name="submitbutton" value="'+addItemBtnTxt+'" onclick="addNewItem('+ uniqueId +');">';
	       			} 
	       			else if(movedownEnabled == 0){
	       				newdiv.innerHTML = '<b>'+radioTxt+':</b> <a href="page2.php?id=' + formId + '&up=' + uniqueId + '"><img src="../images/move_up.gif" width="20" height="20" alt="move up" /></a> <img src="../images/move_down_dis.gif" width="20" height="20" alt="move down" /> <a href="page2.php?id=' + formId + '&t=drop&del=' + uniqueId + '"><img src="../images/deleteIcon.png" width="20" height="20" alt="delete" /></a> <p></p><table><tr><td>'+leftTxt+':</td><td><input type="text" id = "'+uniqueId +'" size="30" value="' + leftText+ '"></input></td></tr></table><input type="button" value="'+saveTxt+'" onclick="saveFieldValue(' + uniqueId+')"/><p></p> <input type="text" id="newitem'+uniqueId +'"></input><input type="button" name="submitbutton" value="'+addItemBtnTxt+'" onclick="addNewItem('+ uniqueId +');"><p></p>'+addedItemsTxt+':<p></p>' + fieldsInHTML;
	       			
	       			}
	       			 else {
	       	 			newdiv.innerHTML = '<b>'+radioTxt+':</b> <a href="page2.php?id=' + formId + '&up=' + uniqueId + '"><img src="../images/move_up.gif" width="20" height="20" alt="move up" /></a>  <a href="page2.php?down=' + uniqueId + '"><img src="../images/move_down.gif" width="20" height="20" alt="move down" /></a>  <a href="page2.php?id=' + formId + '&t=drop&del=' + uniqueId + '"><img src="../images/deleteIcon.png" width="20" height="20" alt="delete" /></a> <p></p><table><tr><td>'+leftTxt+':</td><td><input type="text" id = "'+uniqueId +'" size="30" value="' + leftText+ '"></input></td></tr></table><input type="button" value="'+saveTxt+'" onclick="saveFieldValue(' + uniqueId+')"/><p></p> <input type="text" id="newitem'+uniqueId +'"></input><input type="button" name="submitbutton" value="'+addItemBtnTxt+'" onclick="addNewItem('+ uniqueId +');"><p></p>'+addedItemsTxt+':<p></p>' + fieldsInHTML;
	       			
	       			}
	       			 		
	       			
			   });
			   
			   
			   num++;
			   window.location = 'page2.php?id=' + formId;
	}
	
		
	// If the text field already existed, rebuilt it using data from the db.
	function recreateRadio(uniqueId, leftText){
		
		  var fieldsInHTML = 'No fields added..';
		  
		
		    
	       // Get the values for the dropdown menu
	        $.ajaxSetup({async:false});
	        $.post("ajax_functions.php", { id: uniqueId, type: 'getdropdownvalues'},
   
	   		function(data) {
	     		fieldsInHTML = data;
	     		
	    
		
		
		
			var ni = document.getElementById('formdiv');
			var newdiv = document.createElement('div');
			//newdiv.style.backgroundColor = "gray";
			newdiv.style.borderWidth = 1;
			newdiv.style.borderStyle = 'dotted';
	
			newdiv.style.width = 400;
			newdiv.style.height = 400;
	        newdiv.style.marginBottom = 5;
	        newdiv.style.marginLeft = 5;
			newdiv.style.overflow = 'auto';
			var divIdName = 'my'+num+'Div';
	        newdiv.setAttribute('id',num);
	        ni.appendChild(newdiv);
	   	   
	   	   	if(num == 1){
		     		 newdiv.innerHTML = '<b>'+radioTxt+':</b>  <img src="../images/move_up_dis.gif" width="20" height="20" alt="move up" /> <a href="page2.php?id=' + formId + '&down=' + uniqueId + '"><img src="../images/move_down.gif" width="20" height="20" alt="move down" /></a>  <a href="page2.php?id=' + formId + '&del=' + uniqueId + '"><img src="../images/deleteIcon.png" width="20" height="20" alt="delete" /></a><p></p><table><tr><td>'+leftTxt+':</td><td><input type="text" id = "'+ uniqueId+ '" value = "' + leftText+ '" size="30"></input></td></tr></table><input type="button" value="'+saveTxt+'" onclick="saveFieldValue(' + uniqueId + ')"/> <input type="text" id="newitem"></input><p></p><input type="button" name="submitbutton" value="'+addItemBtnTxt+'" onclick="addNewItem('+ uniqueId +');">';
	       			}
	       	else if(movedownEnabled == 0){
	       				newdiv.innerHTML = '<b>'+radioTxt+':</b> <a href="page2.php?id=' + formId + '&up=' + uniqueId + '"><img src="../images/move_up.gif" width="20" height="20" alt="move up" /></a> <img src="../images/move_down_dis.gif" width="20" height="20" alt="move down" /> <a href="page2.php?id=' + formId + '&t=drop&del=' + uniqueId + '"><img src="../images/deleteIcon.png" width="20" height="20" alt="delete" /></a> <p></p><table><tr><td>'+leftTxt+':</td><td><input type="text" id = "'+uniqueId +'" size="30" value="' + leftText+ '"></input></td></tr></table><input type="button" value="'+saveTxt+'" onclick="saveFieldValue(' + uniqueId+')"/><p></p> <input type="text" id="newitem'+uniqueId +'"></input><input type="button" name="submitbutton" value="'+addItemBtnTxt+'" onclick="addNewItem('+ uniqueId +');"><p></p>'+addedItemsTxt+':<p></p>' + fieldsInHTML;
	       		
	       	} else {
	       	 			newdiv.innerHTML = '<b>'+radioTxt+':</b> <a href="page2.php?id=' + formId + '&up=' + uniqueId + '"><img src="../images/move_up.gif" width="20" height="20" alt="move up" /></a>  <a href="page2.php?down=' + uniqueId + '"><img src="../images/move_down.gif" width="20" height="20" alt="move down" /></a>  <a href="page2.php?id=' + formId + '&t=drop&del=' + uniqueId + '"><img src="../images/deleteIcon.png" width="20" height="20" alt="delete" /></a> <p></p><table><tr><td>'+leftTxt+':</td><td><input type="text" id = "'+uniqueId +'" size="30" value="' + leftText+ '"></input></td></tr></table><input type="button" value="'+saveTxt+'" onclick="saveFieldValue(' + uniqueId+')"/><p></p> <input type="text" id="newitem'+uniqueId +'"></input><input type="button" name="submitbutton" value="'+addItemBtnTxt+'" onclick="addNewItem('+ uniqueId +');"><p></p>'+addedItemsTxt+':<p></p>' + fieldsInHTML;
	       			
	       			}
	       			 		
		   });
			
	       			num++;
	}
	
	
	// Saves the text field data to the database
	// by passing the field id.
	function saveFieldValue(id){
		
		var value = document.getElementById(id).value;
        var currentId = id;
       $.ajaxSetup({async:false});
        $.post("ajax_functions.php", { type: 'updatefield', id: currentId, value: value},
   				function(data) {
		     		
		          
			   });
			   
			   
			
			  window.location = 'page2.php?id=' + formId;  
		
		
	}
	
	
	
</script>
<?php


		
		
class courserequest_form extends moodleform {
 
    function definition() {
        
		//call the javascript setLang function to pass in lang string values
		
		
		
		global $CFG;
        global $USER;
        $mform =& $this->_form; // Don't forget the underscore! 
 
 
       global $formId;
	   global $formName;
  
	 	$mform->addElement('header', 'mainheader', get_string('formBuilder_p2_header','block_cmanager'));
		
	 
	 
	 	$htmlOutput = '
	 
			<br><b>'.get_string('formBuilder_editingForm','block_cmanager').':</b> ' .$formName.'<br><br>
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
			<center><b>' .$formName.' '.get_string('formBuilder_shownbelow','block_cmanager').'</b></center><br>
			</div><p></p><br>
			
			
	<script type="text/javascript">
				setLangStrings("'.get_string('formBuilder_dropdownTxt','block_cmanager').'","'.get_string('formBuilder_radioTxt','block_cmanager').'","'.get_string('formBuilder_textAreaTxt','block_cmanager').'","'.get_string('formBuilder_textFieldTxt','block_cmanager').'","'.get_string('formBuilder_leftTxt','block_cmanager').'","'.get_string('formBuilder_saveTxt','block_cmanager').'","'.get_string('formBuilder_addedItemsTxt','block_cmanager').'","'.get_string('formBuilder_addItemBtnTxt','block_cmanager').'")
			</script>

			


			<div id="formdiv">
			
			
			</div>
			
			<a href="preview.php?id=' . $formId . '">'.get_string('formBuilder_previewForm','block_cmanager').'</a>
			<center><a href="../cmanager_admin.php"><input type="button" value="'.get_string('formBuilder_returntoCM','block_cmanager').'"/></a></center>
		';
		
		
		
		
	 	$mform->addElement('html', $htmlOutput);
 
 
 
 
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

		    
		    "<a href=\"../cmanager_admin.php\">".get_string('cmanagerDisplaySearchForm','block_cmanager')."</a> ->
		     $strnav $streditinga", $mform->focus(), "", false);
		    
		    $mform->set_data($toform);
		    $mform->display();
		    print_footer();
			
} else {

          print_header_simple($streditinga, '',

		    
		    "<a href=\"../cmanager_admin.php\">".get_string('cmanagerDisplaySearchForm','block_cmanager')."</a> ->
		     $strnav $streditinga", $mform->focus(), "", false);
		    
		    $mform->set_data($toform);
		    $mform->display();
		    print_footer();
			
		

 
}



		// If any fields currently exist, add them to the page for editing
		$selectQuery = "";
	
		// Count the total number of records
		$numberOfFields = count_records('cmanager_formfields', 'formid', $formId);
	  
							  
	    $formFields = get_records('cmanager_formfields', 'formid', $formId, $sort='position ASC', $fields='*', $limitfrom='', $limitnum='');
		
	
	    $recCounter = 1;						  
		foreach($formFields as $field){
			   	
			   // If we are on the last record, disable the move down option.
			   if($numberOfFields == $recCounter){
			   	 
				    echo '<script>movedownEnabled = 0;</script>';
			   	
			   }
			   
			   
			   if($field->type == 'textfield'){
			   	
				echo "<script>
				       recreateTextField('". $field->id ."', '". $field->lefttext ."');
			      </script>
			      ";
			   }
			   else if($field->type == 'textarea'){
			   	echo "<script>
				       recreateTextArea('". $field->id ."', '". $field->lefttext ."');
			      </script>
			      ";
			   }
			   else if($field->type == 'dropdown'){
			   	echo "<script>
				       recreateDropdown('". $field->id ."', '". $field->lefttext ."');
			      </script>
			      ";
			   }
			   
			   else if($field->type == 'radio'){
			   	echo "<script>
				       recreateRadio('". $field->id ."', '". $field->lefttext ."');
			      </script>
			      ";
			   }
			   
			   $recCounter++;
		}




?>

  
	
		