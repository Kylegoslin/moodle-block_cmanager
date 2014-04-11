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

require_once("../../config.php");
global $CFG, $DB;





// Used to store to where clause for this query
// Depends on the amount of fields which have been included
// by the user.
$selectQuery = '';



//  Search for specific module name
// 
$modname = $_POST['modname'];

$includeAnd = '';
if($modname != ''){
  $selectQuery = "fullname LIKE '%$modname%'";
  $includeAnd = ' OR ';
}


$_SESSION['form_modulename'] = $modname;




// Narrow down by department
$dep = $_POST['dep'];
if($dep != '' && $dep != 'Any'){
  
 $selectQuery .= " $includeAnd fullname LIKE '%$dep%'";
  $includeAnd = ' OR ';
}


$category = $_POST['category'];
if($category != '' && $category != 'Any'){


  $selectQuery .= " $includeAnd fullname LIKE '%$category%'";
  $includeAnd = ' OR ';

}

session_start();
$coursecode = $_POST['code'];
$_SESSION['formcoursecode'] = $coursecode;

if($coursecode != '' && $coursecode != 'Any'){


  $selectQuery .= " $includeAnd fullname LIKE '%$coursecode%' OR shortname LIKE '%$coursecode%'";
  $includeAnd = ' OR ';


}







    $allRecords = $DB->get_recordset_select('course', $select=$selectQuery, $sort='', $fields='*', 
                                      $limitfrom='', $limitnum='');

echo '

<style>

#foundlist {  
  font-family:arial; 
  font-size: 10pt; 
   }
</style>

';

echo "<div id=\"foundlist\">";
echo "<table width = '700px'>";

	foreach($allRecords as $record){
                
                echo '<tr>';
                echo '<td width=\'50px\'><b>' . $record['shortname'] . '</b></td>';
  		echo '<td width=\'250px\'>' . $record['fullname'] . '</td>';
  		echo '<td width=\'70px\'><a href="course_request.php?amod=' . $record['id'] .'">'.get_string('request_addModule','block_cmanager').'</a></td>';  		
		echo '</tr>';
	}

echo "</table>";

echo "</div>";









?>
