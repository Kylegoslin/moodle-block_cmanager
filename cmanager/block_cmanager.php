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




/**
 * COURSE REQUEST MANAGER
 *
 * Course request manager block for moodle main block interface
 * @package    block_cmanager
 * @copyright  2014 Kyle Goslin, Daniel McSweeney
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_cmanager extends block_base {


    /** Init for the block */
    function init() {

        $this->title = get_string('plugindesc', 'block_cmanager');

    }


    /** Get the content for the block */
    /**
    * This is the main content generation function that is responsible for
    * returning the relevant content to the user depending on capability
    * they have (admin / student).
    */
    function get_content() {

        global $CFG, $COURSE, $DB;
        if ($this->content !== NULL) {
            return $this->content;
        }


        $HTML = '<hr>';

        $context = context_system::instance();

        if(has_capability('block/cmanager:addrecord', $context)){
            $HTML .= '<img src="'.$CFG->wwwroot.'/blocks/cmanager/icons/make_req.ico"/> <a href ="'.$CFG->wwwroot.'/blocks/cmanager/course_request.php?mode=1">'.get_string('block_request','block_cmanager').'</a><br />';
        }
        if(has_capability('block/cmanager:editrecord', $context)){
            $HTML .= '<img src="'.$CFG->wwwroot.'/blocks/cmanager/icons/man_req.ico"/> <a href ="'.$CFG->wwwroot.'/blocks/cmanager/module_manager.php">'.get_string('block_manage','block_cmanager').'</a><br />
                      <img src="'.$CFG->wwwroot.'/blocks/cmanager/icons/arch_req.ico"/> <a href ="'.$CFG->wwwroot.'/blocks/cmanager/module_manager_history.php">'.get_string('myarchivedrequests','block_cmanager').'</a>
                      <hr>';
        }


        if(has_capability('block/cmanager:approverecord', $context)){
            $numRequestsPending = 0;
            $numRequestsPending = $DB->count_records('block_cmanager_records', array('status'=>'PENDING'));
            $HTML .=     '<img src="'.$CFG->wwwroot.'/blocks/cmanager/icons/queue.ico"/> <a href ="'.$CFG->wwwroot. '/blocks/cmanager/cmanager_admin.php' .'">'.get_string('block_admin','block_cmanager').' ['.$numRequestsPending.']</a><br />
                          <img src="'.$CFG->wwwroot.'/blocks/cmanager/icons/all_arch.ico"/> <a href ="'.$CFG->wwwroot.'/blocks/cmanager/cmanager_admin_arch.php">'.get_string('allarchivedrequests','block_cmanager').'</a><br />';
        }

        if(has_capability('block/cmanager:viewconfig', $context)){
            $HTML .=     '<img src="'.$CFG->wwwroot.'/blocks/cmanager/icons/config.ico"/> <a href ="'.$CFG->wwwroot.'/blocks/cmanager/cmanager_confighome.php">'.get_string('block_config','block_cmanager').'</a><br />';
        }

        $this->content =  new stdClass;
        $this->content->text = $HTML;
        $this->content->footer = '';

        return $this->content;
    }

}