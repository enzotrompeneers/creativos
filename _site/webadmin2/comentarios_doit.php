<?php
set_include_path('../');
include("../lib/admin.php"); 
	
// Make sure session is TRUE
if ($_SESSION['Admin'] != TRUE) { header('Location: ../admin/');break;}
set_include_path('../');

// Editar contenido

$table 		= $_GET['table'];
$id 		= $_GET['id'];
	
if (req('act')=='edit') {
	
	$query 		= "UPDATE ".$table." SET ";
	foreach ($languages as $k => $v) { 
		$query 		= $query."descr_".$v." = '".req('descr_'.$v)."',";	
	}
	$query 		= rtrim($query,',');
	$query 		= $query." WHERE id = '".$id."'";
	//echo $query;
	run_query($query);
	echo '<span style="color:#000;">¡GUARDADO!</span>';
}

// End file