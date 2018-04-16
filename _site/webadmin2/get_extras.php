<?php

set_include_path('../');
require_once("../lib/admin.php");

$query = "SELECT * FROM ".XNAME."_extras ORDER BY orden";
$sql = $db->dataset($query);

header('Content-Type: application/json');
echo json_encode($sql);

// End of file