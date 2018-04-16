<?php 
set_include_path('../');
include("lib/admin.php"); 
require_once("inc/helpers.php");

$array	= $_POST['arrayorder'];
$table = $_POST['table'];
$tableArray = explode('_',$table);
$clave = $tableArray[1];

if ($_POST['update'] == "update"){
	$count = 1;
	foreach ($array as $idval) {
		$query = "UPDATE {$xname}_images_{$clave} SET orden = " . $count . " WHERE id = " . $idval;
		//echo $query;
		mysql_query($query) or die('Error, update query failed');
		$count ++;	
	}
	//echo 'Guardando orden';
}

// End of file