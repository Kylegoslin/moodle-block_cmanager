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
  require_login();

// Used to store to where clause for this query
// Depends on the amount of fields which have been included
// by the user.
$selectQuery = '';



//  Search for specific module name
// 
$modname = $_POST['modname'];

$includeAnd = '';
if($modname != ''){
  $selectQuery = "Crse_Title LIKE '%$modname%'";
  $includeAnd = ' AND ';
}



// Narrow down by department
$dep = $_POST['dep'];
if($dep != '' && $dep != 'Any'){
  
 $selectQuery .= " $includeAnd Dept = '$dep'";
  $includeAnd = ' AND ';
}


$category = $_POST['category'];
if($category != '' && $category != 'Any'){


  $selectQuery .= " $includeAnd Subj_Desc LIKE '%$category%'";
  $includeAnd = ' AND ';

}





    $allRecords = $DB->get_recordset_select('cmanager_courses', $select=$selectQuery, $sort='', $fields='*', 
                                      $limitfrom='', $limitnum='');

echo '

<style>

#modulelist {  
  font-family:arial; 
  font-size: 10pt; 
   }
</style>

';

echo "<div id=\"modulelist\">";
echo "<table width = '700px'>";

	foreach($allRecords as $record){
                
                echo '<tr>';
                echo '<td width=\'100px\'><b>' . $record['Crse_NUMB'] . '</b></td>';
  		echo '<td width=\'150px\'>' . $record['Crse_Title'] . '</td>';
	        echo '<td width=\'20px\'>' . $record['Dept'] . '</td>';
  		echo '<td width=\'150px\'>' . $record['Subj_Desc'] . '</td>';
  		echo '<td width=\'70px\'><a href="#">Add Module</a></td>';  		
		echo '</tr>';
	}

echo "</table>";

echo "</div>";


?>
