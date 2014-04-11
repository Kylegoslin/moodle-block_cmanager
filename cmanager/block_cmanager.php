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


class block_cmanager extends block_base {




function init() {

$this->title = get_string('plugindesc', 'block_cmanager');
$plugin = new stdClass();
$plugin->version   = 2014041041;      // The current module version (Date: YYYYMMDDXX)
$plugin->requires  = 2011120500.00;      // Requires this Moodle version
}


function get_content() {

if ($this->content !== NULL) {
  return $this->content;
}

global $CFG;
global $COURSE;
global $DB;



$this->content =  new stdClass;
$this->content->text = getHTMLContent();
$this->content->footer = '';

return $this->content;
}
}

/*
This is the main content generation function that is responsible for
returning the relevant content to the user depending on what status
they have (admin / student).

*/
function getHTMLContent(){

global $USER, $DB, $CFG;

$adminHTML = '';
if ($admins = get_admins()) {

$loginIsValid = False;

foreach ($admins as $admin) {
            if($admin->id == $USER->id){
                 $loginIsValid = True;
            }

}

if($loginIsValid == True){

    $numRequestsPending = 0;

    $numRequestsPending = $DB->count_records('block_cmanager_records', array('status'=>'PENDING'));

    $adminHTML = '<br> <img src="'.$CFG->wwwroot.'/blocks/cmanager/icons/queue.ico"/> <a href ="'.$CFG->wwwroot. '/blocks/cmanager/cmanager_admin.php' .'">'.get_string('block_admin','block_cmanager').' ['.$numRequestsPending.']</a><br>
                  <img src="'.$CFG->wwwroot.'/blocks/cmanager/icons/config.ico"/> <a href ="'.$CFG->wwwroot.'/blocks/cmanager/cmanager_confighome.php">'.get_string('block_config','block_cmanager').'</a><br>
                 <img src="'.$CFG->wwwroot.'/blocks/cmanager/icons/all_arch.ico"/> <a href ="'.$CFG->wwwroot.'/blocks/cmanager/cmanager_admin_arch.php">'.get_string('allarchivedrequests','block_cmanager').'</a>';

}
}
$var1 = '';
if((isloggedin() && $USER->id != 1)){

$var1 = "
<hr>
 <img src=\"".$CFG->wwwroot."/blocks/cmanager/icons/make_req.ico\"/> <a href =\"".$CFG->wwwroot."/blocks/cmanager/course_request.php?new=1\">".get_string('block_request','block_cmanager')."</a><br>
 <img src=\"".$CFG->wwwroot."/blocks/cmanager/icons/man_req.ico\"/> <a href =\"".$CFG->wwwroot."/blocks/cmanager/module_manager.php\">".get_string('block_manage','block_cmanager')."</a><br>
  <img src=\"".$CFG->wwwroot."/blocks/cmanager/icons/arch_req.ico\"/> <a href =\"".$CFG->wwwroot."/blocks/cmanager/module_manager_history.php\">".get_string('myarchivedrequests','block_cmanager')."</a>

<hr>
   $adminHTML
";
}
return $var1;

}//end function

