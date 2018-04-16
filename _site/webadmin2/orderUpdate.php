<?php 
set_include_path('../');
include("lib/admin.php");
//error_reporting(E_ALL); ini_set('display_errors', '1'); 
require_once("lib/helpers.php");
$table = $_GET['table'];
$array	= $_POST['arrayorder'];
$table = $_POST['table'];
$tableArray = explode('_',$table);
$clave = $tableArray[1];

if ($_POST['update'] == "update"){
	$count = 1;
	foreach ($array as $idval) {
		$query = "UPDATE {$table} SET orden = " . $count . " WHERE id = " . $idval."\n";
		echo $query;
		mysql_query($query) or die('Error, update query failed');
		$count ++;	
	}
	//echo 'Guardando orden';
}

// End file