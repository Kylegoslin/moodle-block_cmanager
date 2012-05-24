<?php
ini_set('display_errors', 1); 
ini_set('log_errors', 1); 
error_reporting(E_ALL);


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
