
<?php
//ini_set('display_errors', 1); 
//error_reporting(E_ALL);


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

  
	
		