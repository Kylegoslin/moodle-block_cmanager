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
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace block_cmanager\event;
defined('MOODLE_INTERNAL') || die();


class course_created extends \core\event\base {
    protected function init() {
        $this->data['crud'] = 'c'; // c(reate), r(ead), u(pdate), d(elete)
        $this->data['edulevel'] = self::LEVEL_OTHER;
        $this->data['objecttable'] = '';
    }
 
    public static function get_name() {
        return get_string('approverequest_New', 'block_cmanager');
    }
 
    public function get_description() {
        return "user {$this->userid} :" . $this->other. ' '.get_string('createdsuccess', 'block_cmanager');
    }
 
    public function get_url() {
        return new \moodle_url('/blocks/cmanager/cmanager_admin.php');
    }
 
    public function get_legacy_logdata() {
      // 
    }
 
    public static function get_legacy_eventname() {
      //
    }
 
    protected function get_legacy_eventdata() {
      //
    }
}