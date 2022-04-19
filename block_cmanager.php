<?php
// This file is part of Course Request Manager for Moodle - http://moodle.org/
//
// Course Request Manager is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Course Request Manager is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Contains block_cmanager
 *
 * @package    block_cmanager
 * @copyright  2012-2018 Kyle Goslin, Daniel McSweeney (Institute of Technology Blanchardstown)
 * @copyright  2021-2022 TNG Consulting Inc.
 * @author     Kyle Goslin, Daniel McSweeney
 * @author     Michael Milette
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

 /**
  * A block which displays the Course Request Manager
  *
  * @package    block_cmanager
  * @copyright  2022 TNG Consulting Inc.
  * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
  */
class block_cmanager extends block_list {
    /**
     * Initialize the block.
     *
     * @return void
     */
    function init() {
        $this->title = get_string('plugindesc', 'block_cmanager');
    }

    /**
     * Get the content displayed in the block.
     *
     * @return object Contains the content and footer for the block.
     */
    function get_content() {
        if ($this->content !== NULL) {
            return $this->content;
        }

        $this->content =  new stdClass;
        $this->content->items = array();
        $this->content->icons = array();
        $this->content->footer = '';

        if (isloggedin() and !isguestuser()) {   // Show the block if logged-in.
            global $DB, $CFG;
            $context = context_system::instance();
            $requests = $DB->count_records('block_cmanager_records', array('status'=>'PENDING'));

            // For regular users.

            // Make a request.
            $this->content->items[] = $this->builditem('block_request', 'course_request.php', ['mode' => '1'], 'makereq.png');
            // Manage your requests.
            $this->content->items[] = $this->builditem('block_manage', 'module_manager.php', [], 'man_req.png');
            // My archived requests.
            $this->content->items[] = $this->builditem('myarchivedrequests', 'module_manager_history.php', [], 'arch_req.png');

            // For administrators.

            if (has_capability('block/cmanager:approverecord', $context)) {
                // Request queue.
                $this->content->items[] = $this->builditem('block_admin', 'cmanager_admin.php', [], 'queue.png', "[$requests]");
                // Configuration.
                $this->content->items[] = $this->builditem('block_config', 'cmanager_confighome.php', [], 'config.png');
                // All archived requests.
                $this->content->items[] = $this->builditem('allarchivedrequests', 'cmanager_admin_arch.php', [], 'all_arch.png');
            }
        }
        return $this->content;
    }

    /**
     * Allow the block to be placed on any page.
     *
     * @return void
     */
    function applicable_formats() {
        return array('all' => true);
    }

    /**
     * Disable ability to add multiple instances to a page.
     *
     * @return bool false
     */
    function instance_allow_multiple() {
        return false;
    }

    /**
     * Enable plugin settings.php.
     *
     * @return bool true
     */
    function has_config() {
        return true;
    }

    /**
     * Enable block instance's settings.
     *
     * @return bool true
     */
    function instance_allow_config() {
        return true;
    }

    function builditem($identifier, $url, $query = [], $icon = '', $identifierparam = '') {
        global $CFG;

        $string = get_string($identifier, 'block_cmanager') . rtrim(' ' . $identifierparam);
        $icon = html_writer::empty_tag('img', [
            'src' => $CFG->wwwroot . '/blocks/cmanager/icons/' . $icon,
            'alt' => '',
            'class' => 'icon'
        ]);

        return html_writer::link(new moodle_url('/blocks/cmanager/' . $url, $query), $icon . $string);
    }
} // End class.
