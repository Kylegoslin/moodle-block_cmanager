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
 * Course request manager block for moodle main block interface
 * @package    block_cmanager
 * @copyright  2018 Kyle Goslin, Daniel McSweeney
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_cmanager extends block_base {


    
    /** Init for the block */
    function init() {

        $this->title = get_string('plugindesc', 'block_cmanager');
        $plugin = new stdClass();
        $plugin->version   = 2018061351;      // The current module version (Date: YYYYMMDDXX)
        $plugin->requires  = 2018051700.00;   // Requires this Moodle version
    }


    /** Get the content for the block */
    function get_content() {
		require_login();
        global $CFG;
        global $COURSE;
        global $DB;
        if ($this->content !== NULL) {
            return $this->content;
        }

        $this->content =  new stdClass;
        $this->content->text = block_cmanager_get_html_content();
        $this->content->footer = '';

        return $this->content;
    }

}


    /**
    * This is the main content generation function that is responsible for
    * returning the relevant content to the user depending on what status
    * they have (admin / regular staff).
    */
    function block_cmanager_get_html_content(){

        global $USER, $DB, $CFG;
		$context = context_system::instance();
        $adminHTML = '';
        $numRequestsPending = 0;
        $numRequestsPending = $DB->count_records('block_cmanager_records', array('status'=>'PENDING'));

            // Admin UI elements
         	if (has_capability('block/cmanager:approverecord',$context)) {
           
            $adminHTML = '<br> <img src="'.$CFG->wwwroot.'/blocks/cmanager/icons/queue.png"/> <a href ="'.$CFG->wwwroot. '/blocks/cmanager/cmanager_admin.php' .'">'.get_string('block_admin','block_cmanager').' ['.$numRequestsPending.']</a><br>
            <img src="'.$CFG->wwwroot.'/blocks/cmanager/icons/config.png"/> <a href ="'.$CFG->wwwroot.'/blocks/cmanager/cmanager_confighome.php">'.get_string('block_config','block_cmanager').'</a><br>
            <img src="'.$CFG->wwwroot.'/blocks/cmanager/icons/all_arch.png"/> <a href ="'.$CFG->wwwroot.'/blocks/cmanager/cmanager_admin_arch.php">'.get_string('allarchivedrequests','block_cmanager').'</a>';

        	}
    	

    // UI components for regular users of the block
    // to allow requests to be made
    $var1 = '';
    if ((isloggedin() && $USER->id != 1)) {
        $var1 = "
        <hr>
        <a href =\"".$CFG->wwwroot."/blocks/cmanager/course_request.php?mode=1\"><img src=\"".$CFG->wwwroot."/blocks/cmanager/icons/makereq.png\"/> ".get_string('block_request','block_cmanager')."</a><br>
        <img src=\"".$CFG->wwwroot."/blocks/cmanager/icons/man_req.png\"/> <a href =\"".$CFG->wwwroot."/blocks/cmanager/module_manager.php\">".get_string('block_manage','block_cmanager')."</a><br>
        <img src=\"".$CFG->wwwroot."/blocks/cmanager/icons/arch_req.png\"/> <a href =\"".$CFG->wwwroot."/blocks/cmanager/module_manager_history.php\">".get_string('myarchivedrequests','block_cmanager')."</a>

        <hr>
        $adminHTML
        ";
    }
    
    return $var1;

}//end function

