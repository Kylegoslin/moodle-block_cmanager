<?php
/* --------------------------------------------------------- 
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
 --------------------------------------------------------- */



require_once("../../../config.php");
global $CFG;
$formPath = "$CFG->libdir/formslib.php";
require_once($formPath);


class courserequest_form extends moodleform {
 
    function definition() {
        global $CFG;
        global $USER;
        $mform =& $this->_form; // Don't forget the underscore! 
 
 
       
  
	 	$mform->addElement('header', 'mainheader', 'Configure Search Query');

	 
	 
	 	$htmlOutput = '
	 
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
 
} else {

          print_header_simple($streditinga, '',

		    
		    "<a href=\"../cmanager_admin.php\">".get_string('cmanagerDisplaySearchForm','block_cmanager')."</a> ->
		     $strnav $streditinga", $mform->focus(), "", false);
		    
		    $mform->set_data($toform);
		    $mform->display();
		    print_footer();
 
}







?>

  
	
		