<?php
// Last updated 19/1/2011
// Created!

set_include_path('../');
include("../lib/admin.php"); 
include("inc/helpers.php"); 
// error_reporting(E_ALL ^ E_DEPRECATED);

if ($_POST && $_FILES['file_name']){
	
	// Set up file
	$fileExtensions = array('csv'); // FILES
	$importLanguage	= req('lang_csv');
	$file 			= fopen($_FILES['file_name']['tmp_name'], 'r');
	
	$n = 0;
	while(!feof($file)) {
		$row = fgetcsv($file, 1024, ';');
		
		if ($n==0) {
			
			// Set up what indexes have the content for the selected language
			$aLanguages 		= $row;
			$thisTradIndex 		= array_search($importLanguage, $aLanguages);
			$thisArtIndex 		= array_search('art_'.$importLanguage, $aLanguages);
			$thisClaveIndex		= array_search('clave', $aLanguages);
			
		} else {
			
			// Update table with content if not empty
			$thisTraduccion = (array_key_exists($thisTradIndex, $row)) ? mysql_real_escape_string(mb_convert_encoding($row[$thisTradIndex], 'UTF-8')) : '';
			if ($thisTraduccion != '') {
				$thisClave = $row[$thisClaveIndex];
				$tradQuery = "UPDATE {$xname}_traducciones SET {$importLanguage} = '{$thisTraduccion}' WHERE clave = '{$thisClave}'";
				// echo 'trad:'.$tradQuery.'<br/>';
				mysql_query ($tradQuery) or die(mysql_error().' SQL:'.$tradQuery);		
				
			}
			
			$thisArticulo = (array_key_exists($thisArtIndex, $row)) ? mysql_real_escape_string(mb_convert_encoding($row[$thisArtIndex], 'UTF-8')) : '';
			if ($thisArticulo != '' && $thisArticulo != 'NULL') {
				$artQuery = "UPDATE {$xname}_traducciones SET art_{$importLanguage} = '{$thisArticulo}' WHERE clave = '{$thisClave}'";
				// echo 'art:'.$artQuery.'<br/>';	
				mysql_query ($artQuery) or die(mysql_error().' SQL:'.$artQuery);	
				
			}

		}
		
		$n++;
	}
	
	header('Location: traducciones.php');
	
}


// End of file