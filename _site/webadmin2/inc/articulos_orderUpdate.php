<?php 
set_include_path('../../');
include("../../lib/admin.php");
// Make sure session is TRUE
if ($_SESSION['Admin'] != TRUE) { header('Location: ../login.php');break;} 
//error_reporting(E_ALL); ini_set('display_errors', '1'); 
require_once("lib/helpers.php");
$table = $xname.'_articulos';


if ($_POST['list']){


	$n = 0;
	foreach ($_POST['list'] as $k=>$v) {
		$orden = $n+1;
		$id = $k;
		$parent = ($v=='null')?'0':$v;
		$insert = "UPDATE {$xname}_articulos SET orden = {$orden}, parent_id = {$parent} WHERE id = {$id}";
		// echo $insert.'<br />';
		mysql_query($insert) or die(mysql_error());
		$n++;
	}
	// foreach ($_POST as $idval) {
				// $query = "UPDATE {$table} SET orden = " . $count . " WHERE id = " . $idval."\n";
				// echo $query;
				
		// mysql_query($query) or die('Error, insert query failed');
		// $count ++;	
	// }
	//echo 'Guardando orden';
}

// End file