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
 * Settings for Course Request Manager.
 *
 * @package    block_cmanager
 * @copyright  2021-2022 TNG Consulting Inc. - www.tngconsulting.ca
 * @author     Michael Milette
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {
    $settings->add(
        new admin_setting_configcheckbox(
            'block_cmanager/norequestcontrol',
            get_string('norequestcontrol', 'block_cmanager'),
            get_string('norequestcontrol_desc', 'block_cmanager'),
            0 // Default.
        )
    );
}
