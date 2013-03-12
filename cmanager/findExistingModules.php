<?php
/* --------------------------------------------------------------- 

 Course Request Manager

 2012 - 2013
 Kyle Goslin, Daniel McSweeney



 --------------------------------------------------------------- */
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
