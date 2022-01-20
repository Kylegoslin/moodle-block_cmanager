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
 * Version information for Course Request Manager
 *
 * @package    block_cmanager
 * @copyright  2012-2018 Kyle Goslin, Daniel McSweeney (Institute of Technology Blanchardstown)
 * @copyright  2021-2022 Michael Milette (TNG Consulting Inc.)
 * @author     Kyle Goslin, Daniel McSweeney
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$plugin->version   = 2022012000;      // The current module version (Date: YYYYMMDDXX)
$plugin->requires  = 2019052000;      // Requires Moodle 3.7 or later.
$plugin->component = 'block_cmanager';
$plugin->maturity = MATURITY_BETA;
$plugin->release = '5.3.1';
