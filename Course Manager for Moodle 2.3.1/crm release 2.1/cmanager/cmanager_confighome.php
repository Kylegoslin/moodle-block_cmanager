<title>Course Manager</title>
<link rel="stylesheet" type="text/css" href="css/main.css" />
<?php

require_once("../../config.php");
require_once("$CFG->libdir/formslib.php");

/** Navigation Bar **/
$PAGE->navbar->ignore_active();
$PAGE->navbar->add(get_string('cmanagerDisplay', 'block_cmanager'), new moodle_url('/blocks/cmanager/cmanager_admin.php'));
$PAGE->navbar->add(get_string('configurecoursemanagersettings', 'block_cmanager'));

$PAGE->set_url('/blocks/cmanager/block_cmanager_confighome.php');
$PAGE->set_context(get_system_context());

class courserequest_form extends moodleform {
 
	function definition() {
	
		$mform =& $this->_form; // Don't forget the underscore! 
		$mform->addElement('header', 'mainheader', get_string('configurecoursemanagersettings','block_cmanager'));
		
		$mainSlider = "<div style=\"text-indent: -2em; padding-left: 2em;\">
		<p></p>
		<a href=\"cmanager_config.php\">".get_string('configurecoursemanagersettings','block_cmanager')."</a><br>".get_string('configure_instruction1','block_cmanager')."<p></p><br>	
		<p></p>
		<a href=\"formeditor/page1.php\">".get_string('configurecourseformfields','block_cmanager')."</a><br>".get_string('configure_instruction2','block_cmanager')."<p></p><br>
		<p></p>
		<a href=\"formeditor/form_builder.php\">".get_string('informationform','block_cmanager')."</a><br>".get_string('configure_instruction3','block_cmanager')."<p></p><br>
		</div>";
		
		$mform->addElement('html', $mainSlider);

    } // Close the function
}  // Close the class






$mform = new courserequest_form();

if ($mform->is_cancelled()){
    
} 

else if ($fromform=$mform->get_data()){
 
} 

else {
	print_header_simple($streditinga='', '', $mform->focus(), "", false);
	$mform->display();
	echo $OUTPUT->footer();
 
}

?>		
