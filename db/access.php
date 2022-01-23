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
 * Plugin capabilities.
 *
 * @package    block_cmanager
 * @copyright  2012-2014 Kyle Goslin, Daniel McSweeney (Institute of Technology Blanchardstown)
 * @copyright  2021-2022 TNG Consulting Inc.
 * @author     Kyle Goslin, Daniel McSweeney
 * @author     Michael Milette
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
$capabilities = array(

    'block/cmanager:myaddinstance' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => array(
            'editingteacher' => CAP_ALLOW,
            'coursecreator' => CAP_ALLOW,
            'manager' => CAP_ALLOW

        ),

        'clonepermissionsfrom' => 'moodle/my:manageblocks'
    ),

    'block/cmanager:addinstance' => array(
        'riskbitmask' => RISK_SPAM | RISK_XSS,

        'captype' => 'write',
        'contextlevel' => CONTEXT_BLOCK,
        'archetypes' => array(
            'editingteacher' => CAP_ALLOW,
            'coursecreator' => CAP_ALLOW,
            'manager' => CAP_ALLOW
        ),

        'clonepermissionsfrom' => 'moodle/site:manageblocks'
    ),

    'block/cmanager:approverecord' => array(
        'riskbitmask' => RISK_SPAM | RISK_XSS,
        'captype' => 'write',
        'contextlevel' => CONTEXT_SYSTEM,
        'legacy' => array(
        	'coursecreator' => CAP_ALLOW,
            'teacher'        => CAP_PREVENT,
            'editingteacher' => CAP_PREVENT,
            'manager'          => CAP_ALLOW,
            'student'        => CAP_PREVENT,
            'guest' => CAP_PREVENT
        ),
	),

 	'block/cmanager:denyrecord' => array(
        'riskbitmask' => RISK_SPAM | RISK_XSS,
        'captype' => 'write',
        'contextlevel' => CONTEXT_SYSTEM,
        'legacy' => array(
        	'coursecreator' => CAP_ALLOW,
            'teacher'        => CAP_PREVENT,
            'editingteacher' => CAP_PREVENT,
            'manager'          => CAP_ALLOW,
            'student'        => CAP_PREVENT,
            'guest' => CAP_PREVENT
        ),
	),

 	'block/cmanager:editrecord' => array(
        'riskbitmask' => RISK_SPAM | RISK_XSS,
        'captype' => 'write',
        'contextlevel' => CONTEXT_SYSTEM,
        'legacy' => array(
        	'coursecreator' => CAP_ALLOW,
            'teacher'        => CAP_ALLOW,
            'editingteacher' => CAP_ALLOW,
            'manager'          => CAP_ALLOW,
            'student'        => CAP_PREVENT,
            'guest' => CAP_PREVENT
        ),
	),

	'block/cmanager:deleterecord' => array(
        'riskbitmask' => RISK_SPAM | RISK_XSS,
        'captype' => 'write',
        'contextlevel' => CONTEXT_SYSTEM,
        'legacy' => array(
        	'coursecreator' => CAP_ALLOW,
            'teacher'        => CAP_ALLOW,
            'editingteacher' => CAP_ALLOW,
            'manager'          => CAP_ALLOW,
            'student'        => CAP_PREVENT,
            'guest' => CAP_PREVENT
        ),
	),

 	'block/cmanager:addrecord' => array(
        'riskbitmask' => RISK_SPAM | RISK_XSS,
        'captype' => 'write',
        'contextlevel' => CONTEXT_SYSTEM,
        'legacy' => array(
        	'coursecreator' => CAP_ALLOW,
            'teacher'        => CAP_ALLOW,
            'editingteacher' => CAP_ALLOW,
            'manager'          => CAP_ALLOW,
            'student'        => CAP_PREVENT,
            'guest' => CAP_PREVENT
        ),
	),


 	'block/cmanager:viewrecord' => array(
        'riskbitmask' => RISK_SPAM | RISK_XSS,
        'captype' => 'write',
        'contextlevel' => CONTEXT_SYSTEM,
        'legacy' => array(
        	'coursecreator' => CAP_ALLOW,
            'teacher'        => CAP_ALLOW,
            'editingteacher' => CAP_ALLOW,
            'manager'          => CAP_ALLOW,
            'student'        => CAP_PREVENT,
            'guest' => CAP_PREVENT
        ),
	),

 	'block/cmanager:addcomment' => array(
        'riskbitmask' => RISK_SPAM | RISK_XSS,
        'captype' => 'write',
        'contextlevel' => CONTEXT_SYSTEM,
        'legacy' => array(
        	'coursecreator' => CAP_ALLOW,
            'teacher'        => CAP_ALLOW,
            'editingteacher' => CAP_ALLOW,
            'manager'          => CAP_ALLOW,
            'student'        => CAP_PREVENT,
            'guest' => CAP_PREVENT
        ),
	),

 	'block/cmanager:viewconfig' => array(
        'riskbitmask' => RISK_SPAM | RISK_XSS,
        'captype' => 'write',
        'contextlevel' => CONTEXT_SYSTEM,
        'legacy' => array(
            'teacher'        => CAP_PREVENT,
            'editingteacher' => CAP_PREVENT,
            'manager'          => CAP_PREVENT,
            'student'        => CAP_PREVENT,
            'guest' => CAP_PREVENT
        ),
	),

    // Hide block if not logged-in.
    'block/cmanager:view' => array(
        'captype' => 'read',
        'contextlevel' => CONTEXT_BLOCK,
        'archetypes' => array(
            'user' => CAP_ALLOW,
            'guest' => CAP_PREVENT,
            'student' => CAP_ALLOW,
            'teacher' => CAP_ALLOW,
            'editingteacher' => CAP_ALLOW,
            'manager' => CAP_ALLOW
        )
    )
);
