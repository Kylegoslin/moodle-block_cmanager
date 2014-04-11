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

require_once("../../../config.php");
global $CFG, $DB;
$formPath = "$CFG->libdir/formslib.php";
require_once($formPath);
require_login();
require_once('../validate_admin.php');
require_once('../lib/displayLists.php');
$mid = required_param('id', PARAM_INT);




	
	$rec = $DB->get_recordset_select('block_cmanager_records', 'id = ' . $mid);
   	$displayModHTML = displayAdminList($rec, false, false, false, '');
	echo '<div style="font-family: Arial,Verdana,Helvetica,sans-serif">';
	echo $displayModHTML;
	echo '</div>';
?>