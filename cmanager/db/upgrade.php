<?php


function xmldb_block_cmanager_upgrade($oldversion) {
global $CFG, $DB;

$dbman = $DB->get_manager();

$result = true;

$newrec = new stdClass();
$newrec->varname = 'selfcat';
$newrec->value = 'no';

$DB->insert_record('block_cmanager_config', $newrec, false);


//alter database for required/optional fields

// Conditionally launch rename field timesent

$table = new xmldb_table('block_cmanager_formfields');
$field = new xmldb_field('reqfield', XMLDB_TYPE_INTEGER, '10', null, null, null, null);

if (!$dbman->field_exists($table, $field)) {
  $dbman->add_field($table, $field);
}


upgrade_block_savepoint($result, 2013041131, 'cmanager');



return $result;

}
?>