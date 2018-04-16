<?php
// Last updated 19/1/2011
// Created!

set_include_path('../');
include("../lib/admin.php"); 
include("inc/helpers.php"); 
require_once('webadmin2/config/config.php');

$clave_nueva = req('clave_nueva');
	
// Editar contenido

if (req('act')=='edit') {
	
	$query = "UPDATE ".$xname."_traducciones SET ";
	foreach ($languages as $k => $v) { 
		$query = $query." ".$v." = '".req($v)."',";	
		$query = $query."art_".$v." = '".req('art_'.$v)."',";	
	}
	$query = rtrim($query,',');
	$query = $query." WHERE clave = '".req(clave)."'";
	// echo $query;
	run_query($query) or die(mysql_error());
	echo '('.$clave.') GUARDADO';
}

// Añadir contenido
if (req('act')=='add' && $clave_nueva!='') {

	$query = "SELECT * FROM {$xname}_traducciones WHERE clave='$clave_nueva'";
	$sql = record($query);
	if (!$sql){
		$query = "INSERT INTO ".$xname."_traducciones (clave) VALUES ('{$clave_nueva}')";
		//echo $query;
		run_query($query) or die(mysql_error());
		echo '('.$clave_nueva.') AÑADIDO';
	} else {
		echo '¡Clave ya existe!';
	}
}

// Descargar CVS
if (req('act')=='cvs'){
	
	// Get info from database
	$query = "SELECT * FROM {$xname}_traducciones WHERE used = 1 ORDER BY id";
	$sql = dataset($query);
	
	// Set up 
	header('Content-Type: text/csv; charset=Windows-1252');
	header('Content-Disposition: attachment; filename='.$xname.'_traducciones_'.date('Y-m-d').'.csv');	
	$output = fopen('php://output', 'w');
	$fields = $languages;
	array_unshift($fields, 'clave');
	array_unshift($fields, 'id');
	
	// Add content to file
	fputcsv($output, $fields, ';');
	foreach ($sql as $k=>$v) {
		$aFields = array();
		$aFields[] = $v['id'];
		$aFields[] = $v['clave'];
		foreach ($languages as $l) {
			$aFields[] = utf8_decode($v[$l]);
		}
		fputcsv($output, $aFields, ';');
	}
	
}

// End of file