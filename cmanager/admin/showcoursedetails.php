<?php 
/* -----------------------------------------------------------------
 * 
 * 
 *  Course Request Manager
 *  2012-2013 Kyle Goslin, Daniel McSweeney
 * 
 * 
 * 
 * 
 * ------------------------------------------------------------------
 */

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